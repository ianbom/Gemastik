import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';

import { ArrowRight, Plus } from 'lucide-react';

const HeroSection = () => {
    return (
        <section className="relative overflow-hidden bg-gradient-to-r from-emerald-500 to-emerald-700 py-12 text-white">
            <div className="absolute inset-0 bg-black/10"></div>
            <div className="absolute inset-0 opacity-20">
                <div className="absolute left-10 top-10 h-32 w-32 animate-pulse rounded-full bg-white/10 blur-xl"></div>
                <div
                    className="absolute bottom-20 left-1/4 h-48 w-48 animate-bounce rounded-full bg-emerald-300/10 blur-2xl"
                    style={{ animationDuration: '3s' }}
                ></div>
                <div
                    className="absolute right-20 top-1/3 h-40 w-40 animate-pulse rounded-full bg-white/5 blur-xl"
                    style={{ animationDelay: '1s' }}
                ></div>
            </div>

            <div className="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
                <div className="grid items-center gap-12 lg:grid-cols-2">
                    <div className="text-center lg:text-left">
                        <h1 className="mb-6 text-4xl font-bold leading-tight tracking-tight md:text-6xl">
                            Bersama Menjaga
                            <span className="animate-fade-in-up relative block text-green-200">
                                Lingkungan Indonesia
                                <div className="absolute -bottom-2 left-0 h-1 w-full animate-pulse rounded bg-gradient-to-r from-green-200 via-green-300 to-transparent lg:w-3/4"></div>
                            </span>
                        </h1>
                        <p
                            className="animate-fade-in mx-auto mb-8 max-w-2xl text-xl leading-relaxed text-green-50/90 md:text-2xl lg:mx-0"
                            style={{ animationDelay: '0.3s' }}
                        >
                            Platform kolaboratif untuk melaporkan, mengatasi,
                            dan mencegah masalah lingkungan di seluruh Indonesia
                        </p>
                        <div
                            className="animate-fade-in-up flex flex-col justify-center gap-4 sm:flex-row lg:justify-start"
                            style={{ animationDelay: '0.6s' }}
                        >
                            <Link href="/reports/create">
                                <Button
                                    size="lg"
                                    className="group bg-teal-900 font-semibold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:bg-green-50 hover:shadow-xl active:scale-95"
                                >
                                    <Plus className="mr-2 h-5 w-5 transition-transform group-hover:rotate-90" />
                                    Laporkan Isu Sekarang
                                </Button>
                            </Link>
                            <Link href="/reports">
                                <Button
                                    size="lg"
                                    variant="outline"
                                    className="group border-2 border-white/80 font-semibold text-teal-600 shadow-lg transition-all duration-300 hover:scale-105 hover:border-white hover:bg-white hover:text-emerald-600 hover:shadow-xl active:scale-95"
                                >
                                    Lihat Semua Laporan
                                    <ArrowRight className="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" />
                                </Button>
                            </Link>
                        </div>
                    </div>
                    <div className="relative hidden lg:block">
                        <div
                            className="animate-fade-in-scale relative z-10"
                            style={{ animationDelay: '0.9s' }}
                        >
                            <div className="group relative rounded-2xl bg-white/20 p-6 shadow-2xl backdrop-blur-md transition-all duration-500 hover:scale-105 hover:bg-white/25">
                                <img
                                    src="/assets/images/hero.jpg"
                                    alt="Lingkungan Indonesia"
                                    className="h-80 w-full rounded-xl object-cover shadow-lg transition-transform duration-500 group-hover:scale-105"
                                />
                                <div
                                    className="absolute -right-6 -top-6 animate-bounce rounded-xl bg-gradient-to-r from-green-200 to-green-300 px-4 py-2 text-sm font-bold text-green-800 shadow-xl backdrop-blur-sm"
                                    style={{ animationDuration: '2s' }}
                                >
                                    🌱 Go Green
                                </div>
                                <div className="absolute -bottom-6 -left-6 animate-pulse rounded-xl bg-white/95 px-4 py-2 text-sm font-bold text-emerald-600 shadow-xl backdrop-blur-sm">
                                    ♻️ Sustainable
                                </div>

                                <div className="absolute -right-2 top-1/2 h-24 w-1 rounded-full bg-gradient-to-b from-green-200 to-transparent opacity-60"></div>
                            </div>
                        </div>
                        <div className="absolute inset-0 animate-pulse rounded-2xl bg-gradient-to-bl from-emerald-300/30 via-emerald-400/20 to-transparent blur-xl"></div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default HeroSection;
