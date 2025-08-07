import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import { ArrowRight, Leaf, Plus } from 'lucide-react';

const HeroSection = () => {
    return (
        <section className="relative py-12 overflow-hidden text-white bg-gradient-to-r from-sky-500 to-sky-700">
            <div className="absolute inset-0 bg-black/10"></div>
            <div className="absolute inset-0 opacity-20">
                <div className="absolute w-32 h-32 rounded-full left-10 top-10 animate-pulse bg-white/10 blur-xl"></div>
                <div
                    className="absolute w-48 h-48 rounded-full bottom-20 left-1/4 animate-bounce bg-sky-300/10 blur-2xl"
                    style={{ animationDuration: '3s' }}
                ></div>
                <div
                    className="absolute w-40 h-40 rounded-full right-20 top-1/3 animate-pulse bg-white/5 blur-xl"
                    style={{ animationDelay: '1s' }}
                ></div>
            </div>

            <div className="relative px-4 py-20 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div className="grid items-center gap-12 lg:grid-cols-2">
                    <div className="text-center lg:text-left">
                        <div className="inline-flex items-center px-4 py-2 mb-6 text-sm font-medium rounded-full animate-fade-in bg-white/20 backdrop-blur-sm">
                            <Leaf className="w-4 h-4 mr-2 text-blue-200" />
                            Platform Lingkungan #1 di Indonesia
                        </div>

                        <h1 className="mb-6 text-4xl font-bold leading-tight tracking-tight md:text-6xl">
                            Bersama Menjaga
                            <span className="relative block animate-fade-in-up text-cyan-200">
                                Lingkungan Indonesia
                                <div className="absolute left-0 w-full h-1 rounded -bottom-2 animate-pulse bg-gradient-to-r from-cyan-200 via-cyan-300 to-transparent lg:w-3/4"></div>
                            </span>
                        </h1>
                        <p
                            className="max-w-2xl mx-auto mb-8 text-xl leading-relaxed animate-fade-in text-cyan-50/90 md:text-2xl lg:mx-0"
                            style={{ animationDelay: '0.3s' }}
                        >
                            Platform kolaboratif untuk melaporkan, mengatasi,
                            dan mencegah masalah lingkungan di seluruh Indonesia
                        </p>
                        <div
                            className="flex justify-center gap-8 mb-8 animate-fade-in lg:justify-start"
                            style={{ animationDelay: '0.4s' }}
                        >
                            <div className="text-center">
                                <div className="text-2xl font-bold text-blue-200">
                                    1,234
                                </div>
                                <div className="text-sm text-cyan-100/80">
                                    Laporan
                                </div>
                            </div>
                            <div className="text-center">
                                <div className="text-2xl font-bold text-blue-200">
                                    567
                                </div>
                                <div className="text-sm text-cyan-100/80">
                                    Diselesaikan
                                </div>
                            </div>
                            <div className="text-center">
                                <div className="text-2xl font-bold text-blue-200">
                                    89%
                                </div>
                                <div className="text-sm text-cyan-100/80">
                                    Success Rate
                                </div>
                            </div>
                        </div>
                        <div
                            className="flex flex-col justify-center gap-4 animate-fade-in-up sm:flex-row lg:justify-start"
                            style={{ animationDelay: '0.6s' }}
                        >
                            <Link href="/reports/create">
                                <Button
                                    size="lg"
                                    className="font-semibold text-white transition-all duration-300 bg-blue-900 shadow-lg group hover:scale-105 hover:bg-cyan-50 hover:text-blue-900 hover:shadow-xl active:scale-95"
                                >
                                    <Plus className="w-5 h-5 mr-2 transition-transform group-hover:rotate-90" />
                                    Laporkan Isu Sekarang
                                </Button>
                            </Link>
                            <Link href="/reports">
                                <Button
                                    size="lg"
                                    variant="outline"
                                    className="font-semibold text-blue-600 transition-all duration-300 border-2 shadow-lg group border-white/80 hover:scale-105 hover:border-white hover:bg-white hover:text-sky-600 hover:shadow-xl active:scale-95"
                                >
                                    Lihat Semua Laporan
                                    <ArrowRight className="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" />
                                </Button>
                            </Link>
                        </div>
                    </div>
                    <div className="relative hidden lg:block">
                        <div
                            className="relative z-10 animate-fade-in-scale"
                            style={{ animationDelay: '0.9s' }}
                        >
                            <div className="relative p-6 transition-all duration-500 shadow-2xl group rounded-2xl bg-white/20 backdrop-blur-md hover:scale-105 hover:bg-white/25">
                                <img
                                    src="/assets/images/hero2.jpg"
                                    alt="Lingkungan Indonesia"
                                    className="object-cover w-full transition-transform duration-500 shadow-lg h-80 rounded-xl group-hover:scale-105"
                                />
                                <div className="absolute w-1 h-24 rounded-full -right-2 top-1/2 bg-gradient-to-b from-cyan-200 to-transparent opacity-60"></div>
                            </div>
                        </div>
                        <div className="absolute inset-0 animate-pulse rounded-2xl bg-gradient-to-bl from-sky-300/30 via-sky-400/20 to-transparent blur-xl"></div>
                    </div>
                </div>
            </div>
        </section>
    );
};
export default HeroSection;
