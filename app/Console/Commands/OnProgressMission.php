<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Report;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OnProgressMission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'missions:set-on-progress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengubah status misi menjadi "on-progress" jika tanggal jadwalnya adalah hari ini.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan misi yang dijadwalkan untuk hari ini...');

        // 1. Cari semua misi yang statusnya 'open' DAN tanggal jadwalnya adalah hari ini.
        // Kita menggunakan whereDate untuk membandingkan hanya bagian tanggal, mengabaikan jam.
        $missionsToStart = Mission::where('status', 'open')
            ->whereDate('scheduled_date', today())
            ->get();

        if ($missionsToStart->isEmpty()) {
            $this->info('Tidak ada misi baru yang dimulai hari ini.');
            return 0; // Selesai dengan sukses
        }

        $this->info("Ditemukan {$missionsToStart->count()} misi untuk diubah statusnya menjadi 'on-progress'.");
        
        $updatedMissionCount = 0;
        $updatedReportCount = 0;

        // 2. Loop melalui setiap misi yang ditemukan
        foreach ($missionsToStart as $mission) {
            DB::beginTransaction();
            try {
                // 3. Ubah status misi menjadi 'on-progress'
                $mission->update(['status' => 'on-progress']);
                $this->line("-> Misi #{$mission->id} ('{$mission->title}') status diubah menjadi on-progress.");
                $updatedMissionCount++;

                // 4. Periksa apakah misi ini terkait dengan sebuah laporan
                if ($mission->report_id) {
                    $report = Report::find($mission->report_id);

                    // 5. Jika ada laporan terkait DAN statusnya belum on-progress/completed, ubah juga statusnya.
                    if ($report && !in_array($report->status, ['on-progress', 'completed'])) {
                        $report->update(['status' => 'on-progress']);
                        $this->line("   -> Laporan terkait #{$report->id} ('{$report->title}') status diubah menjadi on-progress.");
                        $updatedReportCount++;
                    }
                }

                // Opsional: Kirim notifikasi ke semua sukarelawan bahwa misi telah dimulai
                // NotificationJob::dispatch(...)

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Gagal memproses Misi #{$mission->id}: " . $e->getMessage());
                Log::error("Scheduler 'missions:set-on-progress' gagal untuk Misi ID {$mission->id}: " . $e->getMessage());
            }
        }

        $this->info("Selesai. {$updatedMissionCount} misi dan {$updatedReportCount} laporan telah diperbarui.");
        return 0; // Selesai dengan sukses
    }
}
