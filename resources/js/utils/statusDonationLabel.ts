export const getStatusDonationLabel = (status: string): string => {
    const normalized = status.trim().toLowerCase();

    const map: Record<string, string> = {
        paid: 'Pembayaran Berhasil',
        expired: 'Pembayaran Kadaluwarsa',
        cancelled: 'Pembayaran Dibatalkan',
        pending: 'Menunggu Pembayaran   ',
    };

    return map[normalized] ?? 'Status Tidak Diketahui';
};
