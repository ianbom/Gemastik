<?php

namespace App\Http\Controllers\Community;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContentRequest;
use App\Models\Content;
use App\Models\ContentMedia;
use App\Services\ContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ContentController extends Controller
{

    protected $contentService;
    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['content_type', 'search']);
            $perPage = $request->get('per_page', 15);
            $contents = $this->contentService->getContents($filters, $perPage);
            return Inertia::render('Community/EducationalContent/EducationalContentPage', [
                'contents' => $contents
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show(int $id)
    {
        try {
            $content = $this->contentService->getContentById($id);
            if (!$content) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Konten tidak ditemukan'
                ], 404);
            }
            return Inertia::render('Community/EducationalContent/EducationalContentDetailPage', [
                'content' => $content
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data konten',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
