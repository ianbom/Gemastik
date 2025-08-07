import { StatItem } from '@/types/homepage/statItem';
import StatCounter from './StatCounter';

const stats: StatItem[] = [
    { label: 'Laporan Aktif', value: '87', color: 'text-blue-600' },
    { label: 'Misi Selesai', value: '21', color: 'text-cyan-600' },
    { label: 'Volunteer Aktif', value: '45', color: 'text-purple-600' },
    { label: 'Komunitas', value: '17', color: 'text-indigo-600' },
];

const StatSection = () => {
    return (
        <section className="relative py-20 overflow-hidden bg-gradient-to-br from-white via-sky-50 to-sky-100">
            <div className="absolute rounded-full -top-32 left-1/4 h-96 w-96 bg-cyan-200/70 blur-3xl"></div>
            <div className="absolute rounded-full -bottom-40 right-1/4 h-96 w-96 bg-sky-200/30 blur-3xl"></div>
            <div className="absolute inset-0 bg-gradient-to-r from-blue-100/30 to-indigo-50/30"></div>
            <div className="relative z-10 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
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
