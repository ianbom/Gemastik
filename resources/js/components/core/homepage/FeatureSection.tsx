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
            icon: <FileText className="w-8 h-8 text-sky-600" />,
            title: 'Laporkan Isu Lingkungan',
            description:
                'Laporkan masalah lingkungan di sekitar Anda dengan mudah dan cepat',
            link: '/report',
        },
        {
            icon: <Target className="w-8 h-8 text-red-600" />,
            title: 'Bergabung dalam Misi',
            description:
                'Temukan Misi dan Jadilah bagian dari Gerakan peduli lingkungan Indonesia',
            link: '/mission',
        },
        {
            icon: <Map className="w-8 h-8 text-blue-600" />,
            title: 'Lihat Peta Laporan',
            description:
                'Jelajahi peta interaktif untuk melihat laporan di berbagai daerah',
            link: '/map',
        },
        {
            icon: <Newspaper className="w-8 h-8 text-cyan-600" />,
            title: 'Konten Edukasi',
            description:
                'Akses berbagai edukasi tentang kepedulian terhadap lingkungan',
            link: '/education',
        },
        {
            icon: <Lightbulb className="w-8 h-8 text-yellow-600" />,
            title: 'Kuis Edukasi',
            description:
                'Akses dan kerjakan berbagai kuis edukasi tentang  lingkungan',
            link: '/quiz',
        },
        {
            icon: <ShoppingBag className="w-8 h-8 text-indigo-500" />,
            title: 'Merchandise',
            description:
                'Dapatkan merchandise resmi dari KawanBumi dan tukarkan poinmu',
            link: '/merchandise',
        },
    ];

    return (
        <section className="relative py-20 overflow-hidden bg-gradient-to-br from-gray-100 via-sky-50/30 to-gray-50">
            <div className="relative z-10 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div className="mb-16 text-center">
                    <div className="inline-flex items-center px-4 py-2 mb-6 text-sm font-medium border rounded-full border-sky-200/50 bg-sky-100/80 text-sky-800 backdrop-blur-sm">
                        Bergabung Sekarang
                    </div>
                    <h2 className="mb-6 text-4xl font-bold leading-tight text-transparent text-gray-900 bg-gradient-to-r from-gray-900 via-sky-800 to-gray-900 bg-clip-text md:text-5xl lg:text-6xl">
                        Mulai Berkontribusi{' '}
                        <span className="text-sky-600">Hari Ini</span>
                    </h2>
                    <p className="max-w-3xl mx-auto text-xl leading-relaxed text-gray-600 md:text-xl">
                        Pilih cara Anda untuk berpartisipasi dalam
                        <span className="font-semibold text-sky-600">
                            {' '}
                            menjaga kelestarian lingkungan
                        </span>
                    </p>
                    <div className="flex items-center justify-center mt-8 mb-4">
                        <div className="w-32 h-px bg-gradient-to-r from-transparent via-sky-300 to-transparent"></div>
                        <div className="w-3 h-3 mx-4 rounded-full shadow-lg bg-sky-400 shadow-sky-200"></div>
                        <div className="w-32 h-px bg-gradient-to-r from-transparent via-sky-300 to-transparent"></div>
                    </div>
                </div>

                <div className="grid gap-8 md:grid-cols-3 lg:gap-12">
                    {features.map((feature, index) => (
                        <Card
                            key={index}
                            className="relative overflow-hidden transition-all duration-500 border-0 shadow-xl group bg-white/70 backdrop-blur-sm hover:-translate-y-2 hover:bg-white/90 hover:shadow-2xl"
                        >
                            <div className="absolute inset-0 transition-opacity duration-500 opacity-0 bg-gradient-to-br from-sky-50/50 via-transparent to-blue-50/30 group-hover:opacity-100"></div>

                            <div className="absolute top-0 left-0 right-0 h-1 transition-transform duration-500 origin-left transform scale-x-0 bg-gradient-to-r from-sky-400 via-sky-500 to-sky-600 group-hover:scale-x-100"></div>

                            <CardContent className="relative p-8 text-center">
                                <div className="flex justify-center mb-6">
                                    <div className="relative p-4 transition-all duration-500 shadow-lg rounded-2xl bg-gradient-to-br from-gray-100 to-gray-50 group-hover:rotate-3 group-hover:scale-110 group-hover:shadow-xl">
                                        <div className="absolute inset-0 transition-all duration-500 rounded-2xl bg-gradient-to-br from-sky-200/20 to-sky-300/20 blur-xl group-hover:blur-2xl"></div>
                                        <div className="relative text-gray-600 transition-colors duration-300 group-hover:text-sky-700">
                                            {feature.icon}
                                        </div>
                                    </div>
                                </div>

                                <h3 className="mb-4 text-2xl font-bold text-gray-900 transition-colors duration-300 group-hover:text-sky-800">
                                    {feature.title}
                                </h3>
                                <p className="mb-8 text-lg leading-relaxed text-gray-600 transition-colors duration-300 group-hover:text-gray-700">
                                    {feature.description}
                                </p>

                                <Link href={feature.link}>
                                    <Button
                                        variant="outline"
                                        className="relative w-full px-6 py-6 overflow-hidden text-lg font-semibold transition-all duration-500 bg-transparent border-2 shadow-lg group/btn rounded-xl border-sky-200 text-sky-700 hover:border-sky-500 hover:bg-sky-500 hover:text-white hover:shadow-xl"
                                    >
                                        <span className="relative flex items-center justify-center">
                                            Mulai Sekarang
                                            <ArrowRight className="w-5 h-5 ml-2 transition-transform duration-300 transform group-hover/btn:translate-x-1" />
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
