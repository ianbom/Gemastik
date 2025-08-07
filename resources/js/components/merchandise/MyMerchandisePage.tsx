'use client';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Reedem } from '@/types/reedem/interface';
import { User } from '@/types/user/interface';
import { getStatusClass } from '@/utils/badgeStatusDonationColor';
import { formatFullDateTime } from '@/utils/formatDate';
import { getReedemStatusLabel } from '@/utils/reedemStatusLabel';
import {
    ChevronDown,
    ChevronUp,
    RefreshCcw,
    ShoppingBag,
    SlidersHorizontal,
    Star,
} from 'lucide-react';
import { useState } from 'react';
interface MyMerchandisePageProps {
    reedems: Reedem[];
    auth: {
        user: User;
    };
}

const MyMerchandisePage = ({ reedems, auth }: MyMerchandisePageProps) => {
    // const [sortBy, setSortBy] = useState('newest');
    const [searchQuery, setSearchQuery] = useState('');
    const [statusFilter, setStatusFilter] = useState('all');
    const [nominalFilter, setNominalFilter] = useState('all');
    const [expandedItems, setExpandedItems] = useState(new Set());

    const filteredReedems = reedems.filter((reedem) => {
        const matchesStatus =
            statusFilter === 'all' ? true : reedem.status === statusFilter;
        const matchesNominal =
            nominalFilter === 'all'
                ? true
                : (nominalFilter === 'below500' && reedem.points_spent < 500) ||
                  (nominalFilter === '500kto1000' &&
                      reedem.points_spent >= 500 &&
                      reedem.points_spent <= 1000) ||
                  (nominalFilter === 'above1000' && reedem.points_spent > 1000);
        const matchesSearch = (
            (reedem.merchandise?.name || '') +
            (reedem.merchandise?.description || '')
        )
            .toLowerCase()
            .includes(searchQuery.toLowerCase());

        return matchesStatus && matchesNominal && matchesSearch;
    });

    const resetFilters = () => {
        setSearchQuery('');
        setStatusFilter('all');
        setNominalFilter('all');
    };

    const toggleDetail = (id: number) => {
        const newExpanded = new Set(expandedItems);
        if (newExpanded.has(id)) {
            newExpanded.delete(id);
        } else {
            newExpanded.add(id);
        }
        setExpandedItems(newExpanded);
    };
    const hasActiveFilters =
        searchQuery.trim() || statusFilter !== 'all' || nominalFilter !== 'all';

    return (
        <div className="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div className="flex flex-col justify-between gap-4 mb-8 sm:flex-row sm:items-center">
                <div>
                    <h1 className="mb-1 text-2xl font-bold text-gray-900 sm:text-3xl">
                        Riwayat Penukaran Merchandise
                    </h1>
                    <p className="text-gray-600">
                        Berikut adalah riwayat penukaran merchandise Anda
                    </p>
                </div>
                <div className="flex items-center gap-3">
                    <div className="inline-flex items-center gap-1.5 rounded-md bg-yellow-100 px-3 py-1 text-sm font-semibold text-yellow-800">
                        <svg
                            className="w-4 h-4 text-yellow-500"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path d="M10 15l-5.878 3.09 1.122-6.545L.488 6.91l6.561-.955L10 0l2.951 5.955 6.561.955-4.756 4.635 1.122 6.545z" />
                        </svg>
                        Poin Saya:
                        {auth.user?.points_balance || 0} Poin
                    </div>
                    <button
                        className="px-4 py-2 text-sm font-medium text-white transition rounded-md bg-sky-600 hover:bg-sky-700"
                        onClick={() => (window.location.href = '/merchandise')}
                    >
                        Tukar Merchandise
                    </button>
                </div>
            </div>

            <Card className="mb-6">
                <CardHeader>
                    <div className="flex items-center justify-between">
                        <CardTitle className="flex items-center justify-between gap-4 text-lg">
                            <div className="flex items-center">
                                <SlidersHorizontal
                                    size={20}
                                    className="mr-2 text-sky-600"
                                />
                                Filter Penukaran Merchandise
                            </div>
                            {hasActiveFilters && (
                                <span className="px-2 py-1 text-xs rounded-full bg-emerald-100 text-emerald-600">
                                    Aktif
                                </span>
                            )}
                        </CardTitle>
                        <Button
                            variant="outline"
                            size="sm"
                            onClick={resetFilters}
                            disabled={!hasActiveFilters}
                        >
                            <RefreshCcw className="w-4 h-4 mr-2" />
                            Reset Filter
                        </Button>
                    </div>
                </CardHeader>
                <CardContent>
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div className="space-y-2">
                            <label className="text-sm font-medium">
                                Status Penukaran
                            </label>
                            <Select
                                value={statusFilter}
                                onValueChange={setStatusFilter}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Semua Status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">
                                        Semua Status
                                    </SelectItem>
                                    <SelectItem value="pending">
                                        Menunggu
                                    </SelectItem>
                                    <SelectItem value="processing">
                                        Sedang Diproses
                                    </SelectItem>
                                    <SelectItem value="cancelled">
                                        Dibatalkan
                                    </SelectItem>
                                    <SelectItem value="completed">
                                        Selesai
                                    </SelectItem>
                                    <SelectItem value="shipped">
                                        Dalam Pengiriman
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="space-y-2">
                            <label className="text-sm font-medium">
                                Nominal Poin Merchandise
                            </label>
                            <Select
                                value={nominalFilter}
                                onValueChange={setNominalFilter}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Semua Nominal" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">
                                        Semua Nominal
                                    </SelectItem>
                                    <SelectItem value="below500">
                                        &lt; 500
                                    </SelectItem>
                                    <SelectItem value="500to1000">
                                        500 – 1000
                                    </SelectItem>
                                    <SelectItem value="above1000">
                                        &gt; 1000
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="space-y-2">
                            <label className="text-sm font-medium">
                                Pencarian Penukaran Item
                            </label>
                            <Input
                                type="text"
                                placeholder="Cari berdasarkan Nama atau Deskripsi Merchandise..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                            />
                        </div>
                    </div>
                </CardContent>
            </Card>
            <div className="space-y-4">
                {filteredReedems.map((reedem) => (
                    <Card
                        key={reedem.id}
                        className="transition-shadow hover:shadow-md"
                    >
                        <CardContent className="p-6">
                            <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                {/* Kiri: Gambar & Nama */}
                                <div className="flex flex-1 gap-4">
                                    <img
                                        src={`/storage/${reedem.merchandise?.image_url}`}
                                        alt={reedem.merchandise?.name}
                                        className="object-contain w-16 h-16 rounded-md"
                                    />
                                    <div>
                                        <p className="text-sm text-gray-500">
                                            Barang Merchandise
                                        </p>
                                        <h3 className="text-lg font-semibold text-gray-900">
                                            {reedem.merchandise?.name}
                                        </h3>
                                        <p className="text-sm text-gray-600">
                                            Tanggal Penukaran:{' '}
                                            {formatFullDateTime(
                                                reedem.created_at,
                                            )}
                                        </p>
                                    </div>
                                </div>

                                {/* Kanan: Poin & Status & Button */}
                                <div className="flex flex-col gap-2 sm:items-end">
                                    <div className="text-right">
                                        <div className="flex items-center text-lg font-bold text-yellow-600">
                                            <Star
                                                size={16}
                                                className="mr-1 fill-current"
                                            />
                                            {reedem.points_spent.toLocaleString()}{' '}
                                            Poin
                                        </div>
                                        <Badge
                                            className={getStatusClass(
                                                reedem.status,
                                            )}
                                        >
                                            {getReedemStatusLabel(
                                                reedem.status,
                                            )}
                                        </Badge>
                                    </div>
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        onClick={() => toggleDetail(reedem.id)}
                                        className="shrink-0"
                                    >
                                        {expandedItems.has(reedem.id) ? (
                                            <>
                                                <ChevronUp className="w-4 h-4 mr-2" />
                                                Tutup
                                            </>
                                        ) : (
                                            <>
                                                <ChevronDown className="w-4 h-4 mr-2" />
                                                Detail
                                            </>
                                        )}
                                    </Button>
                                </div>
                            </div>
                            {expandedItems.has(reedem.id) && (
                                <div className="p-4 mt-4 text-sm border rounded-lg shadow-sm border-sky-200 bg-sky-50">
                                    <div className="mb-4">
                                        <h3 className="text-base font-semibold text-gray-800">
                                            Deskripsi Barang Merchandise
                                        </h3>
                                        <p className="mt-1 text-gray-600">
                                            {reedem.merchandise?.description ||
                                                'Tidak ada deskripsi'}
                                        </p>
                                    </div>

                                    <div className="pt-4 border-t border-gray-200">
                                        <h3 className="mb-2 text-base font-semibold text-gray-800">
                                            Informasi Pengiriman
                                        </h3>
                                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <div>
                                                <p className="font-medium text-gray-900">
                                                    Alamat Pengiriman
                                                </p>
                                                <p className="text-gray-600">
                                                    {reedem.shipping_address ||
                                                        'Alamat tidak diketahui'}
                                                </p>
                                            </div>
                                            <div>
                                                <p className="font-medium text-gray-900">
                                                    Tracking Number
                                                </p>
                                                <p className="text-gray-600">
                                                    {reedem.tracking_number ||
                                                        'Belum ada Tracking Number'}
                                                </p>
                                            </div>
                                            <div>
                                                <p className="font-medium text-gray-900">
                                                    Layanan Jasa Kirim
                                                </p>
                                                <p className="text-gray-600">
                                                    {reedem.logistic ||
                                                        'Belum ada Pengiriman'}
                                                </p>
                                            </div>
                                            <div>
                                                <p className="font-medium text-gray-900">
                                                    Status Terakhir Diperbarui
                                                </p>
                                                <p className="text-gray-600">
                                                    {formatFullDateTime(
                                                        reedem.updated_at,
                                                    ) || 'Belum diperbarui'}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </CardContent>
                    </Card>
                ))}

                {filteredReedems.length === 0 && (
                    <Card>
                        <CardContent className="flex flex-col items-center py-12 text-center">
                            <ShoppingBag className="w-10 h-10 mb-3 text-gray-400" />
                            <p className="text-gray-500">
                                Tidak ada data penukaran merchandise.
                            </p>
                        </CardContent>
                    </Card>
                )}
            </div>
        </div>
    );
};

export default MyMerchandisePage;
