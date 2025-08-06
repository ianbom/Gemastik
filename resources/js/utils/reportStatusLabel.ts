export const getStatusLabel = (status: string): string => {
    const normalized = status.trim().toLowerCase();

    const map: Record<string, string> = {
        completed: 'Selesai',
        'on-progress': 'Sedang Diproses',
        verified: 'Terverifikasi',
        rejected: 'Ditolak',
        'under-authority': 'Ditangani Pihak Berwenang',
        pending: 'Menunggu',
    };

    return map[normalized] ?? 'Status Tidak Diketahui';
};
