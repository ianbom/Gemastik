('use client');
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { City, District, Province } from '@/types/area/interface';
import { formatFullDateTime } from '@/utils/formatDate';

import { Report } from '@/types/report';
import { getCategoryLabel } from '@/utils/categoryReportLabel';
import { getStatusColor } from '@/utils/reportStatusColor';
import { getStatusLabel } from '@/utils/reportStatusLabel';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

import {
    Calendar,
    Eye,
    MapPin,
    RefreshCcw,
    Search,
    SlidersHorizontal,
} from 'lucide-react';

import { useEffect, useState } from 'react';
import { MapContainer, Marker, Popup, TileLayer } from 'react-leaflet';

interface MapPageProps {
    reports: Report[];
    provinces: Province[];
    cities: City[];
    districts: District[];
    onViewReport: (id: number | string) => void;
}

interface FilterState {
    category: string;
    status: string;
    province: string;
    startDate: string;
    endDate: string;
}

delete (L.Icon.Default.prototype as L.Icon.Default & { _getIconUrl?: unknown })
    ._getIconUrl;
L.Icon.Default.mergeOptions({
    iconUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-icon.png',
    iconRetinaUrl:
        'https://unpkg.com/leaflet@1.9.3/dist/images/marker-icon-2x.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-shadow.png',
});

