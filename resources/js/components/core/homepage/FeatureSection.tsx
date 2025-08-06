import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Link } from '@inertiajs/react';
import {
    ArrowRight,
    FileText,
    Lightbulb,
    Map,
    Newspaper,
    ShoppingBag,
    Target,
} from 'lucide-react';

const FeatureSection = () => {
    const features = [
        {
            icon: <FileText className="h-8 w-8 text-green-600" />,
            title: 'Laporkan Isu Lingkungan',
            description:
                'Laporkan masalah lingkungan di sekitar Anda dengan mudah dan cepat',
            link: '/report',
        },
        {
            icon: <Target className="h-8 w-8 text-red-600" />,
            title: 'Bergabung dalam Misi',
            description:
                'Temukan Misi dan Jadilah bagian dari Gerakan peduli lingkungan Indonesia',
            link: '/mission',
        },
        {
            icon: <Map className="h-8 w-8 text-blue-600" />,
            title: 'Lihat Peta Laporan',
            description:
                'Jelajahi peta interaktif untuk melihat laporan di berbagai daerah',
            link: '/map',
        },
        {
            icon: <Newspaper className="h-8 w-8 text-amber-600" />,
            title: 'Konten Edukasi',
            description:
                'Akses berbagai edukasi tentang kepedulian terhadap lingkungan',
            link: '/education',
        },
        {
            icon: <Lightbulb className="h-8 w-8 text-purple-600" />,
            title: 'Kuis Edukasi',
            description:
                'Akses dan kerjakan berbagai kuis edukasi tentang  lingkungan',
            link: '/quiz',
        },
        {
            icon: <ShoppingBag className="h-8 w-8 text-sky-900" />,
            title: 'Merchandise',
            description:
                'Dapatkan merchandise resmi dari SobatBumi dan tukarkan poinmu',
            link: '/merchandise',
        },
    ];

    return (
        <section className="relative overflow-hidden bg-gradient-to-br from-gray-100 via-emerald-50/30 to-gray-50 py-20">
            <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="mb-16 text-center">
                    <div className="mb-6 inline-flex items-center rounded-full border border-emerald-200/50 bg-emerald-100/80 px-4 py-2 text-sm font-medium text-emerald-800 backdrop-blur-sm">
                        Bergabung Sekarang
                    </div>
                    <h2 className="mb-6 bg-gradient-to-r from-gray-900 via-emerald-800 to-gray-900 bg-clip-text text-4xl font-bold leading-tight text-gray-900 text-transparent md:text-5xl lg:text-6xl">
                        Mulai Berkontribusi{' '}
                        <span className="text-emerald-600">Hari Ini</span>
                    </h2>
                    <p className="mx-auto max-w-3xl text-xl leading-relaxed text-gray-600 md:text-xl">
                        Pilih cara Anda untuk berpartisipasi dalam
                        <span className="font-semibold text-emerald-600">
                            {' '}
                            menjaga kelestarian lingkungan
                        </span>
                    </p>
                    <div className="mb-4 mt-8 flex items-center justify-center">
                        <div className="h-px w-32 bg-gradient-to-r from-transparent via-emerald-300 to-transparent"></div>
                        <div className="mx-4 h-3 w-3 rounded-full bg-emerald-400 shadow-lg shadow-emerald-200"></div>
                        <div className="h-px w-32 bg-gradient-to-r from-transparent via-emerald-300 to-transparent"></div>
                    </div>
                </div>

                <div className="grid gap-8 md:grid-cols-3 lg:gap-12">
                    {features.map((feature, index) => (
                        <Card
                            key={index}
                            className="group relative overflow-hidden border-0 bg-white/70 shadow-xl backdrop-blur-sm transition-all duration-500 hover:-translate-y-2 hover:bg-white/90 hover:shadow-2xl"
                        >
                            <div className="absolute inset-0 bg-gradient-to-br from-emerald-50/50 via-transparent to-blue-50/30 opacity-0 transition-opacity duration-500 group-hover:opacity-100"></div>

                            <div className="absolute left-0 right-0 top-0 h-1 origin-left scale-x-0 transform bg-gradient-to-r from-emerald-400 via-emerald-500 to-emerald-600 transition-transform duration-500 group-hover:scale-x-100"></div>

                            <CardContent className="relative p-8 text-center">
                                <div className="mb-6 flex justify-center">
                                    <div className="relative rounded-2xl bg-gradient-to-br from-gray-100 to-gray-50 p-4 shadow-lg transition-all duration-500 group-hover:rotate-3 group-hover:scale-110 group-hover:shadow-xl">
                                        <div className="absolute inset-0 rounded-2xl bg-gradient-to-br from-emerald-200/20 to-emerald-300/20 blur-xl transition-all duration-500 group-hover:blur-2xl"></div>
                                        <div className="relative text-gray-600 transition-colors duration-300 group-hover:text-emerald-700">
                                            {feature.icon}
                                        </div>
                                    </div>
                                </div>

                                <h3 className="mb-4 text-2xl font-bold text-gray-900 transition-colors duration-300 group-hover:text-emerald-800">
                                    {feature.title}
                                </h3>
                                <p className="mb-8 text-lg leading-relaxed text-gray-600 transition-colors duration-300 group-hover:text-gray-700">
                                    {feature.description}
                                </p>

                                <Link href={feature.link}>
                                    <Button
                                        variant="outline"
                                        className="group/btn relative w-full overflow-hidden rounded-xl border-2 border-emerald-200 bg-transparent px-6 py-6 text-lg font-semibold text-emerald-700 shadow-lg transition-all duration-500 hover:border-emerald-500 hover:bg-emerald-500 hover:text-white hover:shadow-xl"
                                    >
                                        <span className="relative flex items-center justify-center">
                                            Mulai Sekarang
                                            <ArrowRight className="ml-2 h-5 w-5 transform transition-transform duration-300 group-hover/btn:translate-x-1" />
                                        </span>
                                    </Button>
                                </Link>
                            </CardContent>
                        </Card>
                    ))}
                </div>
            </div>
        </section>
    );
};

export default FeatureSection;
