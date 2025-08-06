export const availableCategories = [
    { label: 'Sampah Plastik', value: 'sampah-plastik' },
    { label: 'Pencemaran Air', value: 'pencemaran-air' },
    { label: 'Pencemaran Udara', value: 'pencemaran-udara' },
    { label: 'Pencemaran Tanah', value: 'pencemaran-tanah' },
    { label: 'Limbah Industri', value: 'limbah-industri' },
    { label: 'Emisi Gas Rumah Kaca', value: 'emisi-gas-rumah-kaca' },
    {
        label: 'Penggundulan / Kebakaran Hutan',
        value: 'penggundulan-kebakaran-hutan',
    },
    {
        label: 'Naiknya Permukaan Air Laut',
        value: 'naiknya-permukaan-air-laut',
    },
    {
        label: 'Limbah Pertanian / Peternakan',
        value: 'limbah-pertanian-peternakan',
    },
    { label: 'Lainnya', value: 'lainnya' },
];

export function getCategoryLabel(value: string): string {
    return (
        availableCategories.find((cat) => cat.value === value)?.label || value
    );
}