const MapPage = ({ reports, provinces, onViewReport }: MapPageProps) => {
    const [showFilters, setShowFilters] = useState(false);
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

    // Function untuk apply semua filter
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
                        .includes(searchQuery.toLowerCase()) ||
                    report.address
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

        setFilteredReports(processedReports);
    }, [reports, searchQuery, filters]);

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
        <div className="flex flex-col h-screen lg:flex-row">
            <div
                className={`bg-white shadow-lg transition-all duration-300 ${showFilters ? 'max-h-[1000px] opacity-100' : 'max-h-0 opacity-0'} overflow-hidden lg:relative lg:max-h-full lg:w-80 lg:border-r lg:border-gray-200 lg:opacity-100`}
            >
                <div className="p-6">
                    <div className="flex items-center justify-between mb-6">
                        <h2 className="flex items-center text-xl font-semibold text-gray-900">
                            <SlidersHorizontal
                                size={20}
                                className="mr-2 text-sky-600"
                            />
                            Filter Peta Laporan
                            {hasActiveFilters && (
                                <span className="inline-block px-2 py-1 ml-2 text-xs rounded-full bg-emerald-100 text-emerald-600">
                                    Aktif
                                </span>
                            )}
                        </h2>
                        <Button
                            variant="ghost"
                            size="sm"
                            className="lg:hidden"
                            onClick={() => setShowFilters(false)}
                        >
                            ×
                        </Button>
                    </div>

                    {/* Search Bar */}
                    <div className="mb-6">
                        <div className="relative">
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
                            <span className="mb-1 text-xs font-medium text-gray-700">
                                Filter aktif:
                            </span>
                            {searchQuery && (
                                <Badge
                                    variant="secondary"
                                    className="flex items-center gap-1 text-xs"
                                >
                                    "{searchQuery}"
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
                                    className="flex items-center gap-1 text-xs"
                                >
                                    {filters.category}
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
                                    className="flex items-center gap-1 text-xs"
                                >
                                    {getStatusLabel(filters.status)}
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
                                    className="flex items-center gap-1 text-xs"
                                >
                                    {filters.province}
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
                        </div>
                    )}

                    <div className="space-y-4">
                        <div className="space-y-2">
                            <label className="text-sm font-medium text-gray-700">
                                Kategori Laporan
                            </label>
                            <Select
                                value={filters.category}
                                onValueChange={(value) =>
                                    updateFilter('category', value)
                                }
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Semua kategori" />
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
                                Status Laporan
                            </label>
                            <Select
                                value={filters.status}
                                onValueChange={(value) =>
                                    updateFilter('status', value)
                                }
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Semua status" />
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
                                    <SelectValue placeholder="Semua Provinsi" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="semua">
                                        Semua Provinsi
                                    </SelectItem>
                                    {provinces.map((province) => (
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
                                    updateFilter('startDate', e.target.value)
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
                        <div className="flex space-x-2">
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
                    </div>
                </div>
            </div>

            {/* Main Map Area */}
            <div className="relative flex-1">
                <div className="p-4 lg:hidden">
                    <Button
                        onClick={() => setShowFilters(!showFilters)}
                        className="w-full text-white bg-sky-600"
                    >
                        {showFilters ? 'Tutup Filter' : 'Tampilkan Filter'}
                    </Button>
                </div>

                <MapContainer
                    center={[-2.5489, 118.0149]}
                    zoom={5}
                    scrollWheelZoom={true}
                    className="z-10 w-full h-full"
                >
                    <TileLayer
                        attribution='&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
                        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                    />

                    {filteredReports.map((report) => (
                        <Marker
                            key={report.id}
                            position={[report.latitude, report.longitude]}
                        >
                            <Popup
                                className="custom-popup"
                                minWidth={280}
                                maxWidth={320}
                                closeButton={true}
                                autoPan={true}
                            >
                                <div className="relative overflow-hidden bg-white rounded-lg shadow-lg">
                                    <div className="relative h-32 bg-gradient-to-r from-sky-500 to-blue-600">
                                        {report.media?.[0] ? (
                                            report.media[0].media_type?.startsWith(
                                                'video',
                                            ) ? (
                                                <div className="relative w-full h-full bg-black">
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
                                                    src={`/storage/${report.media[0].media_url}`}
                                                    alt={report.title}
                                                    className="object-cover w-full h-full"
                                                />
                                            )
                                        ) : (
                                            <div className="flex items-center justify-center h-full bg-gray-800">
                                                <svg
                                                    className="w-8 h-8 text-white/80"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={2}
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                    />
                                                </svg>
                                            </div>
                                        )}

                                        <div className="absolute right-2 top-2">
                                            <Badge
                                                className={`${getStatusColor(report.status)} text-xs font-medium shadow-sm`}
                                            >
                                                {getStatusLabel(report.status)}
                                            </Badge>
                                        </div>

                                        {report.hasMission && (
                                            <div className="absolute left-2 top-2">
                                                <Badge className="text-xs font-medium text-white bg-blue-500 shadow-sm">
                                                    Ada Misi
                                                </Badge>
                                            </div>
                                        )}
                                    </div>

                                    <div className="p-4">
                                        <div className="mb-2">
                                            <Badge className="text-xs font-medium text-gray-700 border-gray-200 bg-gray-50">
                                                {getCategoryLabel(
                                                    report.category,
                                                )}
                                            </Badge>
                                        </div>

                                        <h3 className="mb-3 text-base font-semibold leading-tight text-gray-900 line-clamp-2">
                                            {report.title}
                                        </h3>

                                        <div className="flex items-start mb-3 text-sm text-gray-600">
                                            <MapPin
                                                size={14}
                                                className="mr-2 mt-0.5 flex-shrink-0 text-gray-400"
                                            />
                                            <span className="line-clamp-2">
                                                {report.address}
                                            </span>
                                        </div>

                                        <div className="flex items-center justify-between mb-4 text-xs text-gray-500">
                                            <div className="flex items-center">
                                                <Calendar
                                                    size={12}
                                                    className="mr-1"
                                                />
                                                <span>
                                                    {formatFullDateTime(
                                                        report.created_at,
                                                    )}
                                                </span>
                                            </div>
                                        </div>

                                        <Button
                                            size="sm"
                                            onClick={() =>
                                                onViewReport(report.id)
                                            }
                                            className="w-full text-white transition-colors duration-200 bg-cyan-600 hover:bg-cyan-700"
                                        >
                                            <Eye size={14} className="mr-2" />
                                            Lihat Detail
                                        </Button>
                                    </div>
                                </div>
                            </Popup>
                        </Marker>
                    ))}
                </MapContainer>

                <div className="absolute z-20 bottom-4 right-4">
                    <Card className="shadow-lg bg-white/90 backdrop-blur-sm">
                        <CardContent className="p-3 text-center">
                            <div className="text-sm font-medium text-gray-900">
                                {filteredReports.length} Laporan Ditemukan
                            </div>
                            <div className="text-xs text-gray-600">
                                Klik marker untuk lihat detail
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    );
};

export default MapPage;
