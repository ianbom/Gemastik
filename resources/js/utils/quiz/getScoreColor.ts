export const getScoreColor = (score: number) => {
    if (score === 100) return 'text-green-600';
    if (score >= 80) return 'text-blue-600';
    if (score >= 60) return 'text-yellow-600';
    return 'text-red-600';
};
