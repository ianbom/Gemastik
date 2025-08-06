export const getContentTypeLabel = (status: string): string => {
    const normalized = status.trim().toLowerCase();
    const map: Record<string, string> = {
        artikel: 'Artikel',
        video: 'Video',
        completed: 'Selesai',
        modul: 'Modul PDF',
    };

    return map[normalized] ?? 'Status Tidak Diketahui';
};
