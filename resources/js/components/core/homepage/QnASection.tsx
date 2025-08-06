import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import { Headset, Mail } from 'lucide-react';
import { useState } from 'react';

('use client');

const faqs = [
    {
        question: 'Apa itu SobatBumi?',
        answer: 'SobatBumi adalah platform digital untuk berpartisipasi dalam misi sosial dan lingkungan. Kamu bisa bergabung dalam kegiatan, mengumpulkan poin, mengasah wawasan lewat konten edukasi dan kuis, serta menukar poin dengan merchandise menarik.',
    },
    {
        question: 'Apa saja yang bisa saya lakukan di Sobat Bumi?',
        answer: 'Di SobatBumi, kamu bisa berpartisipasi dalam kegiatan atau misi seperti penanaman pohon, bersih pantai, dan aksi sosial lainnya. Kamu juga bisa membaca berbagai konten edukasi tentang lingkungan, menjawab kuis untuk mengasah pengetahuan, serta mengumpulkan poin dari berbagai aktivitas yang kamu ikuti. Poin tersebut nantinya bisa ditukar dengan merchandise eksklusif. Selain itu, kamu juga bisa berdonasi untuk mendukung program-program sosial yang dijalankan oleh komunitas.',
    },
    {
        question: 'Siapa saja yang bisa bergabung dalam aksi lingkungan?',
        answer: 'Semua orang bisa bergabung, baik pelajar, mahasiswa, relawan komunitas, hingga masyarakat umum yang peduli lingkungan.',
    },
    {
        question: 'Bagaimana sistem poin dan penukaran hadiah bekerja?',
        answer: 'Setiap aktivitas yang kamu lakukan di SobatBumi akan menghasilkan poin, mulai dari laporan terverifikasi, menyelesaikan misi, dan mengerjakan kuis. Poin ini bisa kamu akumulasikan dan tukarkan dengan hadiah berupa merchandise resmi. Sistem ini kami bangun untuk memberikan semangat dan motivasi dalam berbuat kebaikan.',
    },
    {
        question:
            'Apakah saya akan mendapatkan penghargaan setelah mengikuti aktivitas?',
        answer: 'Ya, setiap partisipasi kamu di Sobat Bumi tidak hanya berdampak bagi lingkungan dan masyarakat, tapi juga memberikan apresiasi pribadi. Setelah menyelesaikan misi atau kontribusi tertentu, kamu akan mendapatkan sertifikat digital sebagai bukti keikutsertaan, yang bisa digunakan untuk portofolio atau kebutuhan profesional lainnya. Selain itu, kamu juga akan memperoleh badges digital yang menunjukkan pencapaianmu, seperti "Pahlawan Lingkungan", "Donatur Aktif", atau "Pembelajar Hijau". Semua penghargaan ini akan tampil di profil kamu sebagai bentuk pengakuan dan motivasi agar terus aktif berkontribusi.',
    },
    {
        question: 'Apa tujuan fitur donasi di SobatBumi?',
        answer: 'Fitur donasi dibuat sebagai bentuk solidaritas dan dukungan terhadap aksi-aksi sosial yang membutuhkan bantuan nyata. Dengan berdonasi, kamu bisa ikut membantu korban bencana, mendukung gerakan penghijauan, atau membantu komunitas yang sedang membutuhkan. Donasi bisa dilakukan dengan nominal kecil sekalipun, karena setiap kontribusi berarti besar bagi mereka yang membutuhkan.',
    },
];

const QnASection = () => {
    const [openIndex, setOpenIndex] = useState<number | null>(null);

    const toggleFAQ = (index: number) => {
        setOpenIndex(openIndex === index ? null : index);
    };

    return (
        <section className="relative overflow-hidden bg-gradient-to-br from-green-200 via-emerald-50/30 to-gray-50 py-20">
            {/* <div className="container relative px-10 mx-auto lg:px-10"> */}
            {/* <div className="relative z-10 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8"> */}
            <div className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="mb-16 text-center">
                    <div className="mb-6 inline-flex items-center rounded-full border border-emerald-200/50 bg-emerald-100/80 px-4 py-2 text-sm font-medium text-emerald-800 backdrop-blur-sm">
                        Butuh Bantuan ❓
                    </div>
                    <h2 className="mb-6 text-4xl font-bold text-gray-900 lg:text-5xl">
                        <span className="text-gradient">Pertanyaan</span> yang
                        Sering Diajukan
                    </h2>
                    <p className="mx-auto max-w-3xl text-lg leading-relaxed text-gray-600">
                        Temukan jawaban atas pertanyaan umum tentang layanan
                        kami. Jika tidak menemukan jawaban yang dicari, silakan
                        hubungi tim support kami.
                    </p>
                </div>

                <div className="mx-auto w-full space-y-6">
                    {faqs.map((faq, index) => (
                        <div
                            key={index}
                            className="faq-item floating overflow-hidden rounded-2xl bg-white shadow-lg"
                        >
                            <button
                                className="w-full px-8 py-6 text-left focus:outline-none"
                                onClick={() => toggleFAQ(index)}
                            >
                                <div className="flex items-center justify-between">
                                    <h3 className="pr-4 text-xl font-bold text-gray-900">
                                        {faq.question}
                                    </h3>
                                    <svg
                                        className={`faq-toggle h-6 w-6 flex-shrink-0 text-black transition-transform duration-300 ${
                                            openIndex === index
                                                ? 'rotate-180'
                                                : ''
                                        }`}
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M19 9l-7 7-7-7"
                                        />
                                    </svg>
                                </div>
                            </button>
                            {openIndex === index && (
                                <div className="faq-content bg-amber-50 px-8 py-6 text-justify text-lg leading-relaxed text-gray-600">
                                    <p>{faq.answer}</p>
                                </div>
                            )}
                        </div>
                    ))}
                </div>

                <div className="mt-16 text-center">
                    <div className="rounded-2xl bg-emerald-600 p-8 py-16 text-white shadow-lg">
                        <h3 className="mb-4 text-3xl font-bold text-white">
                            Masih Ada Pertanyaan?
                        </h3>
                        <p className="mb-6 leading-relaxed text-white">
                            Tim customer service kami siap membantu Anda 24/7
                        </p>
                        <div className="mt-6 flex flex-wrap justify-center gap-4">
                            <Link href="/dashboard">
                                <Button
                                    size="lg"
                                    className="bg-white text-green-600 hover:bg-green-50"
                                >
                                    Wa Admin
                                    <Headset className="ml-1 h-5 w-5" />
                                </Button>
                            </Link>
                            <Link href="/laporan">
                                <Button
                                    size="lg"
                                    className="bg-amber-500 text-white hover:bg-yellow-700"
                                >
                                    Email Kami
                                    <Mail className="ml-2 h-5 w-5" />
                                </Button>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default QnASection;
