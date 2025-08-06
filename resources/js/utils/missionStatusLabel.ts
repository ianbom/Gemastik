export const getMissionStatusLabel = (status: string): string => {
    const normalized = status.trim().toLowerCase();
    const map: Record<string, string> = {
        open: 'Dibuka',
        'on-progress': 'Sedang Berlangsung',
        completed: 'Selesai',
        cancelled: 'Dibatalkan',
        'under-authority': 'Misi Pihak Berwenang',
    };

    return map[normalized] ?? 'Status Tidak Diketahui';
};
