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
            <div className="flex w-full max-w-3xl overflow-hidden transition-all duration-300 transform scale-100 bg-white shadow-2xl rounded-2xl">
                <div className="hidden bg-gray-200 md:block md:w-1/2">
                    <img
                        src={'/donasi.jpg'}
                        alt="Donation context"
                        className="object-cover w-full h-full"
                    />
                </div>
                <div className="flex flex-col w-full p-8 md:w-1/2">
                    <div className="flex-grow">
                        <div className="mb-6">
                            <h3 className="flex items-center text-2xl font-bold text-gray-800">
                                <Gift className="w-6 h-6 mr-3 text-sky-600" />
                                Jumlah Donasi
                            </h3>
                        </div>

                        {/* Input Jumlah Donasi */}
                        <div className="relative mb-4">
                            <span className="absolute font-medium text-gray-500 transform -translate-y-1/2 left-4 top-1/2">
                                Rp
                            </span>
                            <input
                                type="text"
                                value={formatInputCurrency(amount)}
                                onChange={(e) =>
                                    handleAmountChange(e.target.value)
                                }
                                className="w-full py-3 pl-12 pr-4 text-lg text-gray-800 transition-colors border border-gray-300 rounded-lg focus:border-sky-500 focus:ring-2 focus:ring-sky-500"
                                placeholder="0"
                                disabled={loading}
                            />
                        </div>
                        {error && (
                            <p className="mb-4 text-sm text-red-600">{error}</p>
                        )}

                        {/* Pilihan Cepat */}
                        <div className="mb-8">
                            <p className="flex items-center mb-3 text-sm font-medium text-gray-600">
                                <span className="mr-2 h-1.5 w-1.5 rounded-full bg-sky-500"></span>
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
                                        className={`rounded-lg border px-4 py-2 text-sm font-medium transition-all duration-200 ${parseInt(amount) === preAmount ? 'border-sky-600 bg-sky-600 text-white' : 'border-gray-300 bg-white hover:border-sky-400 hover:bg-sky-50'}`}
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
                            className="w-full h-auto py-3 text-base font-semibold rounded-lg bg-sky-600 hover:bg-sky-700"
                        >
                            {loading ? (
                                <div className="flex items-center">
                                    <div className="w-5 h-5 mr-3 border-2 border-white rounded-full animate-spin border-t-transparent" />
                                    <span>Memproses...</span>
                                </div>
                            ) : (
                                <>
                                    <Heart className="w-5 h-5 mr-2" />
                                    <span>Donasi Sekarang</span>
                                </>
                            )}
                        </Button>
                        <Button
                            type="button"
                            variant="outline"
                            onClick={onClose}
                            disabled={loading}
                            className="w-full h-auto py-3 font-medium border-gray-300 rounded-lg hover:bg-gray-100"
                        >
                            Batal
                        </Button>
                        <p className="pt-2 text-xs text-center text-gray-500">
                            <span className="mr-1.5 inline-block h-1.5 w-1.5 rounded-full bg-sky-500"></span>
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

            <Card className="bg-gradient-to-r from-sky-50 to-sky-50">
                <CardContent className="p-6">
                    <div className="text-center">
                        <Heart className="w-12 h-12 mx-auto mb-4 text-sky-600" />
                        <h3 className="mb-2 text-xl font-semibold">
                            Donasi untuk Penanganan
                        </h3>
                        <p className="mb-4 text-gray-600">
                            Bantu penanganan masalah ini dengan memberikan
                            donasi
                        </p>

                        {/* Donation Statistics */}
                        <div className="grid grid-cols-2 gap-4 mb-6">
                            <div className="p-4 rounded-lg bg-white/70">
                                <div className="flex items-center justify-center mb-2">
                                    <DollarSign className="w-5 h-5 mr-2 text-sky-600" />
                                    <span className="text-sm font-medium text-gray-600">
                                        Total Donasi
                                    </span>
                                </div>
                                <p className="text-lg font-bold text-sky-700">
                                    {formatCurrency(totalAmount)}
                                </p>
                            </div>
                            <div className="p-4 rounded-lg bg-white/70">
                                <div className="flex items-center justify-center mb-2">
                                    <Users className="w-5 h-5 mr-2 text-sky-600" />
                                    <span className="text-sm font-medium text-gray-600">
                                        Donatur
                                    </span>
                                </div>
                                <p className="text-lg font-bold text-sky-700">
                                    {totalDonors} Orang
                                </p>
                            </div>
                        </div>

                        {/* Donor List */}
                        {totalDonors > 0 && (
                            <div>
                                <button
                                    className="flex items-center justify-center w-full mb-4 text-sm transition-colors text-sky-700 hover:text-sky-800"
                                    onClick={() => setIsOpen(!isOpen)}
                                >
                                    <Users className="w-4 h-4 mr-2" />
                                    {isOpen ? 'Sembunyikan' : 'Lihat'} Daftar
                                    Donatur
                                    {isOpen ? (
                                        <ChevronUp className="w-4 h-4 ml-2" />
                                    ) : (
                                        <ChevronDown className="w-4 h-4 ml-2" />
                                    )}
                                </button>

                                {isOpen && (
                                    <div className="mb-6">
                                        <div className="overflow-y-auto max-h-64">
                                            <div className="space-y-3">
                                                {donations.map((donation) => (
                                                    <div
                                                        key={donation.id}
                                                        className="flex items-center justify-between p-3 text-left rounded-lg bg-white/70"
                                                    >
                                                        <div className="flex items-center space-x-3">
                                                            <div className="flex-shrink-0">
                                                                {donation.user
                                                                    .profile_url ? (
                                                                    <img
                                                                        className="object-cover w-8 h-8 rounded-full"
                                                                        src={`/storage/${donation.user.profile_url}`}
                                                                        alt={
                                                                            donation
                                                                                .user
                                                                                .name
                                                                        }
                                                                    />
                                                                ) : (
                                                                    <div className="flex items-center justify-center w-8 h-8 rounded-full bg-sky-100">
                                                                        <span className="text-xs font-medium text-sky-700">
                                                                            {getInitials(
                                                                                donation
                                                                                    .user
                                                                                    .name,
                                                                            )}
                                                                        </span>
                                                                    </div>
                                                                )}
                                                            </div>
                                                            <div className="flex-1 min-w-0">
                                                                <p className="text-sm font-medium text-gray-900 truncate">
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
                                                            <p className="text-sm font-semibold text-sky-700">
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
                            className="w-full bg-sky-600 hover:bg-sky-700"
                            onClick={() => setModalOpen(true)}
                        >
                            <Heart className="w-4 h-4 mr-2" />
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
