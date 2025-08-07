import { Instagram } from 'lucide-react';
const Footer = () => {
    return (
        <footer className="relative overflow-hidden text-white bg-blue-900">
            <div className="absolute inset-0 bg-sky-600"></div>
            <div className="relative z-10 px-4 py-16 mx-auto max-w-7xl">
                <div className="grid grid-cols-1 gap-8 mb-12 md:grid-cols-2 lg:grid-cols-4">
                    <div className="lg:col-span-2">
                        <div className="flex items-start gap-4 mb-6">
                            <img
                                src="/assets/images/sobatbumibnw.png"
                                alt="KawanBumi"
                                className="object-contain w-12 h-12"
                            />
                            <div>
                                <h3 className="text-2xl font-bold text-white">
                                    KawanBumi
                                </h3>
                                <p className="text-sm text-slate-200">
                                    Aksi nyata untuk bumi, mulai dari laporan
                                    hingga misi sosial.
                                </p>
                            </div>
                        </div>

                        <p className="max-w-md mb-6 leading-relaxed text-justify text-slate-50">
                            KawanBumi adalah platform digital untuk
                            berpartisipasi dalam misi sosial dan lingkungan.
                            Kamu bisa bergabung dalam kegiatan, mengumpulkan
                            poin, mengasah wawasan lewat konten edukasi dan
                            kuis, serta menukar poin dengan merchandise menarik.
                        </p>
                        <div className="flex gap-4">
                            <a
                                href="#"
                                className="flex items-center justify-center w-10 h-10 transition-colors rounded-lg bg-cyan-500 hover:bg-cyan-800"
                            >
                                <Instagram className="w-5 h-5" />
                            </a>
                            <a
                                href="#"
                                className="flex items-center justify-center w-10 h-10 transition-colors rounded-lg bg-cyan-500 hover:bg-cyan-800"
                            >
                                <svg
                                    className="w-5 h-5"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div>
                        <h4 className="mb-6 text-lg font-semibold">Navigasi</h4>
                        <ul className="space-y-3">
                            <li>
                                <a
                                    href="/report"
                                    className="transition-colors text-slate-300 hover:text-white"
                                >
                                    Laporan
                                </a>
                            </li>
                            <li>
                                <a
                                    href="/mission"
                                    className="transition-colors text-slate-300 hover:text-white"
                                >
                                    Misi
                                </a>
                            </li>
                            <li>
                                <a
                                    href="/map"
                                    className="transition-colors text-slate-300 hover:text-white"
                                >
                                    Peta
                                </a>
                            </li>
                            <li>
                                <a
                                    href="/education"
                                    className="transition-colors text-slate-300 hover:text-white"
                                >
                                    Konten Edukasi
                                </a>
                            </li>
                            <li>
                                <a
                                    href="/quiz"
                                    className="transition-colors text-slate-300 hover:text-white"
                                >
                                    Quiz
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 className="mb-6 text-lg font-semibold">Layanan</h4>
                        <ul className="space-y-3">
                            <li>
                                <p className="transition-colors text-slate-300 hover:text-white">
                                    Pengajuan Laporan
                                </p>
                            </li>
                            <li>
                                <p className="transition-colors text-slate-300 hover:text-white">
                                    Verifikasi dan Tindak Lanjut Laporan
                                </p>
                            </li>
                            <li>
                                <p className="transition-colors text-slate-300 hover:text-white">
                                    Aksi Peduli Lingkungan
                                </p>
                            </li>
                            <li>
                                <p className="transition-colors text-slate-300 hover:text-white">
                                    Penukaran Merchandise
                                </p>
                            </li>
                            <li>
                                <p className="transition-colors text-slate-300 hover:text-white">
                                    Edukasi Berupa Kuis dan Konten
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div className="pt-8 border-t border-sky-900">
                    <div className="flex flex-col items-center justify-between gap-4 lg:flex-row">
                        <div className="flex flex-col items-center gap-4 text-sm sm:flex-row">
                            <p>&copy; 2025 KawanBumi. All rights reserved.</p>
                            <div className="flex items-center gap-4">
                                <a
                                    href="#"
                                    className="transition-colors hover:text-white"
                                >
                                    Privacy Policy
                                </a>
                                <a
                                    href="#"
                                    className="transition-colors hover:text-white"
                                >
                                    Terms of Service
                                </a>
                                <a
                                    href="#"
                                    className="transition-colors hover:text-white"
                                >
                                    Cookie Policy
                                </a>
                            </div>
                        </div>
                        <div className="flex items-center gap-4 text-sm">
                            <span>Made with</span>
                            <svg
                                className="w-4 h-4 text-red-500"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                            <span>by Tim Kebut Semalam, Surabaya</span>
                        </div>
                    </div>
                </div>
            </div>
            <div className="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
            <div className="absolute top-0 right-0 rounded-full h-96 w-96 bg-blue-500/5 blur-3xl"></div>
            <div className="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-indigo-500/5 blur-2xl"></div>
        </footer>
    );
};
export default Footer;
