import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
const JoinSection = () => {
    return (
        <section
            className="relative py-20 bg-center bg-no-repeat bg-cover"
            style={{ backgroundImage: "url('/assets/images/tanganbumi2.jpg')" }}
        >
            {' '}
            <div className="absolute inset-0 z-0 bg-sky-600/70" />
            <div className="relative z-10 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div className="p-6 text-center rounded-xl">
                    <h2 className="mb-6 text-4xl font-bold text-white lg:text-5xl">
                        Bergabunglah dengan Gerakan Lingkungan
                    </h2>
                    <p className="mb-6 leading-relaxed text-emerald-100">
                        Ribuan warga Indonesia telah bergabung. Saatnya Anda
                        menjadi bagian dari solusi!
                    </p>
                    <Link href="/dashboard">
                        <Button
                            size="lg"
                            className="bg-white text-cyan-600 hover:bg-cyan-700 hover:text-white"
                        >
                            Lihat Dashboard Saya
                            <ArrowRight className="w-5 h-5 ml-2" />
                        </Button>
                    </Link>
                </div>
            </div>
        </section>
    );
};

export default JoinSection;
