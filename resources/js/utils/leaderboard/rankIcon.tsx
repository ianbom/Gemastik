import { Award, Medal, Trophy } from 'lucide-react';
export const getRankIcon = (rank: number): JSX.Element => {
    switch (rank) {
        case 1:
            return <Trophy className="h-8 w-8 text-yellow-500" />;
        case 2:
            return <Medal className="h-8 w-8 text-gray-400" />;
        case 3:
            return <Award className="h-8 w-8 text-amber-600" />;
        default:
            return (
                <span className="text-2xl font-bold text-white">#{rank}</span>
            );
    }
};
