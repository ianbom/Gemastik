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
import { Mission } from '@/types/report/mission';
import { formatFullDateTime } from '@/utils/formatDate';
import { getStatusColor } from '@/utils/missionStatusColor';
import { getMissionStatusLabel } from '@/utils/missionStatusLabel';
import { router as Inertia } from '@inertiajs/react';
import {
    Eye,
    MapPin,
    RefreshCcw,
    Search,
    SlidersHorizontal,
    Target,
} from 'lucide-react';
import { useMemo, useState } from 'react';
import { Badge } from '../ui/badge';
interface MissionPageProps {
    missions: Mission[];
    myMissions: boolean;
    onViewDetails: (id: number) => void;
    provinces: Province[];
}

interface FilterOptions {
    category: string;
    status: string;
    province: string;
    startDate: string;
    endDate: string;
}

const MissionPage = ({
    missions,
    myMissions,
    onViewDetails,
    provinces,
}: MissionPageProps) => {
    // console.log('ini prov', provinces);
    const [sortBy, setSortBy] = useState('newest');
    const [searchQuery, setSearchQuery] = useState('');
    const [filters, setFilters] = useState<FilterOptions>({
        category: 'semua',
        status: 'semua',
        province: 'semua',
        startDate: '',
        endDate: '',
    });
    // Filter and sort missions
    const filteredAndSortedMissions = useMemo(() => {
        let filtered = missions;

        // Apply search filter
        if (searchQuery.trim()) {
            filtered = filtered.filter(
                (mission) =>
                    mission.title
                        ?.toLowerCase()
                        .includes(searchQuery.toLowerCase()) ||
                    mission.description
                        ?.toLowerCase()
                        .includes(searchQuery.toLowerCase()) ||
                    mission.creator?.name
                        ?.toLowerCase()
                        .includes(searchQuery.toLowerCase()) ||
                    mission.address
                        ?.toLowerCase()
                        .includes(searchQuery.toLowerCase()),
            );
        }

        // Apply status filter
        if (filters.status !== 'semua') {
            const statusMap: { [key: string]: string } = {
                open: 'Dibuka',
                'on-progress': 'Sedang Berlangsung',
                completed: 'Selesai',
                cancelled: 'Dibatalkan',
                'under-authority': 'Misi Pihak Berwenang',
            };
            const mappedStatus = statusMap[filters.status] || filters.status;
            filtered = filtered.filter(
                (mission) => mission.status === mappedStatus,
            );
        }

        // Apply province filter
        if (filters.province !== 'semua') {
            filtered = filtered.filter(
                (mission) => mission.province?.name === filters.province,
            );
        }

        // Apply date range filter
        if (filters.startDate) {
            filtered = filtered.filter((mission) => {
                const missionDate = new Date(mission.scheduled_date);
                const startDate = new Date(filters.startDate);
                return missionDate >= startDate;
            });
        }

        if (filters.endDate) {
            filtered = filtered.filter((mission) => {
                const missionDate = new Date(mission.scheduled_date);
                const endDate = new Date(filters.endDate);
                return missionDate <= endDate;
            });
        }

        // Apply sorting
        const sorted = [...filtered].sort((a, b) => {
            switch (sortBy) {
                case 'newest':
                    return (
                        new Date(b.scheduled_date).getTime() -
                        new Date(a.scheduled_date).getTime()
                    );
                case 'oldest':
                    return (
                        new Date(a.scheduled_date).getTime() -
                        new Date(b.scheduled_date).getTime()
                    );
                case 'title':
                    return (a.title || '').localeCompare(b.title || '');
                case 'status':
                    return (a.status || '').localeCompare(b.status || '');

                default:
                    return 0;
            }
        });

        return sorted;
    }, [missions, searchQuery, filters, sortBy]);

    // Handle filter changes
    const handleFilterChange = (key: keyof FilterOptions, value: string) => {
        setFilters((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    // Reset filters
    const resetFilters = () => {
        setFilters({
            category: 'semua',
            status: 'semua',
            province: 'semua',
            startDate: '',
            endDate: '',
        });
        setSearchQuery('');
        setSortBy('newest');
    };

    // Check if any filters are active
    const hasActiveFilters =
        searchQuery.trim() ||
        filters.status !== 'semua' ||
        filters.province !== 'semua' ||
        filters.startDate ||
        filters.endDate;

    return (
        <div className="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div className="flex flex-col items-start justify-between mb-8 md:flex-row md:items-center">
                <div>
                    <h1 className="mb-2 text-3xl font-bold text-gray-900">
                        {myMissions ? 'Misi yang Diikuti' : 'Daftar Misi'}
                    </h1>
                    <p className="text-gray-600">
                        Temukan Misi dan jadilah bagian dalam aksi penyelamatan
                        lingkungan
                    </p>
                </div>
            </div>

            <div className="grid grid-cols-1 gap-8 lg:grid-cols-4">
                {/* Filter Sidebar */}
                <div className="lg:col-span-1">
                    <Card className="sticky top-24">
                        <CardHeader>
                            <CardTitle className="flex items-center justify-between text-lg">
                                <div className="flex items-center">
                                    <SlidersHorizontal
                                        size={20}
                                        className="mr-2 text-sky-600"
                                    />
                                    Filter Misi
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
                                    Status
                                </label>
                                <Select
                                    value={filters.status}
                                    onValueChange={(value) =>
                                        handleFilterChange('status', value)
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="semua">
                                            Semua Status
                                        </SelectItem>
                                        <SelectItem value="open">
                                            Dibuka
                                        </SelectItem>
                                        <SelectItem value="on-progress">
                                            Sedang Berlangsung
                                        </SelectItem>
                                        <SelectItem value="completed">
                                            Selesai
                                        </SelectItem>

                                        <SelectItem value="cancelled">
                                            Dibatalkan
                                        </SelectItem>
                                        <SelectItem value="under-authority">
                                            Misi Pihak Berwenang
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
                                        handleFilterChange('province', value)
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
                                    Rentang Awal Tanggal
                                </label>
                                <input
                                    type="date"
                                    className="w-full px-2 py-2 border border-gray-200 rounded-md"
                                    value={filters.startDate}
                                    onChange={(e) =>
                                        handleFilterChange(
                                            'startDate',
                                            e.target.value,
                                        )
                                    }
                                />
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">
                                    Rentang Akhir Tanggal
                                </label>

                                <input
                                    type="date"
                                    className="w-full px-2 py-2 border border-gray-300 rounded"
                                    value={filters.endDate}
                                    onChange={(e) =>
                                        handleFilterChange(
                                            'endDate',
                                            e.target.value,
                                        )
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
                                    <SelectItem value="title">
                                        Judul A-Z
                                    </SelectItem>
                                    <SelectItem value="status">
                                        Status
                                    </SelectItem>
                                    <SelectItem value="location">
                                        Lokasi
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="relative w-full sm:w-64">
                            <Search className="absolute w-4 h-4 text-gray-400 left-3 top-3" />
                            <Input
                                placeholder="Cari misi..."
                                className="pl-10"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                            />
                        </div>
                    </div>

                    {filteredAndSortedMissions.length > 0 ? (
                        <>
                            <div className="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                                {filteredAndSortedMissions.map(
                                    (mission: Mission) => (
                                        <Card
                                            key={mission.id}
                                            className="transition-all duration-300 border-0 shadow-md cursor-pointer group hover:-translate-y-1 hover:shadow-lg"
                                            onClick={() =>
                                                onViewDetails(
                                                    mission.report?.id,
                                                )
                                            }
                                        >
                                            <div className="relative overflow-hidden rounded-t-lg">
                                                {mission.report?.media?.[0]?.media_type?.startsWith(
                                                    'video',
                                                ) ? (
                                                    <div className="relative w-full h-48 bg-black">
                                                        <video
                                                            className="object-cover w-full h-full opacity-50"
                                                            src={`/storage/${mission.report.media[0].media_url}`}
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
                                                        src={`/storage/${mission.thumbnail_url}`}
                                                        alt={mission.title}
                                                        className="object-cover w-full h-48 transition-transform duration-300 group-hover:scale-105"
                                                    />
                                                )}

                                                <div className="absolute right-3 top-3">
                                                    <Badge
                                                        className={getStatusColor(
                                                            mission.status,
                                                        )}
                                                    >
                                                        {getMissionStatusLabel(
                                                            mission.status,
                                                        )}
                                                    </Badge>
                                                </div>
                                            </div>

                                            <CardContent className="p-4">
                                                <h3 className="mb-2 font-semibold text-gray-900 transition-colors line-clamp-2 group-hover:text-sky-600">
                                                    {mission.title}
                                                </h3>
                                                <p className="mb-3 text-sm text-gray-600 line-clamp-2">
                                                    {mission.description}
                                                </p>
                                                <div className="flex items-center justify-between mb-3 text-xs text-gray-500">
                                                    <span>
                                                        Diverifikasi:{' '}
                                                        {mission.creator?.name}
                                                    </span>
                                                </div>
                                                <div className="flex items-center mb-3 text-sm text-gray-500">
                                                    <MapPin
                                                        size={14}
                                                        className="mr-1"
                                                    />
                                                    <span className="truncate">
                                                        {mission.district?.name}
                                                        , {mission.city?.name},{' '}
                                                        {mission.province?.name}
                                                    </span>
                                                </div>

                                                <div className="flex items-center justify-between mb-4">
                                                    <div className="flex items-center text-sm text-gray-500">
                                                        <span>
                                                            Pelaksanaan:{' '}
                                                            {formatFullDateTime(
                                                                mission.scheduled_date,
                                                            )}
                                                        </span>
                                                    </div>
                                                </div>

                                                <Button
                                                    className="w-full mt-auto transition-colors duration-200 bg-cyan-500 hover:bg-cyan-700"
                                                    onClick={(e) => {
                                                        e.stopPropagation();
                                                        Inertia.visit(
                                                            `/report/${mission.report.id}`,
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
                                    ),
                                )}
                            </div>
                        </>
                    ) : (
                        <div className="flex items-center justify-center w-full">
                            <Card className="w-full px-8 py-32 text-center">
                                <div className="flex justify-center mb-4">
                                    <div className="p-4 bg-gray-100 rounded-full">
                                        <Target
                                            size={32}
                                            className="w-12 h-12 text-gray-400"
                                        />
                                    </div>
                                </div>
                                <h3 className="mb-2 text-lg font-semibold text-gray-900">
                                    {hasActiveFilters
                                        ? 'Tidak Ada Misi yang Sesuai'
                                        : myMissions
                                          ? 'Anda Belum Memiliki Misi'
                                          : 'Misi Belum Tersedia'}
                                </h3>
                                <p className="text-sm text-gray-500">
                                    {hasActiveFilters
                                        ? 'Coba ubah filter atau kata kunci pencarian Anda.'
                                        : myMissions
                                          ? 'Anda belum memiliki misi. Silahkan mendaftar misi terlebih dahulu.'
                                          : 'Belum ada misi yang tersedia saat ini. Coba lagi nanti.'}
                                </p>
                                {hasActiveFilters && (
                                    <Button
                                        variant="outline"
                                        className="mt-4"
                                        onClick={resetFilters}
                                    >
                                        Reset Filter
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

export default MissionPage;
