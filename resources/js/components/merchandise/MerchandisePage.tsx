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
import { formatDateOnly } from '@/utils/formatDate';
import {
    Calendar,
    Gift,
    RefreshCcw,
    Search,
    ShoppingBag,
    SlidersHorizontal,
    Star,
} from 'lucide-react';
import { useMemo, useState } from 'react';
import { Badge } from '../ui/badge';
import ModalRedeemMerchandise from './ModalReedemMerchandise';

interface Merchandise {
    id: number;
    name: string;
    description: string;
    image_url: string;
    points_cost: number;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

interface MerchandisePageProps {
    merchandise: Merchandise[];
    userPoints: number;
}

interface FilterOptions {
    status: string;
    priceRange: string;
    startDate: string;
    endDate: string;
}

const MerchandisePage = ({ merchandise, userPoints }: MerchandisePageProps) => {
    const [sortBy, setSortBy] = useState('newest');
    const [searchQuery, setSearchQuery] = useState('');
    const [filters, setFilters] = useState<FilterOptions>({
        status: 'semua',
        priceRange: 'semua',
        startDate: '',
        endDate: '',
    });

    // Modal state - simplified
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedMerchandise, setSelectedMerchandise] =
        useState<Merchandise | null>(null);

    // Filter and sort merchandise
    const filteredAndSortedMerchandise = useMemo(() => {
        let filtered = merchandise;

        // Apply search filter
        if (searchQuery.trim()) {
            filtered = filtered.filter(
                (item) =>
                    item.name
                        ?.toLowerCase()
                        .includes(searchQuery.toLowerCase()) ||
                    item.description
                        ?.toLowerCase()
                        .includes(searchQuery.toLowerCase()),
            );
        }

        // Apply status filter
        if (filters.status !== 'semua') {
            filtered = filtered.filter((item) => {
                if (filters.status === 'active') {
                    return item.is_active;
                } else if (filters.status === 'inactive') {
                    return !item.is_active;
                }
                return true;
            });
        }

        // Apply price range filter
        if (filters.priceRange !== 'semua') {
            filtered = filtered.filter((item) => {
                const points = item.points_cost;
                switch (filters.priceRange) {
                    case 'low':
                        return points <= 100;
                    case 'medium':
                        return points > 100 && points <= 500;
                    case 'high':
                        return points > 500;
                    default:
                        return true;
                }
            });
        }
        if (filters.startDate) {
            filtered = filtered.filter((item) => {
                const itemDate = new Date(item.created_at);
                const startDate = new Date(filters.startDate);
                return itemDate >= startDate;
            });
        }

        if (filters.endDate) {
            filtered = filtered.filter((item) => {
                const itemDate = new Date(item.created_at);
                const endDate = new Date(filters.endDate);
                return itemDate <= endDate;
            });
        }
        const sorted = [...filtered].sort((a, b) => {
            switch (sortBy) {
                case 'newest':
                    return (
                        new Date(b.created_at).getTime() -
                        new Date(a.created_at).getTime()
                    );
                case 'oldest':
                    return (
                        new Date(a.created_at).getTime() -
                        new Date(b.created_at).getTime()
                    );
                case 'name':
                    return (a.name || '').localeCompare(b.name || '');
                case 'price-low':
                    return a.points_cost - b.points_cost;
                case 'price-high':
                    return b.points_cost - a.points_cost;
                default:
                    return 0;
            }
        });

        return sorted;
    }, [merchandise, searchQuery, filters, sortBy]);

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
            status: 'semua',
            priceRange: 'semua',
            startDate: '',
            endDate: '',
        });
        setSearchQuery('');
        setSortBy('newest');
    };

    // Handle modal functions
    const openModal = (item: Merchandise) => {
        setSelectedMerchandise(item);
        setIsModalOpen(true);
    };

    const closeModal = () => {
        setIsModalOpen(false);
        setSelectedMerchandise(null);
    };

    // const handleModalConfirm = async (merchandiseId: number, address: string) => {
    //     await onPurchase(merchandiseId, address);
    // };

    // Check if any filters are active
    const hasActiveFilters =
        searchQuery.trim() ||
        filters.status !== 'semua' ||
        filters.priceRange !== 'semua' ||
        filters.startDate ||
        filters.endDate;

    return (
        <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div className="mb-8 flex flex-col items-start justify-between md:flex-row md:items-center">
                <div>
                    <h1 className="mb-2 text-3xl font-bold text-gray-900">
                        Toko Merchandise
                    </h1>
                    <p className="text-gray-600">
                        Tukarkan poin Anda dengan merchandise eksklusif dan
                        jadilah bagian dari gerakan lingkungan
                    </p>
                </div>
                <div className="mt-4 md:mt-0">
                    <div className="flex items-center rounded-lg bg-yellow-100 px-4 py-2">
                        <Star className="mr-2 h-5 w-5 text-yellow-800" />
                        <span className="font-semibold text-yellow-800">
                            Poin Saya: {userPoints.toLocaleString('id-ID')} Poin
                        </span>
                    </div>
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
                                        className="mr-2 text-emerald-600"
                                    />
                                    Filter Merchandise
                                </div>
                                {hasActiveFilters && (
                                    <span className="rounded-full bg-emerald-100 px-2 py-1 text-xs text-emerald-600">
                                        Aktif
                                    </span>
                                )}
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">
                                    Rentang Harga (Poin)
                                </label>
                                <Select
                                    value={filters.priceRange}
                                    onValueChange={(value) =>
                                        handleFilterChange('priceRange', value)
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih rentang harga" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="semua">
                                            Semua Harga
                                        </SelectItem>
                                        <SelectItem value="low">
                                            ≤ 100 Poin
                                        </SelectItem>
                                        <SelectItem value="medium">
                                            101 - 500 Poin
                                        </SelectItem>
                                        <SelectItem value="high">
                                            + 500 Poin
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="space-y-2 pt-4">
                                <Button
                                    variant="outline"
                                    className="flex w-full items-center justify-center gap-2"
                                    onClick={resetFilters}
                                    disabled={!hasActiveFilters}
                                >
                                    <RefreshCcw className="h-4 w-4" />
                                    Reset Filter
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Main Content */}
                <div className="lg:col-span-3">
                    {/* Sort and Search */}
                    <div className="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
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
                                    <SelectItem value="name">
                                        Nama A-Z
                                    </SelectItem>
                                    <SelectItem value="price-low">
                                        Harga Terendah
                                    </SelectItem>
                                    <SelectItem value="price-high">
                                        Harga Tertinggi
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="relative w-full sm:w-64">
                            <Search className="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                            <Input
                                placeholder="Cari merchandise..."
                                className="pl-10"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                            />
                        </div>
                    </div>

                    {filteredAndSortedMerchandise.length > 0 ? (
                        <>
                            <div className="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                                {filteredAndSortedMerchandise.map(
                                    (item: Merchandise) => (
                                        <Card
                                            key={item.id}
                                            className="group cursor-pointer border-0 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                                        >
                                            <div className="relative overflow-hidden rounded-t-lg">
                                                <img
                                                    src={`/storage/${item.image_url}`}
                                                    alt={item.name}
                                                    className="h-48 w-full object-contain transition-transform duration-300 group-hover:scale-105"
                                                />
                                                <div className="absolute right-3 top-3">
                                                    <Badge
                                                        className={
                                                            item.is_active
                                                                ? 'bg-green-100 text-green-800 hover:bg-green-200'
                                                                : 'bg-red-100 text-red-800 hover:bg-red-200'
                                                        }
                                                    >
                                                        {item.is_active
                                                            ? 'Tersedia'
                                                            : 'Habis'}
                                                    </Badge>
                                                </div>
                                                {userPoints <
                                                    item.points_cost && (
                                                    <div className="absolute left-3 top-3">
                                                        <Badge className="bg-yellow-100 text-yellow-800">
                                                            Poin Kurang
                                                        </Badge>
                                                    </div>
                                                )}
                                            </div>

                                            <CardContent className="p-4">
                                                <h3 className="mb-2 line-clamp-2 font-semibold text-gray-900 transition-colors group-hover:text-emerald-600">
                                                    {item.name}
                                                </h3>
                                                <p className="mb-3 line-clamp-2 text-sm text-gray-600">
                                                    {item.description}
                                                </p>

                                                <div className="mb-3 flex items-center justify-between">
                                                    <div className="flex items-center text-lg font-bold text-emerald-600">
                                                        <Star
                                                            size={16}
                                                            className="mr-1 fill-current"
                                                        />
                                                        {item.points_cost.toLocaleString(
                                                            'id-ID',
                                                        )}{' '}
                                                        Poin
                                                    </div>
                                                </div>

                                                <div className="mb-4 flex items-center text-sm text-gray-500">
                                                    <Calendar
                                                        size={14}
                                                        className="mr-1"
                                                    />
                                                    <span>
                                                        Ditambahkan:{' '}
                                                        {formatDateOnly(
                                                            item.created_at,
                                                        )}
                                                    </span>
                                                </div>

                                                <div className="flex gap-2">
                                                    {/* <Button
                                                        variant="outline"
                                                        className="flex-1"
                                                        onClick={(e) => {
                                                            e.stopPropagation();
                                                            onViewDetails(
                                                                item.id,
                                                            );
                                                        }}
                                                    >
                                                        <Eye
                                                            size={16}
                                                            className="mr-2"
                                                        />
                                                        Detail
                                                    </Button> */}
                                                    <Button
                                                        className="flex-1 bg-amber-600 transition-colors duration-200 hover:bg-amber-700 disabled:bg-gray-400"
                                                        onClick={(e) => {
                                                            e.stopPropagation();
                                                            openModal(item);
                                                        }}
                                                        disabled={
                                                            !item.is_active ||
                                                            userPoints <
                                                                item.points_cost
                                                        }
                                                    >
                                                        <ShoppingBag
                                                            size={16}
                                                            className="mr-2"
                                                        />
                                                        {!item.is_active
                                                            ? 'Habis'
                                                            : userPoints <
                                                                item.points_cost
                                                              ? 'Poin Kurang'
                                                              : 'Tukar'}
                                                    </Button>
                                                </div>
                                            </CardContent>
                                        </Card>
                                    ),
                                )}
                            </div>
                        </>
                    ) : (
                        <div className="flex w-full items-center justify-center">
                            <Card className="w-full px-8 py-32 text-center">
                                <div className="mb-4 flex justify-center">
                                    <div className="rounded-full bg-gray-100 p-4">
                                        <Gift
                                            size={32}
                                            className="h-12 w-12 text-gray-400"
                                        />
                                    </div>
                                </div>
                                <h3 className="mb-2 text-lg font-semibold text-gray-900">
                                    {hasActiveFilters
                                        ? 'Tidak Ada Merchandise yang Sesuai'
                                        : 'Merchandise Belum Tersedia'}
                                </h3>
                                <p className="text-sm text-gray-500">
                                    {hasActiveFilters
                                        ? 'Coba ubah filter atau kata kunci pencarian Anda.'
                                        : 'Belum ada merchandise yang tersedia saat ini. Coba lagi nanti.'}
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

            {/* Modal Component */}
            <ModalRedeemMerchandise
                isOpen={isModalOpen}
                onClose={closeModal}
                merchandise={selectedMerchandise}
                userPoints={userPoints}
            />
        </div>
    );
};

export default MerchandisePage;
