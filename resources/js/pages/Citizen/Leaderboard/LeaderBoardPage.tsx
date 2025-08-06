import CitizenLayout from '@/components/layouts/CitizenLayout';
import React from 'react';
import LeaderBoard from './leaderboard-page';
interface LeaderboardUser {
    id: number;
    name: string;
    profile_url: string | null;
    reports_count?: number;
    missions_count?: number;
    total_donation?: number;
}

interface LeaderboardPageProps {
    top3Reporters?: LeaderboardUser[];
    top3MissionVolunteers?: LeaderboardUser[];
    top3Donors?: LeaderboardUser[];
    top10Reporters?: LeaderboardUser[];
    top10MissionVolunteers?: LeaderboardUser[];
    top10Donors?: LeaderboardUser[];
}

const LeaderboardPage: React.FC<LeaderboardPageProps> = (props) => {
    return (
        <CitizenLayout currentPage="leaderboard">
            <LeaderBoard
                top3Reporters={props.top3Reporters}
                top3MissionVolunteers={props.top3MissionVolunteers}
                top3Donors={props.top3Donors}
                top10Reporters={props.top10Reporters}
                top10MissionVolunteers={props.top10MissionVolunteers}
                top10Donors={props.top10Donors}
            />
        </CitizenLayout>
    );
};

export default LeaderboardPage;
