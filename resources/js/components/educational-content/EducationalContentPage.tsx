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
import { Content } from '@/types/content';
import { getContentTypeLabel } from '@/utils/contentTypeLabel';
import { getTypeColor } from '@/utils/educationColor';
import { formatDateOnly } from '@/utils/formatDate';
import { router as Inertia } from '@inertiajs/react';
import {
    Calendar,
    Eye,
    Play,
    RefreshCcw,
    Search,
    SlidersHorizontal,
} from 'lucide-react';
import { useEffect, useState } from 'react';
import RenderHTML from '../RenderHtml';
import { Badge } from '../ui/badge';
interface EducationalContentPageProps {
    contents: Content[];
    onViewDetails: (id: number) => void;
}

interface FilterState {
    content_type: string;
}

const EducationalContentPage = ({
    onViewDetails,
    contents,
}: EducationalContentPageProps) => {
    const [sortBy, setSortBy] = useState('newest');
    const [searchQuery, setSearchQuery] = useState('');
    const [filteredContents, setFilteredContents] =
        useState<Content[]>(contents);

    // State untuk semua filter
    const [filters, setFilters] = useState<FilterState>({
        content_type: 'semua',
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
            content_type: 'semua',
        });
        setSearchQuery('');
    };

    // Function untuk apply semua filter dan sorting
    useEffect(() => {
        let processedContents = [...contents];
        // Apply search filter
        if (searchQuery.trim()) {
            processedContents = processedContents.filter((content) =>
                content.title.toLowerCase().includes(searchQuery.toLowerCase()),
            );
        }
        // Apply type filter
        if (filters.content_type !== 'semua') {
            processedContents = processedContents.filter(
                (content) =>
                    content.content_type.toLowerCase() ===
                    filters.content_type.toLowerCase(),
            );
        }
        // Apply sorting
        if (sortBy === 'newest') {
            processedContents.sort(
                (a, b) =>
                    new Date(b.created_at).getTime() -
                    new Date(a.created_at).getTime(),
            );
        } else if (sortBy === 'oldest') {
            processedContents.sort(
                (a, b) =>
                    new Date(a.created_at).getTime() -
                    new Date(b.created_at).getTime(),
            );
        } else if (sortBy === 'title') {
            processedContents.sort((a, b) => a.title.localeCompare(b.title));
        }
        setFilteredContents(processedContents);
    }, [contents, searchQuery, sortBy, filters]);

    const availableContentTypes = [
        { label: 'Artikel', value: 'artikel' },
        { label: 'Modul PDF', value: 'modul' },
        { label: 'Video', value: 'video' },
    ];

    const hasActiveFilters =
        searchQuery.trim() || filters.content_type !== 'semua';
    return (
        <div className="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div className="mb-8">
                <h1 className="mb-2 text-3xl font-bold text-gray-900">
                    Konten Edukasi
                </h1>
                <p className="text-gray-600">
                    Pelajari berbagai topik lingkungan untuk meningkatkan
                    kesadaran Anda
                </p>
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
                                    Filter Konten
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
                                    Tipe Konten
                                </label>
                                <Select
                                    value={filters.content_type}
                                    onValueChange={(value) =>
                                        updateFilter('content_type', value)
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih tipe" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="semua">
                                            Semua Tipe
                                        </SelectItem>
                                        {availableContentTypes.map(
                                            (content) => (
                                                <SelectItem
                                                    key={content.value}
                                                    value={content.value}
                                                >
                                                    {content.label}
                                                </SelectItem>
                                            ),
                                        )}
                                    </SelectContent>
                                </Select>
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
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="relative w-full sm:w-64">
                            <Search className="absolute w-4 h-4 text-gray-400 left-3 top-3" />
                            <Input
                                placeholder="Cari konten..."
                                className="pl-10"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                            />
                        </div>
                    </div>
                    {/* Active Filters Display */}
                    {(filters.content_type !== 'semua' || searchQuery) && (
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
                            {filters.content_type !== 'semua' && (
                                <Badge
                                    variant="secondary"
                                    className="flex items-center gap-1"
                                >
                                    Kategori: {filters.content_type}
                                    <button
                                        onClick={() =>
                                            updateFilter(
                                                'content_type',
                                                'semua',
                                            )
                                        }
                                        className="ml-1 text-xs"
                                    >
                                        ×
                                    </button>
                                </Badge>
                            )}
                        </div>
                    )}
                    {filteredContents.length > 0 ? (
                        <>
                            <div className="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                                {filteredContents.map((content: Content) => (
                                    <Card
                                        key={content.id}
                                        className="transition-all duration-300 border-0 shadow-md cursor-pointer group hover:-translate-y-1 hover:shadow-lg"
                                        onClick={() =>
                                            onViewDetails(content.id)
                                        }
                                    >
                                        <div className="relative overflow-hidden rounded-t-lg">
                                            {content.media?.[0]?.media_type?.startsWith(
                                                'video',
                                            ) ? (
                                                <div className="relative w-full h-48 bg-black">
                                                    <video
                                                        className="object-cover w-full h-full opacity-50"
                                                        src={`/storage/${content.media[0].media_url}`}
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
                                            ) : content.media?.[0]
                                                  ?.media_type ===
                                              'document' ? (
                                                <div className="flex items-center justify-center w-full h-48 bg-gray-100">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        className="w-12 h-12 text-sky-600"
                                                        viewBox="0 0 24 24"
                                                        fill="currentColor"
                                                    >
                                                        <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6H6zm7 1.5L18.5 9H13V3.5zM8 13h8v2H8v-2zm0 4h5v2H8v-2z" />
                                                    </svg>
                                                </div>
                                            ) : (
                                                <img
                                                    src={`/storage/${content.media?.[0]?.media_url}`}
                                                    alt={content.title}
                                                    className="object-cover w-full h-48 transition-transform duration-300 group-hover:scale-105"
                                                />
                                            )}

                                            <div className="absolute right-3 top-3">
                                                <Badge
                                                    className={getTypeColor(
                                                        content.content_type,
                                                    )}
                                                >
                                                    <div className="flex items-center gap-1">
                                                        {getContentTypeLabel(
                                                            content.content_type,
                                                        )}
                                                    </div>
                                                </Badge>
                                            </div>

                                            {content.content_type ===
                                                'Video' && (
                                                <div className="absolute inset-0 flex items-center justify-center transition-opacity opacity-0 bg-black/20 group-hover:opacity-100">
                                                    <div className="flex items-center justify-center w-16 h-16 rounded-full bg-white/90">
                                                        <Play
                                                            size={24}
                                                            className="ml-1 text-sky-600"
                                                        />
                                                    </div>
                                                </div>
                                            )}
                                        </div>
                                        <CardContent className="p-4">
                                            <h3 className="mb-2 font-semibold text-gray-900 transition-colors line-clamp-2 group-hover:text-sky-600">
                                                {content.title}
                                            </h3>
                                            <div className="mb-3 text-sm text-gray-600 line-clamp-2">
                                                <RenderHTML
                                                    htmlString={content.body}
                                                    className="leading-relaxed text-gray-700"
                                                />
                                            </div>
                                            <div className="flex items-center justify-between mb-3 text-xs text-gray-500">
                                                <span>
                                                    Oleh: {content.author?.name}
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
                                                            content.created_at,
                                                        )}
                                                    </span>
                                                </div>
                                            </div>

                                            <Button
                                                className="w-full mt-auto transition-colors duration-200 bg-cyan-500 hover:bg-cyan-700"
                                                onClick={() =>
                                                    Inertia.visit(
                                                        `/content/${content.id}`,
                                                    )
                                                }
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
                                    filters.content_type !== 'semua'
                                        ? 'Tidak Ada Konten Edukasi yang Sesuai Filter'
                                        : 'Konten Edukasi Belum Tersedia'}
                                </h3>
                                <p className="text-sm text-gray-500">
                                    {searchQuery ||
                                    filters.content_type !== 'semua'
                                        ? 'Coba ubah atau hapus beberapa filter untuk melihat lebih banyak konten edukasi.'
                                        : 'Belum ada konten edukasi yang tersedia saat ini.'}
                                </p>
                                {(searchQuery ||
                                    filters.content_type !== 'semua') && (
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

export default EducationalContentPage;
