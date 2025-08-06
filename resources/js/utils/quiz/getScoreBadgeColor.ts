export const getScoreBadgeColor = (score: number) => {
    if (score === 100) return 'bg-emerald-100 text-emerald-800';
    if (score >= 80) return 'bg-sky-100 text-sky-800';
    if (score >= 60) return 'bg-amber-100 text-amber-800';
    return 'bg-rose-100 text-rose-800';
};
