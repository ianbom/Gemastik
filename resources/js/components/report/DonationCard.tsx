import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Head, router as Inertia, usePage } from '@inertiajs/react';
import {
    ChevronDown,
    ChevronUp,
    DollarSign,
    Gift,
    Heart,
    Users,
} from 'lucide-react';
import { useMemo, useState } from 'react';

// Declare Snap for TypeScript
declare global {
    interface Window {
        snap: {
            pay: (
                token: string,
                options?: {
                    onSuccess?: (result: any) => void;
                    onPending?: (result: any) => void;
                    onError?: (result: any) => void;
                    onClose?: () => void;
                },
            ) => void;
        };
    }
}

// --- Tipe Data (Sangat penting untuk TypeScript) ---
interface Donor {
    id: number;
    name: string;
    profile_url?: string | null;
}

interface Donation {
    id: number;
    amount: number;
    created_at: string;
    user: Donor;
}

interface DonationCardProps {
    donations: Donation[];
    reportId: number;
}

// --- Helper Functions ---
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const getInitials = (name: string = '') => {
    return name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
};

// Clean Modal Component sesuai design
const DonationModal = ({
    isOpen,
    onClose,
    onSubmit,
    loading,
}: {
    isOpen: boolean;
    onClose: () => void;
    onSubmit: (amount: number) => void;
    loading: boolean;
}) => {
    const [amount, setAmount] = useState('');
    const [error, setError] = useState('');

    const predefinedAmounts = [25000, 50000, 100000, 250000, 500000, 1000000];

    const handleSubmit = () => {
        const numAmount = parseInt(amount.replace(/\D/g, ''));

        if (!numAmount || numAmount < 10000) {
            setError('Minimal donasi adalah Rp 10.000');
            return;
        }

        setError('');
        onSubmit(numAmount);
    };

    const handleAmountChange = (value: string) => {
        const cleanValue = value.replace(/\D/g, '');
        setAmount(cleanValue);
        setError('');
    };

    const formatInputCurrency = (value: string) => {
        if (!value) return '';
        const number = parseInt(value.replace(/\D/g, ''));
        return number.toLocaleString('id-ID');
    };

    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div className="flex w-full max-w-3xl scale-100 transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300">
                {/* Bagian Kiri: Gambar Laporan */}
                <div className="hidden bg-gray-200 md:block md:w-1/2">
                    <img
                        src={'/donasi.jpg'} // Gunakan gambar dari prop atau fallback
                        alt="Donation context"
                        className="h-full w-full object-cover"
                    />
                </div>

                {/* Bagian Kanan: Form Donasi */}
                <div className="flex w-full flex-col p-8 md:w-1/2">
                    <div className="flex-grow">
                        <div className="mb-6">
                            <h3 className="flex items-center text-2xl font-bold text-gray-800">
                                <Gift className="mr-3 h-6 w-6 text-emerald-600" />
                                Jumlah Donasi
                            </h3>
                        </div>

                        {/* Input Jumlah Donasi */}
                        <div className="relative mb-4">
                            <span className="absolute left-4 top-1/2 -translate-y-1/2 transform font-medium text-gray-500">
                                Rp
                            </span>
                            <input
                                type="text"
                                value={formatInputCurrency(amount)}
                                onChange={(e) =>
                                    handleAmountChange(e.target.value)
                                }
                                className="w-full rounded-lg border border-gray-300 py-3 pl-12 pr-4 text-lg text-gray-800 transition-colors focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                                placeholder="0"
                                disabled={loading}
                            />
                        </div>
                        {error && (
                            <p className="mb-4 text-sm text-red-600">{error}</p>
                        )}

                        {/* Pilihan Cepat */}
                        <div className="mb-8">
                            <p className="mb-3 flex items-center text-sm font-medium text-gray-600">
                                <span className="mr-2 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Pilihan Cepat:
                            </p>
                            <div className="grid grid-cols-2 gap-3">
                                {predefinedAmounts.map((preAmount) => (
                                    <button
                                        key={preAmount}
                                        type="button"
                                        onClick={() => {
                                            setAmount(preAmount.toString());
                                            setError('');
                                        }}
                                        className={`rounded-lg border px-4 py-2 text-sm font-medium transition-all duration-200 ${parseInt(amount) === preAmount ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-gray-300 bg-white hover:border-emerald-400 hover:bg-emerald-50'}`}
                                        disabled={loading}
                                    >
                                        {formatCurrency(preAmount).replace(
                                            /\s*Rp/g,
                                            '',
                                        )}
                                    </button>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Tombol Aksi di Bawah */}
                    <div className="mt-auto space-y-3">
                        <Button
                            onClick={handleSubmit}
                            disabled={loading || !amount}
                            className="h-auto w-full rounded-lg bg-emerald-600 py-3 text-base font-semibold hover:bg-emerald-700"
                        >
                            {loading ? (
                                <div className="flex items-center">
                                    <div className="mr-3 h-5 w-5 animate-spin rounded-full border-2 border-white border-t-transparent" />
                                    <span>Memproses...</span>
                                </div>
                            ) : (
                                <>
                                    <Heart className="mr-2 h-5 w-5" />
                                    <span>Donasi Sekarang</span>
                                </>
                            )}
                        </Button>
                        <Button
                            type="button"
                            variant="outline"
                            onClick={onClose}
                            disabled={loading}
                            className="h-auto w-full rounded-lg border-gray-300 py-3 font-medium hover:bg-gray-100"
                        >
                            Batal
                        </Button>
                        <p className="pt-2 text-center text-xs text-gray-500">
                            <span className="mr-1.5 inline-block h-1.5 w-1.5 rounded-full bg-green-500"></span>
                            Donasi Anda akan langsung disalurkan untuk
                            penanganan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
};

export function DonationCard({ donations, reportId }: DonationCardProps) {
    const [isOpen, setIsOpen] = useState(false);
    const [modalOpen, setModalOpen] = useState(false);
    const [loading, setLoading] = useState(false);

    // Get page props untuk konfigurasi Midtrans
    const { props } = usePage();
    const { midtrans_client_key, midtrans_is_production } = props as any;

    // --- Kalkulasi Data dari Props menggunakan useMemo untuk efisiensi ---
    const { totalAmount, totalDonors } = useMemo(() => {
        const total = donations.reduce(
            (sum, donation) => sum + donation.amount,
            0,
        );
        return {
            totalAmount: total,
            totalDonors: donations.length,
        };
    }, [donations]);
    const getCsrfToken = (): string => {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        return tokenMeta ? tokenMeta.getAttribute('content') || '' : '';
    };

    const getRouteUrl = (routeName: string, params?: any): string => {
        switch (routeName) {
            case 'report.donate':
                return `/donate/report/${params}`;
            case 'payment.success':
                return `/api/payment-success/${params}`;
            default:
                return '';
        }
    };

    const handleSuccessPayment = async (
        donationId: number,
        paymentResult?: any,
    ) => {
        console.log(`Mengirim konfirmasi untuk ID Donasi: ${donationId}`);
        console.log('Payment result data:', paymentResult);

        try {
            // Menggunakan custom route helper dengan fallback
            const url =
                typeof route !== 'undefined'
                    ? route('payment.success', donationId)
                    : getRouteUrl('payment.success', donationId);

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                body: JSON.stringify({
                    donation_id: donationId,
                    payment_data: paymentResult || {},
                    status: 'success',
                }),
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error(
                    'Payment confirmation error response:',
                    errorText,
                );

                let errorData;
                try {
                    errorData = JSON.parse(errorText);
                } catch (e) {
                    errorData = {
                        message: `Server error: ${response.status} ${response.statusText}`,
                    };
                }

                throw new Error(
                    errorData.message ||
                        'Gagal mengkonfirmasi pembayaran di server.',
                );
            }

            const result = await response.json();
            console.log(
                'Konfirmasi pembayaran berhasil dikirim ke server:',
                result,
            );
            return result;
        } catch (error) {
            console.error('Gagal mengirim konfirmasi pembayaran:', error);
            // Re-throw error agar bisa di-handle di onSuccess
            throw error;
        }
    };

    const handleDonateSubmit = async (amount: number) => {
        setLoading(true);
        try {
            // Request ke backend untuk mendapatkan snap token
            const response = await fetch(route('donate.report', reportId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN':
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute('content') || '',
                    Accept: 'application/json',
                },
                body: JSON.stringify({
                    amount: amount,
                    report_id: reportId,
                }),
            });

            const data = await response.json();

            if (response.ok && data) {
                // PERBAIKAN: Simpan donation_id dalam variable yang bisa diakses
                const donationId = data.donation_id;
                const snapToken = data.snap_token || data; // Sesuaikan dengan response backend Anda

                setModalOpen(false);
                setLoading(false);

                // Buka Midtrans Snap
                window.snap.pay(snapToken, {
                    onSuccess: async function (result: any) {
                        console.log('Payment success:', result);
                        console.log('Donation ID:', donationId);

                        try {
                            // PERBAIKAN: Panggil dengan donationId yang benar
                            await handleSuccessPayment(donationId, result);
                            console.log(
                                'handleSuccessPayment completed successfully',
                            );

                            // Reload data donations
                            window.location.reload();
                            // Inertia.reload({ only: ['donations'], preserveScroll: true });
                        } catch (error) {
                            console.error(
                                'Error in handleSuccessPayment:',
                                error,
                            );
                            // Tetap reload meskipun ada error di handleSuccessPayment
                            window.location.reload();
                        }
                    },
                    onPending: function (result: any) {
                        console.log('Payment pending:', result);
                        // Untuk pending, mungkin tidak perlu panggil handleSuccessPayment
                        window.location.reload();
                    },
                    onError: function (result: any) {
                        console.log('Payment error:', result);
                        alert('Pembayaran gagal. Halaman akan dimuat ulang.');
                        Inertia.reload();
                    },
                    onClose: function () {
                        console.log('Payment popup closed');
                        // User closed the popup without completing payment
                    },
                });
            } else {
                throw new Error(data.message || 'Gagal memproses donasi');
            }
        } catch (error) {
            setLoading(false);
            console.error('Error submitting donation:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    };

    return (
        <>
            <Head>
                <script
                    src={
                        midtrans_is_production
                            ? 'https://app.midtrans.com/snap/snap.js'
                            : 'https://app.sandbox.midtrans.com/snap/snap.js'
                    }
                    data-client-key={midtrans_client_key}
                />
            </Head>

            <Card className="bg-gradient-to-r from-emerald-50 to-green-50">
                <CardContent className="p-6">
                    <div className="text-center">
                        <Heart className="mx-auto mb-4 h-12 w-12 text-emerald-600" />
                        <h3 className="mb-2 text-xl font-semibold">
                            Donasi untuk Penanganan
                        </h3>
                        <p className="mb-4 text-gray-600">
                            Bantu penanganan masalah ini dengan memberikan
                            donasi
                        </p>

                        {/* Donation Statistics */}
                        <div className="mb-6 grid grid-cols-2 gap-4">
                            <div className="rounded-lg bg-white/70 p-4">
                                <div className="mb-2 flex items-center justify-center">
                                    <DollarSign className="mr-2 h-5 w-5 text-emerald-600" />
                                    <span className="text-sm font-medium text-gray-600">
                                        Total Donasi
                                    </span>
                                </div>
                                <p className="text-lg font-bold text-emerald-700">
                                    {formatCurrency(totalAmount)}
                                </p>
                            </div>
                            <div className="rounded-lg bg-white/70 p-4">
                                <div className="mb-2 flex items-center justify-center">
                                    <Users className="mr-2 h-5 w-5 text-emerald-600" />
                                    <span className="text-sm font-medium text-gray-600">
                                        Donatur
                                    </span>
                                </div>
                                <p className="text-lg font-bold text-emerald-700">
                                    {totalDonors} Orang
                                </p>
                            </div>
                        </div>

                        {/* Donor List */}
                        {totalDonors > 0 && (
                            <div>
                                <button
                                    className="mb-4 flex w-full items-center justify-center text-sm text-emerald-700 transition-colors hover:text-emerald-800"
                                    onClick={() => setIsOpen(!isOpen)}
                                >
                                    <Users className="mr-2 h-4 w-4" />
                                    {isOpen ? 'Sembunyikan' : 'Lihat'} Daftar
                                    Donatur
                                    {isOpen ? (
                                        <ChevronUp className="ml-2 h-4 w-4" />
                                    ) : (
                                        <ChevronDown className="ml-2 h-4 w-4" />
                                    )}
                                </button>

                                {isOpen && (
                                    <div className="mb-6">
                                        <div className="max-h-64 overflow-y-auto">
                                            <div className="space-y-3">
                                                {donations.map((donation) => (
                                                    <div
                                                        key={donation.id}
                                                        className="flex items-center justify-between rounded-lg bg-white/70 p-3 text-left"
                                                    >
                                                        <div className="flex items-center space-x-3">
                                                            <div className="flex-shrink-0">
                                                                {donation.user
                                                                    .profile_url ? (
                                                                    <img
                                                                        className="h-8 w-8 rounded-full object-cover"
                                                                        src={`/storage/${donation.user.profile_url}`}
                                                                        alt={
                                                                            donation
                                                                                .user
                                                                                .name
                                                                        }
                                                                    />
                                                                ) : (
                                                                    <div className="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100">
                                                                        <span className="text-xs font-medium text-emerald-700">
                                                                            {getInitials(
                                                                                donation
                                                                                    .user
                                                                                    .name,
                                                                            )}
                                                                        </span>
                                                                    </div>
                                                                )}
                                                            </div>
                                                            <div className="min-w-0 flex-1">
                                                                <p className="truncate text-sm font-medium text-gray-900">
                                                                    {
                                                                        donation
                                                                            .user
                                                                            .name
                                                                    }
                                                                </p>
                                                                <p className="text-xs text-gray-500">
                                                                    {formatDate(
                                                                        donation.created_at,
                                                                    )}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div className="text-right">
                                                            <p className="text-sm font-semibold text-emerald-700">
                                                                {formatCurrency(
                                                                    donation.amount,
                                                                )}
                                                            </p>
                                                        </div>
                                                    </div>
                                                ))}
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </div>
                        )}

                        <Button
                            size="lg"
                            className="w-full bg-emerald-600 hover:bg-emerald-700"
                            onClick={() => setModalOpen(true)}
                        >
                            <Heart className="mr-2 h-4 w-4" />
                            Donasi Sekarang
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <DonationModal
                isOpen={modalOpen}
                onClose={() => setModalOpen(false)}
                onSubmit={handleDonateSubmit}
                loading={loading}
            />
        </>
    );
}
