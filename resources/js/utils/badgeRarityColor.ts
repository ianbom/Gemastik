export const getRarityColor = (rarity: string) => {
    switch (rarity) {
        case 'Gold':
            return 'border-yellow-400 bg-yellow-50';
        case 'Silver':
            return 'border-gray-400 bg-gray-50';
        case 'Bronze':
            return 'border-orange-400 bg-orange-50';
        default:
            return 'border-gray-200 bg-gray-50';
    }
};
