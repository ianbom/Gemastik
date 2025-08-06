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
import { Donation } from '@/types/donation/interface';
import { getStatusClass } from '@/utils/badgeStatusDonationColor';
import { formatFullDateTime } from '@/utils/formatDate';
import { getStatusDonationLabel } from '@/utils/statusDonationLabel';
import {
    ChevronDown,
    ChevronUp,
    Heart,
    RefreshCcw,
    SlidersHorizontal,
} from 'lucide-react';
import { useState } from 'react';
interface MyDonationPageProps {
    donations: Donation[];
}

const MyDonationPage = ({ donations }: MyDonationPageProps) => {
    // const [sortBy, setSortBy] = useState('newest');
    const [searchQuery, setSearchQuery] = useState('');
    const [statusFilter, setStatusFilter] = useState('all');
    const [nominalFilter, setNominalFilter] = useState('all');
    const [expandedItems, setExpandedItems] = useState(new Set());

    const filteredDonations = donations.filter((donation) => {
        const matchesStatus =
            statusFilter === 'all' ? true : donation.status === statusFilter;
        const matchesNominal =
            nominalFilter === 'all'
                ? true
                : (nominalFilter === 'below50k' && donation.amount < 50000) ||
                  (nominalFilter === '50kto100k' &&
                      donation.amount >= 50000 &&
                      donation.amount <= 100000) ||
                  (nominalFilter === 'above100k' && donation.amount > 100000);
        const matchesSearch = (
            (donation.report?.title || '') + (donation.transaction_id || '')
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
        <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div className="mb-8">
                <h1 className="mb-2 text-3xl font-bold text-gray-900">
                    Riwayat Donasi
                </h1>
                <p className="text-gray-600">
                    Berikut adalah riwayat donasi Anda
                </p>
            </div>
            <Card className="mb-6">
                <CardHeader>
                    <div className="flex items-center justify-between">
                        <CardTitle className="flex items-center justify-between gap-4 text-lg">
                            <div className="flex items-center">
                                <SlidersHorizontal
                                    size={20}
                                    className="mr-2 text-emerald-600"
                                />
                                Filter Donasi
                            </div>
                            {hasActiveFilters && (
                                <span className="rounded-full bg-emerald-100 px-2 py-1 text-xs text-emerald-600">
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
                            <RefreshCcw className="mr-2 h-4 w-4" />
                            Reset Filter
                        </Button>
                    </div>
                </CardHeader>
                <CardContent>
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div className="space-y-2">
                            <label className="text-sm font-medium">
                                Status Donasi
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
                                    <SelectItem value="paid">
                                        Pembayaran Berhasil
                                    </SelectItem>
                                    <SelectItem value="expired">
                                        Pembayaran Kadaluwarsa
                                    </SelectItem>
                                    <SelectItem value="cancelled">
                                        Pembayaran Dibatalkan
                                    </SelectItem>
                                    <SelectItem value="pending">
                                        Menunggu Pembayaran
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="space-y-2">
                            <label className="text-sm font-medium">
                                Nominal Donasi
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
                                    <SelectItem value="below50k">
                                        &lt; 50.000
                                    </SelectItem>
                                    <SelectItem value="50kto100k">
                                        50.000 – 100.000
                                    </SelectItem>
                                    <SelectItem value="above100k">
                                        &gt; 100.000
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="space-y-2">
                            <label className="text-sm font-medium">
                                Pencarian Donasi
                            </label>
                            <Input
                                type="text"
                                placeholder="Cari berdasarkan Laporan atau ID Transaksi..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                            />
                        </div>
                    </div>
                </CardContent>
            </Card>
            <div className="space-y-4">
                {filteredDonations.map((donation) => (
                    <Card
                        key={donation.id}
                        className="transition-shadow hover:shadow-md"
                    >
                        <CardContent className="p-6">
                            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div className="flex-1">
                                    <p>Laporan Terkait: </p>
                                    <h3 className="mb-1 text-lg font-semibold text-gray-900">
                                        {donation.report?.title}
                                    </h3>
                                    <p className="mb-2 text-sm text-gray-600">
                                        Tanggal Donasi:{' '}
                                        {formatFullDateTime(
                                            donation.created_at,
                                        )}
                                    </p>
                                </div>
                                <div className="mt-4 flex items-center gap-4 sm:mt-0 sm:flex-col sm:items-end">
                                    <div className="text-right">
                                        <p className="text-lg font-semibold text-emerald-600">
                                            Rp{' '}
                                            {donation.amount.toLocaleString()}
                                        </p>
                                        <Badge
                                            className={getStatusClass(
                                                donation.status,
                                            )}
                                        >
                                            {getStatusDonationLabel(
                                                donation.status,
                                            )}
                                        </Badge>
                                    </div>
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        onClick={() =>
                                            toggleDetail(donation.id)
                                        }
                                        className="shrink-0"
                                    >
                                        {expandedItems.has(donation.id) ? (
                                            <>
                                                <ChevronUp className="mr-2 h-4 w-4" />
                                                Tutup
                                            </>
                                        ) : (
                                            <>
                                                <ChevronDown className="mr-2 h-4 w-4" />
                                                Detail
                                            </>
                                        )}
                                    </Button>
                                </div>
                            </div>
                            {expandedItems.has(donation.id) && (
                                <div className="mt-4 border-t border-gray-200 pt-4">
                                    <div className="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                                        <div>
                                            <span className="font-medium text-gray-900">
                                                ID Transaksi:
                                            </span>
                                            <p className="text-gray-600">
                                                #{donation.transaction_id}
                                            </p>
                                        </div>
                                        <div>
                                            <span className="font-medium text-gray-900">
                                                Metode Pembayaran:
                                            </span>
                                            <p className="text-gray-600">
                                                {donation.payment_method ||
                                                    'Transfer Bank'}
                                            </p>
                                        </div>
                                        <div className="md:col-span-2">
                                            <span className="font-medium text-gray-900">
                                                Deskripsi Laporan Terkait:
                                            </span>
                                            <p className="mt-1 text-gray-600">
                                                {donation.report?.description}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </CardContent>
                    </Card>
                ))}

                {filteredDonations.length === 0 && (
                    <Card>
                        <CardContent className="flex flex-col items-center py-12 text-center">
                            <Heart className="mb-3 h-10 w-10 text-gray-400" />
                            <p className="text-gray-500">
                                Tidak ada data donasi ditemukan.
                            </p>
                        </CardContent>
                    </Card>
                )}
            </div>
        </div>
    );
};

export default MyDonationPage;
