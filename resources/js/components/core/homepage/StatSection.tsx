import { StatItem } from '@/types/homepage/statItem';
import StatCounter from './StatCounter';

const stats: StatItem[] = [
    { label: 'Laporan Aktif', value: '87', color: 'text-blue-600' },
    { label: 'Misi Selesai', value: '21', color: 'text-green-600' },
    { label: 'Volunteer Aktif', value: '45', color: 'text-purple-600' },
    { label: 'Komunitas', value: '17', color: 'text-orange-600' },
];

const StatSection = () => {
    return (
        <section className="relative overflow-hidden bg-gradient-to-br from-white via-emerald-50 to-emerald-100 py-20">
            <div className="absolute -top-32 left-1/4 h-96 w-96 rounded-full bg-green-200/70 blur-3xl"></div>
            <div className="absolute -bottom-40 right-1/4 h-96 w-96 rounded-full bg-emerald-200/30 blur-3xl"></div>
            <div className="absolute inset-0 bg-gradient-to-r from-blue-100/30 to-purple-50/30"></div>
            <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="grid grid-cols-2 gap-8 md:grid-cols-4">
                    {stats.map((stat, index) => (
                        <StatCounter key={index} stat={stat} index={index} />
                    ))}
                </div>
            </div>
        </section>
    );
};

export default StatSection;
