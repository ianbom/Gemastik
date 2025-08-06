<?php

namespace App\Console\Commands;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AwardMissionBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:award-mission-badges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan untuk lencana penyelesaian 5 misi...');

        $targetBadgeId = 1;

        $badge = Badge::find($targetBadgeId);
        if (!$badge) {
            $this->error("Badge dengan ID {$targetBadgeId} tidak ditemukan. Command dihentikan.");
            Log::error("Scheduled Command: Badge dengan ID {$targetBadgeId} tidak ditemukan.");
            return 1;
        }

        $eligibleUsers = User::withCount(['missionVolunteers' => function ($query) {

            $query->where('participation_status', 'attended');
        }])
            ->having('mission_volunteers_count', '>=', 1)
            ->get();

        if ($eligibleUsers->isEmpty()) {
            $this->info('Tidak ada pengguna baru yang memenuhi syarat untuk lencana ini.');
            return 0;
        }

        $this->info("Ditemukan {$eligibleUsers->count()} pengguna yang memenuhi syarat.");

        $awardedCount = 0;


        foreach ($eligibleUsers as $user) {
            if (!$user->badges()->where('badge_id', $targetBadgeId)->exists()) {

                $user->badges()->attach($targetBadgeId);

                $this->line("Memberikan lencana '{$badge->title}' kepada: {$user->name}");
                Log::info("Scheduled Command: Lencana '{$badge->title}' diberikan kepada user ID {$user->id}.");

                $awardedCount++;
            }
        }

        $this->info("Selesai. {$awardedCount} lencana baru telah diberikan.");
        return 0;
    }
}
