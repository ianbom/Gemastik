<?php

namespace App\Services;

use App\Models\Mission;
use App\Models\MissionVolunteer;
use App\Models\UserCertificate;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }

     public function generateForMissionVolunteers(int $missionId, string $certificateTitle, string $certificateDate): int
    {
        $mission = Mission::findOrFail($missionId);

        $allAttendedVolunteers = MissionVolunteer::where('mission_id', $mission->id)
            ->where('participation_status', 'attended')
            ->with('user')
            ->get();

        if ($allAttendedVolunteers->isEmpty()) {
            throw new Exception('Tidak ada volunteer yang hadir pada misi ini.');
        }


        $volunteersToProcess = $allAttendedVolunteers->filter(function ($volunteer) {
            return is_null($volunteer->certificate_url);
        });


        if ($volunteersToProcess->isEmpty()) {
            throw new Exception('Semua volunteer yang hadir sudah memiliki sertifikat untuk misi ini.');
        }


        DB::beginTransaction();

        try {

            foreach ($volunteersToProcess as $volunteer) {

                $alreadyExists = UserCertificate::where('user_id', $volunteer->user_id)
                                                ->where('mission_id', $missionId)
                                                ->exists();

                if ($alreadyExists) {

                    continue;
                }

                $uniqueCode = $this->generateUniqueCertificateCode();

                $pdf = Pdf::loadView('admin.certificate.template.template_2', [
                    'volunteerName'    => $volunteer->user->name,
                    'missionTitle'     => $mission->title,
                    'certificateTitle' => $certificateTitle,
                    'certificateDate'  => $certificateDate,
                    'signerName'       => 'SobatBumi',
                    'certificateCode'  => $uniqueCode,

                ]);
                $pdf->setPaper('a4', 'portrait');

                $pdfFileName = 'sertifikat-' . Str::slug($mission->title) . '-' . Str::slug($volunteer->user->name) . '-' . time() . '.pdf';
                $filePath = 'certificates/' . $pdfFileName;

                Storage::disk('public')->put($filePath, $pdf->output());

                // Pastikan tabel `user_certificates` memiliki kolom `mission_id`
                UserCertificate::create([
                    'user_id'         => $volunteer->user_id,
                    'mission_id'      => $missionId, // Penting untuk pengecekan duplikat
                    'title'           => "Sertifikat Partisipasi: {$mission->title}", // Judul lebih deskriptif
                    'certificate_url' => $filePath,
                    'code'            => $this->generateUniqueCertificateCode()
                ]);

                // Update tabel mission_volunteers agar pengecekan pertama berfungsi di masa depan
                $volunteer->update(['certificate_url' => $filePath, 'awarded_at' => now()]);
            }

            DB::commit();

            // Kembalikan jumlah volunteer yang BARU diproses
            return $volunteersToProcess->count();

        } catch (Exception $e) {
            DB::rollBack();
            // Tambahkan detail error untuk debugging yang lebih baik
            throw new Exception('Gagal membuat sertifikat: ' . $e->getMessage());
        }
    }

    private function generateUniqueCertificateCode(): string
    {
        do {
            // Gabungkan prefix dengan 8 karakter acak (alphanumeric)
            $code = 'SB-CERT-' . strtoupper(Str::random(8));

            // Periksa apakah kode ini sudah ada di database
            $isCodeExists = UserCertificate::where('code', $code)->exists();

        } while ($isCodeExists); // Ulangi jika kode sudah ada

        return $code;
    }
}

