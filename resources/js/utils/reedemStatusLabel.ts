export const getReedemStatusLabel = (status: string): string => {
    const normalized = status.trim().toLowerCase();
    const map: Record<string, string> = {
        pending: 'Menunggu',
        processing: 'Sedang Diproses',
        completed: 'Selesai',
        shipped: 'Dalam Pengiriman',
        cancelled: 'Dibatalkan',
    };

    return map[normalized] ?? 'Status Tidak Diketahui';
};
