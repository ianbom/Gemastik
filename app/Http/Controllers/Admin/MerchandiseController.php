<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMerchandiseRequest;
use App\Http\Requests\UpdateMerchandiseRequest;
use App\Models\Merchandise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MerchandiseController extends Controller
{

    public function index()
    {
        $merchandises = Merchandise::all();
        return view('admin.merchandise.index', ['merchandise' => $merchandises]);
    }

    public function create()
    {
        return view('admin.merchandise.create');
    }

    public function store(CreateMerchandiseRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $imagePath = $data['image_url']->store('merchandise', 'public');
            $data['image_url'] = $imagePath;
            $result = Merchandise::create($data);
            DB::commit();
            return redirect()->back()->with('success', 'Merchandise berhasil dibuat');
        } catch (\Throwable $th) {
            DB::rollBack();
            // return response()->json(['err' => $th->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

     public function edit(Merchandise $merchandise)
    {
        return view('admin.merchandise.edit', ['merchandise' => $merchandise]);
    }

   public function update(Merchandise $merchandise, UpdateMerchandiseRequest $request){
    DB::beginTransaction();
    try {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image_url')) {
            // Delete old image if exists
            if ($merchandise->image_url && Storage::disk('public')->exists($merchandise->image_url)) {
                Storage::disk('public')->delete($merchandise->image_url);
            }

            // Store new image
            $imagePath = $request->file('image_url')->store('merchandise', 'public');
            $data['image_url'] = $imagePath;
        } else {
            // Keep the existing image if no new image is uploaded
            unset($data['image_url']); // Remove from update data to keep existing value
        }

        $merchandise->update($data);
        DB::commit();

        return redirect()->back()->with('success', 'Data berhasil diubah');
    } catch (\Throwable $th) {
        DB::rollBack();

        return redirect()->back()->with('error', 'Terjadi kesalahan');
    }
}


}
