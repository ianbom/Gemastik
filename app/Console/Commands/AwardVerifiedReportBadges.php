<?php

namespace App\Console\Commands;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AwardVerifiedReportBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:award-verified-report-badges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Badges ke user dengan 5 verifikasi laporan';


    public function handle()
    {
        $this->info('Memulai pengecekan untuk lencana 5 laporan terverifikasi...');

        $targetBadgeId = 2;

        $badge = Badge::findOrFail($targetBadgeId);
        if (!$badge) {
            $this->error("Badge dengan ID {$targetBadgeId} tidak ditemukan. Command dihentikan.");
            Log::error("Scheduled Command: Badge dengan ID {$targetBadgeId} tidak ditemukan.");
            return 1;
        }
        
        $eligibleUsers = User::withCount(['reports' => function ($query) {
            $query->where('status', 'verified');
        }])
        ->having('reports_count', '>=', 5)
        ->get();




        if ($eligibleUsers->isEmpty()) {
            $this->info('Tidak ada pengguna baru yang memenuhi syarat untuk lencana ini.');
            return 0; // Sukses
        }

        $this->info("Ditemukan {$eligibleUsers->count()} pengguna yang memenuhi syarat.");

        $awardedCount = 0;

        // 3. Loop melalui setiap pengguna yang memenuhi syarat.
        foreach ($eligibleUsers as $user) {
            // 4. Periksa apakah pengguna sudah memiliki lencana ini untuk mencegah duplikasi.
            if (!$user->badges()->where('badge_id', $targetBadgeId)->exists()) {

                // 5. Jika belum, berikan lencananya.
                $user->badges()->attach($targetBadgeId);

                $this->line("Memberikan lencana '{$badge->title}' kepada: {$user->name}");
                Log::info("Scheduled Command: Lencana '{$badge->title}' diberikan kepada user ID {$user->id}.");

                // Opsional: Kirim notifikasi ke pengguna
                // Notification::send($user, new BadgeAwarded($badge));

                $awardedCount++;
            }
        }

        $this->info("Selesai. {$awardedCount} lencana baru telah diberikan.");
        return 0; // Sukses
    }
}
