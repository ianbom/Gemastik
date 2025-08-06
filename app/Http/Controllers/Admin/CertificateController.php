<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Mission;
use App\Models\MissionVolunteer;
use App\Models\UserCertificate;
use App\Services\CertificateService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class CertificateController extends Controller
{
    protected $certificateService;
     public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

public function generateCertificate()
    {

        $logoPath = storage_path('public/LogoSobatBumi.png');

        // if (!file_exists($logoPath)) {

        //     abort(500, 'File logo tidak ditemukan di storage.');
        // }

        // $logoData = base64_encode(file_get_contents($logoPath));
        // $logoBase64 = 'data:image/png;base64,' . $logoData;


        $pdf = Pdf::loadView('admin.certificate.template.template_2', [

        ]);

        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('certificate.pdf');
        return view('admin.certificate.template.template_2');
    }


    public function createSertificateUser(Request $request){

        $mission = Mission::findOrFail($request->mission_id);
        $volunteers = MissionVolunteer::where('mission_id', $mission->id)
        ->where('participation_status', 'attended')->get();

        $title = $request->title;
        $date = $request->date;

        foreach($volunteers as $volunteer){

        $this->generateCertificate($volunteer, $title, $date);

       }

    }

    public function generate(Request $request)
    {
        $request->validate([
            'mission_id' => 'required|exists:missions,id',
            'title'      => 'required|string|max:255',
            'date'       => 'required|date',
        ]);

        try {
            $generatedCount = $this->certificateService->generateForMissionVolunteers(
                $request->mission_id,
                $request->title,
                $request->date
            );
            return redirect()->back()
                ->with('success', "Sertifikat untuk {$generatedCount} volunteer berhasil dibuat dan disimpan.");

        } catch (Exception $e) {
            Log::error('Gagal generate sertifikat: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create(){
        $missions = Mission::all();
        // dd($missions);
        return view('admin.certificate.create', ['missions' => $missions]);
    }

    public function index(){
        $certificates = Certificate::all();
        return view('admin.certificate.index', ['certificates' => $certificates]);
    }

}
