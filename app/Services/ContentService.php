<?php

namespace App\Services;

use App\Models\Content;
use App\Models\ContentMedia;
use App\Services\Service;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use function Illuminate\Log\log;

class ContentService extends Service
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getContentByFilter(array $filters = [])
    {

        $allowedFilters = [
            'contents.title'         => 'like',
            'contents.content_type'  => 'value',
            'contents.created_at'    => 'date',
            'users.name'             => 'like',
        ];

        $selectColumns = [
            'contents.*',
            'users.name as author_name',
        ];

        // Query ke model Content
        $query = Content::select($selectColumns)
            ->join('users', 'contents.author_user_id', '=', 'users.id')
            ->orderBy('contents.created_at', 'desc');

        // Menerapkan filter ke query (asumsi method applyFilters sudah ada)
        $query = $this->applyFilters($query, $filters, $allowedFilters);

        // Eager load relasi 'author' untuk data lengkap di view
        $query->with(['author']);

        return $query->get();
    }

    public function buildFilter($request)
    {
        $filters = [];

        // Filter berdasarkan pencarian (bisa untuk judul atau nama author)
        if ($request->filled('search')) {
            $filters['contents.title'] = $request->input('search');
            // Jika ingin pencarian juga berlaku untuk nama author, uncomment baris di bawah
            // $filters['users.name'] = $request->input('search');
        }

        // Filter berdasarkan tipe konten
        if ($request->filled('content_type')) {
            $filters['contents.content_type'] = $request->input('content_type');
        }

        // Filter berdasarkan rentang tanggal pembuatan
        if ($request->filled('created_from') && $request->filled('created_to')) {
            $filters['contents.created_at'] = [
                'start' => $request->input('created_from'),
                'end'   => $request->input('created_to')
            ];
        }

        return $filters;
    }


    public function createContent(array $data, int $authorId): Content
    {
        try {
            DB::beginTransaction();

            $content = Content::create([
                'author_user_id' => $authorId,
                'title' => $data['title'],
                'body' => $data['body'],
                'content_type' => $data['content_type'] ?? null,
            ]);
            // Handle uploaded files
            if (isset($data['media']) && is_array($data['media']) && !empty($data['media'])) {
                foreach ($data['media'] as $file) {
                    if ($file && $file->isValid()) {
                        try {
                            $mediaUrl = $this->uploadFile($file);
                            $mediaType = $this->getMediaType($file);

                            // Debug: Log data yang akan diinsert
                            Log::info('Creating media with data:', [
                                'content_id' => $content->id,
                                'media_url' => $mediaUrl,
                                'media_type' => $mediaType,
                            ]);

                            ContentMedia::create([
                                'content_id' => $content->id,
                                'media_url' => $mediaUrl,
                                'media_type' => $mediaType,
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Error uploading file: ' . $e->getMessage());
                            throw new Exception('Gagal mengupload file: ' . $e->getMessage());
                        }
                    }
                }
            }

            // Handle media URLs (jika ada)
            if (
                isset($data['media_urls']) && isset($data['media_types']) &&
                is_array($data['media_urls']) && is_array($data['media_types'])
            ) {

                $mediaUrls = $data['media_urls'];
                $mediaTypes = $data['media_types'];

                for ($i = 0; $i < count($mediaUrls); $i++) {
                    if (
                        isset($mediaUrls[$i]) && isset($mediaTypes[$i]) &&
                        !empty($mediaUrls[$i]) && !empty($mediaTypes[$i])
                    ) {

                        ContentMedia::create([
                            'content_id' => $content->id,
                            'media_url' => $mediaUrls[$i],
                            'media_type' => $mediaTypes[$i],
                        ]);
                    }
                }
            }

            DB::commit();

            return $content;
        } catch (Exception $e) {
            DB::rollback();

            throw new Exception('Gagal membuat konten: ' . $e->getMessage());
        }
    }

    private function uploadFile($file): string
    {
        if (!$file || !$file->isValid()) {
            throw new Exception('File tidak valid');
        }

        $fileName = $file->getClientOriginalName();

        $filePath = $file->storeAs('contents', $fileName, 'public');

        if (!$filePath) {
            throw new Exception('Gagal menyimpan file');
        }

        return $filePath;
    }

    private function getMediaType($file): string
    {
        if (!$file || !$file->isValid()) {
            return 'image'; // default fallback
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'wmv'];
        $documentExtensions = ['pdf'];

        if (in_array($extension, $imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $videoExtensions)) {
            return 'video';
        }elseif (in_array($extension, $documentExtensions)) {
            return 'document';
        }

        return 'image';
    }

    public function updateContent(int $contentId, array $data): Content
    {
        try {
            DB::beginTransaction();

            // Find the content
            $content = Content::findOrFail($contentId);


            // Update content data
            $content->update([
                'title' => $data['title'] ?? $content->title,
                'body' => $data['body'] ?? $content->body,
                'content_type' => $data['content_type'] ?? $content->content_type,
            ]);

            // Handle media deletion if specified
            if (isset($data['delete_media_ids']) && is_array($data['delete_media_ids'])) {
                foreach ($data['delete_media_ids'] as $mediaId) {
                    $media = ContentMedia::where('content_id', $content->id)
                        ->where('id', $mediaId)
                        ->first();

                    if ($media) {
                        // Delete file from storage if it exists
                        if (Storage::disk('public')->exists($media->media_url)) {
                            Storage::disk('public')->delete($media->media_url);
                        }

                        // Delete media record
                        $media->delete();
                    }
                }
            }

            // Handle new uploaded files
            if (isset($data['media']) && is_array($data['media']) && !empty($data['media'])) {
                foreach ($data['media'] as $file) {
                    if ($file && $file->isValid()) {
                        try {
                            $mediaUrl = $this->uploadFile($file);
                            $mediaType = $this->getMediaType($file);

                            // Debug: Log data yang akan diinsert
                            Log::info('Creating media with data:', [
                                'content_id' => $content->id,
                                'media_url' => $mediaUrl,
                                'media_type' => $mediaType,
                            ]);

                            ContentMedia::create([
                                'content_id' => $content->id,
                                'media_url' => $mediaUrl,
                                'media_type' => $mediaType,
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Error uploading file: ' . $e->getMessage());
                            throw new Exception('Gagal mengupload file: ' . $e->getMessage());
                        }
                    }
                }
            }

            // Handle new media URLs (jika ada)
            if (
                isset($data['media_urls']) && isset($data['media_types']) &&
                is_array($data['media_urls']) && is_array($data['media_types'])
            ) {

                $mediaUrls = $data['media_urls'];
                $mediaTypes = $data['media_types'];

                for ($i = 0; $i < count($mediaUrls); $i++) {
                    if (
                        isset($mediaUrls[$i]) && isset($mediaTypes[$i]) &&
                        !empty($mediaUrls[$i]) && !empty($mediaTypes[$i])
                    ) {

                        ContentMedia::create([
                            'content_id' => $content->id,
                            'media_url' => $mediaUrls[$i],
                            'media_type' => $mediaTypes[$i],
                        ]);
                    }
                }
            }

            DB::commit();

            // Refresh content to get updated data
            $content->refresh();

            return $content;
        } catch (Exception $e) {
            DB::rollback();

            throw new Exception('Gagal mengupdate konten: ' . $e->getMessage());
        }
    }

    // Helper function untuk menghapus semua media dari konten
    public function deleteAllContentMedia(int $contentId): bool
    {
        try {
            $mediaItems = ContentMedia::where('content_id', $contentId)->get();

            foreach ($mediaItems as $media) {
                // Delete file from storage if it exists
                if (Storage::disk('public')->exists($media->media_url)) {
                    Storage::disk('public')->delete($media->media_url);
                }

                // Delete media record
                $media->delete();
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting all content media: ' . $e->getMessage());
            return false;
        }
    }

    // Helper function untuk replace semua media (hapus semua lalu tambah baru)
    public function replaceAllContentMedia(int $contentId, array $data): bool
    {
        try {
            // Hapus semua media yang ada
            $this->deleteAllContentMedia($contentId);

            // Handle new uploaded files
            if (isset($data['media']) && is_array($data['media']) && !empty($data['media'])) {
                foreach ($data['media'] as $file) {
                    if ($file && $file->isValid()) {
                        $mediaUrl = $this->uploadFile($file);
                        $mediaType = $this->getMediaType($file);

                        ContentMedia::create([
                            'content_id' => $contentId,
                            'media_url' => $mediaUrl,
                            'media_type' => $mediaType,
                        ]);
                    }
                }
            }

            // Handle new media URLs
            if (
                isset($data['media_urls']) && isset($data['media_types']) &&
                is_array($data['media_urls']) && is_array($data['media_types'])
            ) {

                $mediaUrls = $data['media_urls'];
                $mediaTypes = $data['media_types'];

                for ($i = 0; $i < count($mediaUrls); $i++) {
                    if (
                        isset($mediaUrls[$i]) && isset($mediaTypes[$i]) &&
                        !empty($mediaUrls[$i]) && !empty($mediaTypes[$i])
                    ) {

                        ContentMedia::create([
                            'content_id' => $contentId,
                            'media_url' => $mediaUrls[$i],
                            'media_type' => $mediaTypes[$i],
                        ]);
                    }
                }
            }

            return true;
        } catch (Exception $e) {
            Log::error('Error replacing all content media: ' . $e->getMessage());
            return false;
        }
    }
    public function getContents(array $filters = [], int $perPage = 15)
    {
        $query = Content::with(['author', 'media']);
        // Filter by type
        if (isset($filters['content_type'])) {
            $query->where('content_type', $filters['content_type']);
        }
        // Search by title or description
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('body', 'like', '%' . $filters['search'] . '%');
            });
        }
        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    public function getContentById(int $id): ?Content
    {
        return Content::with([
            'author',
            'media',
        ])
            ->find($id);
    }
}
