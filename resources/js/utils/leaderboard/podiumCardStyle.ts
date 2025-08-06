export const getPodiumCardStyle = (rank: number): string => {
    switch (rank) {
        case 1:
            return 'bg-gradient-to-br from-yellow-400 to-yellow-600 border-yellow-500';
        case 2:
            return 'bg-gradient-to-br from-gray-300 to-gray-500 border-gray-400';
        case 3:
            return 'bg-gradient-to-br from-amber-500 to-amber-700 border-amber-600';
        default:
            return 'bg-white border-gray-200';
    }
};
