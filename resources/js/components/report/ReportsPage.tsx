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
import { Province } from '@/types/area/interface';
import { Report } from '@/types/report';
import { getCategoryLabel } from '@/utils/categoryReportLabel';
import { formatDateOnly } from '@/utils/formatDate';
import { getStatusColor } from '@/utils/reportStatusColor';
import { getStatusLabel } from '@/utils/reportStatusLabel';
import { router as Inertia } from '@inertiajs/react';
import {
    Calendar,
    Eye,
    MapPin,
    Plus,
    RefreshCcw,
    Search,
    SlidersHorizontal,
    ThumbsDown,
    ThumbsUp,
} from 'lucide-react';
import { useEffect, useState } from 'react';
import { Badge } from '../ui/badge';

interface ReportsPageProps {
    reports: Report[];
    provinces: Province[];
    myReports: boolean;
    onViewDetails: (id: number) => void;
    onCreateReport: () => void;
}

interface FilterState {
    category: string;
    status: string;
    province: string;
    startDate: string;
    endDate: string;
}

const ReportsPage = ({
    reports,
    provinces,
    myReports,
    onViewDetails,
    onCreateReport,
}: ReportsPageProps) => {
    const [sortBy, setSortBy] = useState('newest');
    const [searchQuery, setSearchQuery] = useState('');
    const [filteredReports, setFilteredReports] = useState<Report[]>(reports);

    // State untuk semua filter
    const [filters, setFilters] = useState<FilterState>({
        category: 'semua',
        status: 'semua',
        province: 'semua',
        startDate: '',
        endDate: '',
    });

    // Function untuk mengupdate filter individual
    const updateFilter = (key: keyof FilterState, value: string) => {
        setFilters((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    // Function untuk reset filter
    const resetFilters = () => {
        setFilters({
            category: 'semua',
            status: 'semua',
            province: 'semua',
            startDate: '',
            endDate: '',
        });
        setSearchQuery('');
    };

    // Function untuk apply semua filter dan sorting
    useEffect(() => {
        let processedReports = [...reports];

        // Apply search filter
        if (searchQuery.trim()) {
            processedReports = processedReports.filter(
                (report) =>
                    report.title
                        .toLowerCase()
                        .includes(searchQuery.toLowerCase()) ||
                    report.description
                        ?.toLowerCase()
                        .includes(searchQuery.toLowerCase()),
            );
        }

        // Apply category filter
        if (filters.category !== 'semua') {
            processedReports = processedReports.filter(
                (report) =>
                    report.category.toLowerCase() ===
                    filters.category.toLowerCase(),
            );
        }

        // Apply status filter
        if (filters.status !== 'semua') {
            processedReports = processedReports.filter(
                (report) =>
                    report.status.toLowerCase() ===
                    filters.status.toLowerCase(),
            );
        }

        // Apply province filter
        if (filters.province !== 'semua') {
            processedReports = processedReports.filter((report) =>
                report.province?.name
                    .toLowerCase()
                    .includes(filters.province.toLowerCase()),
            );
        }

        // Apply date range filter
        if (filters.startDate) {
            processedReports = processedReports.filter((report) => {
                const reportDate = new Date(report.created_at);
                const startDate = new Date(filters.startDate);
                return reportDate >= startDate;
            });
        }

        if (filters.endDate) {
            processedReports = processedReports.filter((report) => {
                const reportDate = new Date(report.created_at);
                const endDate = new Date(filters.endDate);
                endDate.setHours(23, 59, 59, 999);
                return reportDate <= endDate;
            });
        }

        // Apply sorting
        if (sortBy === 'newest') {
            processedReports.sort(
                (a, b) =>
                    new Date(b.created_at).getTime() -
                    new Date(a.created_at).getTime(),
            );
        } else if (sortBy === 'oldest') {
            processedReports.sort(
                (a, b) =>
                    new Date(a.created_at).getTime() -
                    new Date(b.created_at).getTime(),
            );
        } else if (sortBy === 'popular') {
            processedReports.sort(
                (a, b) => (b.upvotes_count || 0) - (a.upvotes_count || 0),
            );
        } else if (sortBy === 'status') {
            processedReports.sort((a, b) => a.status.localeCompare(b.status));
        } else if (sortBy === 'title') {
            processedReports.sort((a, b) => a.title.localeCompare(b.title));
        }

        setFilteredReports(processedReports);
    }, [reports, searchQuery, sortBy, filters]);

    // Get unique categories from reports for dynamic options
    const availableCategories = [
        { label: 'Sampah Plastik', value: 'sampah-plastik' },
        { label: 'Pencemaran Air', value: 'pencemaran-air' },
        { label: 'Pencemaran Udara', value: 'pencemaran-udara' },
        { label: 'Pencemaran Tanah', value: 'pencemaran-tanah' },
        { label: 'Limbah Industri', value: 'limbah-industri' },
        { label: 'Emisi Gas Rumah Kaca', value: 'emisi-gas-rumah-kaca' },
        {
            label: 'Penggundulan / Kebakaran Hutan',
            value: 'penggundulan-kebakaran-hutan',
        },
        {
            label: 'Naiknya Permukaan Air Laut',
            value: 'naiknya-permukaan-air-laut',
        },
        {
            label: 'Limbah Pertanian / Peternakan',
            value: 'limbah-pertanian-peternakan',
        },
        { label: 'Lainnya', value: 'lainnya' },
    ];

    const hasActiveFilters =
        searchQuery.trim() ||
        filters.category !== 'semua' ||
        filters.status !== 'semua' ||
        filters.province !== 'semua' ||
        filters.startDate ||
        filters.endDate;

    return (
        <div className="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div className="flex flex-col items-start justify-between mb-8 md:flex-row md:items-center">
                <div>
                    <h1 className="mb-2 text-3xl font-bold text-gray-900">
                        {myReports ? 'Laporan Saya' : 'Daftar Laporan'}
                    </h1>
                    <p className="text-gray-600">
                        {myReports
                            ? 'Laporan yang dibuat oleh Anda'
                            : 'Temukan Laporan dan bergabung dalam aksi penyelamatan lingkungan'}
                    </p>
                    {/* <p className="mt-1 text-sm text-gray-500">
                        Menampilkan {filteredReports.length} dari {reports.length} laporan
                    </p> */}
                </div>
                <Button
                    onClick={onCreateReport}
                    className="mt-4 text-white bg-sky-700 hover:bg-sky-800 md:mt-0"
                    size="lg"
                >
                    <Plus size={20} className="mr-2" />
                    Buat Laporan Baru
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-8 lg:grid-cols-4">
                {/* Filter Sidebar */}
                <div className="lg:col-span-1">
                    {/* <Card className="sticky top-24"> */}
                    <Card className="sticky overflow-visible top-24">
                        <CardHeader>
                            <CardTitle className="flex items-center justify-between text-lg">
                                <div className="flex items-center">
                                    <SlidersHorizontal
                                        size={20}
                                        className="mr-2 text-sky-600"
                                    />
                                    Filter Laporan
                                </div>
                                {hasActiveFilters && (
                                    <span className="px-2 py-1 text-xs rounded-full bg-emerald-100 text-emerald-600">
                                        Aktif
                                    </span>
                                )}
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">
                                    Kategori
                                </label>
                                <Select
                                    value={filters.category}
                                    onValueChange={(value) =>
                                        updateFilter('category', value)
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih kategori" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="semua">
                                            Semua Kategori
                                        </SelectItem>
                                        {availableCategories.map((cat) => (
                                            <SelectItem
                                                key={cat.value}
                                                value={cat.value}
                                            >
                                                {cat.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">
                                    Status
                                </label>
                                <Select
                                    value={filters.status}
                                    onValueChange={(value) =>
                                        updateFilter('status', value)
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="semua">
                                            Semua Status
                                        </SelectItem>
                                        <SelectItem value="pending">
                                            Menunggu
                                        </SelectItem>
                                        <SelectItem value="verified">
                                            Terverifikasi
                                        </SelectItem>
                                        <SelectItem value="on-progress">
                                            Sedang Diproses
                                        </SelectItem>
                                        <SelectItem value="rejected">
                                            Ditolak
                                        </SelectItem>
                                        <SelectItem value="completed">
                                            Selesai
                                        </SelectItem>
                                        <SelectItem value="under-authority">
                                            Ditangani Pihak Berwenang
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">
                                    Provinsi
                                </label>
                                <Select
                                    value={filters.province}
                                    onValueChange={(value) =>
                                        updateFilter('province', value)
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih provinsi" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="semua">
                                            Semua Provinsi
                                        </SelectItem>
                                        {provinces?.map((province) => (
                                            <SelectItem
                                                key={province.id}
                                                value={province.name}
                                            >
                                                {province.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">
                                    Tanggal Mulai
                                </label>
                                <input
                                    type="date"
                                    className="w-full px-2 py-2 border border-gray-200 rounded-md"
                                    value={filters.startDate}
                                    onChange={(e) =>
                                        updateFilter(
                                            'startDate',
                                            e.target.value,
                                        )
                                    }
                                />
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">
                                    Tanggal Selesai
                                </label>
                                <input
                                    type="date"
                                    className="w-full px-2 py-2 border border-gray-200 rounded-md"
                                    value={filters.endDate}
                                    onChange={(e) =>
                                        updateFilter('endDate', e.target.value)
                                    }
                                    min={filters.startDate}
                                />
                            </div>
                            <div className="pt-4 space-y-2">
                                <Button
                                    variant="outline"
                                    className="flex items-center justify-center w-full gap-2"
                                    onClick={resetFilters}
                                    disabled={!hasActiveFilters}
                                >
                                    <RefreshCcw className="w-4 h-4" />
                                    Reset Filter
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Main Content */}
                <div className="lg:col-span-3">
                    {/* Sort and Search */}
                    <div className="flex flex-col items-start justify-between gap-4 mb-6 sm:flex-row sm:items-center">
                        <div className="flex items-center space-x-4">
                            <Select value={sortBy} onValueChange={setSortBy}>
                                <SelectTrigger className="w-48">
                                    <SelectValue placeholder="Urutkan berdasarkan" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="newest">
                                        Terbaru
                                    </SelectItem>
                                    <SelectItem value="oldest">
                                        Terlama
                                    </SelectItem>
                                    <SelectItem value="popular">
                                        Terpopuler
                                    </SelectItem>
                                    <SelectItem value="status">
                                        Status
                                    </SelectItem>
                                    <SelectItem value="title">
                                        Judul A-Z
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="relative w-full sm:w-64">
                            <Search className="absolute w-4 h-4 text-gray-400 left-3 top-3" />
                            <Input
                                placeholder="Cari laporan..."
                                className="pl-10"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                            />
                        </div>
                    </div>
                    {/* Active Filters Display */}
                    {(filters.category !== 'semua' ||
                        filters.status !== 'semua' ||
                        filters.province !== 'semua' ||
                        filters.startDate ||
                        filters.endDate ||
                        searchQuery) && (
                        <div className="flex flex-wrap gap-2 mb-4">
                            <span className="mr-2 text-sm font-medium text-gray-700">
                                Filter aktif:
                            </span>
                            {searchQuery && (
                                <Badge
                                    variant="secondary"
                                    className="flex items-center gap-1"
                                >
                                    Pencarian: "{searchQuery}"
                                    <button
                                        onClick={() => setSearchQuery('')}
                                        className="ml-1 text-xs"
                                    >
                                        ×
                                    </button>
                                </Badge>
                            )}
                            {filters.category !== 'semua' && (
                                <Badge
                                    variant="secondary"
                                    className="flex items-center gap-1"
                                >
                                    Kategori: {filters.category}
                                    <button
                                        onClick={() =>
                                            updateFilter('category', 'semua')
                                        }
                                        className="ml-1 text-xs"
                                    >
                                        ×
                                    </button>
                                </Badge>
                            )}
                            {filters.status !== 'semua' && (
                                <Badge
                                    variant="secondary"
                                    className="flex items-center gap-1"
                                >
                                    Status: {filters.status}
                                    <button
                                        onClick={() =>
                                            updateFilter('status', 'semua')
                                        }
                                        className="ml-1 text-xs"
                                    >
                                        ×
                                    </button>
                                </Badge>
                            )}
                            {filters.province !== 'semua' && (
                                <Badge
                                    variant="secondary"
                                    className="flex items-center gap-1"
                                >
                                    Provinsi: {filters.province}
                                    <button
                                        onClick={() =>
                                            updateFilter('province', 'semua')
                                        }
                                        className="ml-1 text-xs"
                                    >
                                        ×
                                    </button>
                                </Badge>
                            )}
                            {(filters.startDate || filters.endDate) && (
                                <Badge
                                    variant="secondary"
                                    className="flex items-center gap-1"
                                >
                                    Tanggal: {filters.startDate || '...'} -{' '}
                                    {filters.endDate || '...'}
                                    <button
                                        onClick={() => {
                                            updateFilter('startDate', '');
                                            updateFilter('endDate', '');
                                        }}
                                        className="ml-1 text-xs"
                                    >
                                        ×
                                    </button>
                                </Badge>
                            )}
                        </div>
                    )}
                    {filteredReports.length > 0 ? (
                        <>
                            <div className="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                                {filteredReports.map((report: Report) => (
                                    <Card
                                        key={report.id}
                                        className="transition-all duration-300 border-0 shadow-md cursor-pointer group hover:-translate-y-1 hover:shadow-lg"
                                        onClick={() => onViewDetails(report.id)}
                                    >
                                        <div className="relative overflow-hidden rounded-t-lg">
                                            {report.media?.[0]?.media_type?.startsWith(
                                                'video',
                                            ) ? (
                                                <div className="relative w-full h-48 bg-black">
                                                    <video
                                                        className="object-cover w-full h-full opacity-50"
                                                        src={`/storage/${report.media[0].media_url}`}
                                                        muted
                                                        preload="metadata"
                                                    />
                                                    <div className="absolute inset-0 flex items-center justify-center">
                                                        <div className="p-2 rounded-full bg-white/80">
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                className="w-6 h-6 text-black"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                            >
                                                                <path
                                                                    strokeLinecap="round"
                                                                    strokeLinejoin="round"
                                                                    strokeWidth={
                                                                        2
                                                                    }
                                                                    d="M14.752 11.168l-5.197-3.03A1 1 0 008 9.03v5.94a1 1 0 001.555.832l5.197-3.03a1 1 0 000-1.664z"
                                                                />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            ) : (
                                                <img
                                                    src={`/storage/${report.media?.[0]?.media_url}`}
                                                    alt={report.title}
                                                    className="object-cover w-full h-48 transition-transform duration-300 group-hover:scale-105"
                                                />
                                            )}

                                            <div className="absolute right-3 top-3">
                                                <Badge
                                                    className={getStatusColor(
                                                        report.status,
                                                    )}
                                                >
                                                    {getStatusLabel(
                                                        report.status,
                                                    )}
                                                </Badge>
                                            </div>
                                            {report.mission && (
                                                <div className="absolute left-3 top-3">
                                                    <Badge className="text-indigo-700 bg-indigo-100">
                                                        Ada Misi
                                                    </Badge>
                                                </div>
                                            )}
                                        </div>

                                        <CardContent className="p-4">
                                            <div className="mb-2">
                                                <Badge
                                                    variant="outline"
                                                    className="mb-2 text-xs"
                                                >
                                                    {getCategoryLabel(
                                                        report.category,
                                                    )}
                                                </Badge>
                                            </div>

                                            <h3 className="mb-2 font-semibold text-gray-900 transition-colors line-clamp-2 group-hover:text-sky-600">
                                                {report.title}
                                            </h3>

                                            <div className="flex items-center mb-3 text-sm text-gray-500">
                                                <MapPin
                                                    size={14}
                                                    className="mr-1"
                                                />
                                                <span className="truncate">
                                                    {report.district?.name},{' '}
                                                    {report.city?.name},{' '}
                                                    {report.province?.name}
                                                </span>
                                            </div>

                                            <div className="flex items-center justify-between mb-4">
                                                <div className="flex items-center text-sm text-gray-500">
                                                    <Calendar
                                                        size={14}
                                                        className="mr-1"
                                                    />
                                                    <span>
                                                        {formatDateOnly(
                                                            report.created_at,
                                                        )}
                                                    </span>
                                                </div>
                                                <div className="flex items-center justify-between gap-5">
                                                    <div className="flex items-center text-sm font-medium text-sky-600">
                                                        <ThumbsUp
                                                            size={14}
                                                            className="mr-1"
                                                        />
                                                        <span>
                                                            {report.upvotes_count ||
                                                                0}
                                                        </span>
                                                    </div>
                                                    <div className="flex items-center text-sm font-medium text-red-600">
                                                        <ThumbsDown
                                                            size={14}
                                                            className="mr-1"
                                                        />
                                                        <span>
                                                            {report.dislikes_count ||
                                                                0}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <Button
                                                className="w-full mt-auto transition-colors duration-200 bg-cyan-500 hover:bg-cyan-700"
                                                onClick={() => {
                                                    // e.stopPropagation();
                                                    Inertia.visit(
                                                        `/report/${report.id}`,
                                                    );
                                                }}
                                            >
                                                <Eye
                                                    size={16}
                                                    className="mr-2"
                                                />
                                                Lihat Detail
                                            </Button>
                                        </CardContent>
                                    </Card>
                                ))}
                            </div>
                        </>
                    ) : (
                        <div className="flex items-center justify-center w-full">
                            <Card className="w-full px-8 py-32 text-center">
                                <div className="flex justify-center mb-4">
                                    <div className="p-4 bg-gray-100 rounded-full">
                                        <svg
                                            className="w-12 h-12 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            />
                                        </svg>
                                    </div>
                                </div>
                                <h3 className="mb-2 text-lg font-semibold text-gray-900">
                                    {searchQuery ||
                                    filters.category !== 'semua' ||
                                    filters.status !== 'semua' ||
                                    filters.province !== 'semua' ||
                                    filters.startDate ||
                                    filters.endDate
                                        ? 'Tidak Ada Laporan yang Sesuai Filter'
                                        : 'Laporan Belum Tersedia'}
                                </h3>
                                <p className="text-sm text-gray-500">
                                    {searchQuery ||
                                    filters.category !== 'semua' ||
                                    filters.status !== 'semua' ||
                                    filters.province !== 'semua' ||
                                    filters.startDate ||
                                    filters.endDate
                                        ? 'Coba ubah atau hapus beberapa filter untuk melihat lebih banyak laporan.'
                                        : 'Belum ada laporan yang tersedia saat ini. Coba buat laporan baru.'}
                                </p>
                                {(searchQuery ||
                                    filters.category !== 'semua' ||
                                    filters.status !== 'semua' ||
                                    filters.province !== 'semua' ||
                                    filters.startDate ||
                                    filters.endDate) && (
                                    <Button
                                        variant="outline"
                                        className="mt-4"
                                        onClick={resetFilters}
                                    >
                                        Reset Semua Filter
                                    </Button>
                                )}
                            </Card>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default ReportsPage;
