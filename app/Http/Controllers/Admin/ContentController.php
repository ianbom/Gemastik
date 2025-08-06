<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContentRequest;
use App\Models\Content;
use App\Models\ContentMedia;
use App\Services\ContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ContentController extends Controller
{

    protected $contentService;
    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

     public function index(Request $request)
    {
        $filters = $this->contentService->buildFilter($request);
        $contents = $this->contentService->getContentByFilter($filters);
        return view('admin.content.index', ['content' => $contents]);
    }

    public function create()
    {
        return view('admin.content.create');
    }

    public function store(CreateContentRequest $request)
    {
        try {
            $content = $this->contentService->createContent(
                $request->validated(),
                Auth::id()
            );

            return redirect()
                ->route('admin.contents.index')
                ->with('success', 'Konten berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat konten: ' . $e->getMessage());
        }
    }

    public function edit(Content $content)
    {
        return view('admin.content.edit', ['content' => $content]);
    }

    public function update(Content $content, CreateContentRequest $request)
    {
        try {
            $content = $this->contentService->updateContent(
                $content->id,
                $request->validated(),

            );

            return redirect()
                ->route('admin.contents.index')
                ->with('success', 'Konten berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal update konten: ' . $e->getMessage());
        }
    }

    public function deleteMedia(ContentMedia $contentMedia)
    {

        DB::beginTransaction();
        try {
            $contentMedia->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Media kontent berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    public function destroy(Content $content){

        DB::beginTransaction();
        try {
            $content->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Konten berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus konten');
        }
    }
}
