<?php

namespace App\Services;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

   public function createComment(array $data): Comment
    {
        $commentData = [
            'reply_id' => $data['reply_id'] ?? null,
            'user_id' => Auth::id(),
            'report_id' => $data['report_id'],
            'comment' => $data['comment'] ?? null,
        ];

        // Handle media upload if exists
        if (isset($data['media'])) {
            $mediaData = $this->handleMediaUpload($data['media'], $data['media_type'] ?? null);
            $commentData['media_url'] = $mediaData['url'];
            $commentData['media_type'] = $mediaData['type'];
        }

        return Comment::create($commentData);
    }


    private function handleMediaUpload($file, $mediaType = null): array
    {
        $filePath = $this->uploadFile($file);
        $detectedMediaType = $this->getMediaType($file);

        return [
            'url' => $filePath,
            'type' => $mediaType ?? $detectedMediaType
        ];
    }

    private function uploadFile($file): string
    {
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('comments', $fileName, 'public');

        return $filePath;
    }


    private function getMediaType($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'wmv'];

        if (in_array($extension, $imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $videoExtensions)) {
            return 'video';
        }

        return 'image';
    }

    public function getReplies(int $commentId)
    {
        return Comment::with(['user:id,name,email'])
            ->where('reply_id', $commentId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getCommentsByReport(int $reportId)
    {
        return Comment::with('user', 'replies', 'replies.user')
            ->where('report_id', $reportId)
            ->whereNull('reply_id')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
