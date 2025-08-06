<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(CommentRequest $request)
    {

        $data = $request->validated();

        try {
            $comment = $this->commentService->createComment($data);
            $comment->load(['user:id,name,email']);
            return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat komentar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getReplies(int $commentId)
    {
        try {
            $replies = $this->commentService->getReplies($commentId);

            return response()->json([
                'success' => true,
                'message' => 'Balasan komentar berhasil diambil',
                'data' => [
                    'replies' => $replies
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil balasan komentar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getByReport(Request $request, int $reportId)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $comments = $this->commentService->getCommentsByReport($reportId, $perPage);

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil diambil',
                'data' => [
                    'comments' => $comments->items(),
                    'pagination' => [
                        'current_page' => $comments->currentPage(),
                        'per_page' => $comments->perPage(),
                        'total' => $comments->total(),
                        'last_page' => $comments->lastPage(),
                        'from' => $comments->firstItem(),
                        'to' => $comments->lastItem(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil komentar: ' . $e->getMessage()
            ], 500);
        }
    }
}
