import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
const JoinSection = () => {
    return (
        <section
            className="relative bg-cover bg-center bg-no-repeat py-20"
            style={{ backgroundImage: "url('/assets/images/tanganbumi.png')" }}
        >
            {' '}
            <div className="absolute inset-0 z-0 bg-emerald-600/70" />
            <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="rounded-xl p-6 text-center">
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
                            className="bg-white text-green-600 hover:bg-green-700 hover:text-white"
                        >
                            Lihat Dashboard Saya
                            <ArrowRight className="ml-2 h-5 w-5" />
                        </Button>
                    </Link>
                </div>
            </div>
        </section>
    );
};

export default JoinSection;
