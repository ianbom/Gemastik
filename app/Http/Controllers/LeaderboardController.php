<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Citizen/Leaderboard/LeaderBoardPage');
    }
    public function indexLeaderboard()
    {
        // Top Reporter
        $top3Reporters = $this->getTopReporters(3);
        $top10Reporters = $this->getTopReporters(10);

        $top3MissionVolunteers = $this->getMissionVolunteersQuery()
            ->take(3)
            ->get()
            ->map(function ($user) {
                $user->missions_count = $user->mission_volunteers_count;
                return $user;
            });
        // Top Volunteer
        $top10MissionVolunteers = $this->getMissionVolunteersQuery()
            ->take(10)
            ->get()
            ->map(function ($user) {
                $user->missions_count = $user->mission_volunteers_count;
                return $user;
            });

        // Top Donor
        $top3Donors = User::withSum('paidDonations', 'amount')
            ->orderByDesc('paid_donations_sum_amount')
            ->take(3)
            ->get()
            ->map(function ($user) {
                $user->total_donation = $user->paid_donations_sum_amount;
                return $user;
            });

        $top10Donors = User::withSum('paidDonations', 'amount')
            ->orderByDesc('paid_donations_sum_amount')
            ->take(10)
            ->get()
            ->map(function ($user) {
                $user->total_donation = $user->paid_donations_sum_amount;
                return $user;
            });

        return Inertia::render('Citizen/Leaderboard/LeaderBoardPage', [
            'top3Reporters' => $top3Reporters,
            'top10Reporters' => $top10Reporters,
            'top3MissionVolunteers' => $top3MissionVolunteers,
            'top10MissionVolunteers' => $top10MissionVolunteers,
            'top3Donors' => $top3Donors,
            'top10Donors' => $top10Donors,
        ]);
    }
    private function getMissionVolunteersQuery()
    {
        return User::withCount(['missionVolunteers' => function ($query) {
            $query->where('participation_status', 'attended');
        }])
            ->orderByDesc('mission_volunteers_count');
    }
    private function getTopReporters($limit)
    {
        return User::withCount([
            'reports' => function ($query) {
                $query->whereNotIn('status', ['pending', 'rejected']);
            }
        ])
            ->orderByDesc('reports_count')
            ->take($limit)
            ->get();
    }
}
