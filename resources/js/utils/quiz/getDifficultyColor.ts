export const getDifficultyColor = (difficulty?: string) => {
    switch (difficulty) {
        case 'mudah':
            return 'bg-green-100 text-green-800';
        case 'sedang':
            return 'bg-yellow-100 text-yellow-800';
        case 'sulit':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};
