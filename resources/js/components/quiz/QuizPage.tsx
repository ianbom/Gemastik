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
import {
    Clock,
    Play,
    RefreshCcw,
    Search,
    SlidersHorizontal,
    Target,
    Trophy,
} from 'lucide-react';
import { useEffect, useState } from 'react';
import { Badge } from '../ui/badge';

interface Quiz {
    id: number;
    title: string;
    description?: string;
    thumbnail_url?: string;
    difficulty: 'mudah' | 'sedang' | 'sulit';
    points_reward: number;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

interface QuizzesPageProps {
    quizzes: Quiz[];
    onViewDetails: (id: number) => void;
    onCreateQuiz?: () => void;
    canCreate?: boolean;
}

interface FilterState {
    difficulty: string;
    status: string;
    minPoints: string;
    maxPoints: string;
}

const QuizzesPage = ({
    quizzes,
    onViewDetails,
    onCreateQuiz,
    canCreate = false,
}: QuizzesPageProps) => {
    const [sortBy, setSortBy] = useState('newest');
    const [searchQuery, setSearchQuery] = useState('');
    const [filteredQuizzes, setFilteredQuizzes] = useState<Quiz[]>(quizzes);

    // State untuk semua filter
    const [filters, setFilters] = useState<FilterState>({
        difficulty: 'semua',
        status: 'semua',
        minPoints: '',
        maxPoints: '',
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
            difficulty: 'semua',
            status: 'semua',
            minPoints: '',
            maxPoints: '',
        });
        setSearchQuery('');
    };

    // Function untuk apply semua filter dan sorting
    useEffect(() => {
        let processedQuizzes = [...quizzes];

        // Apply search filter
        if (searchQuery.trim()) {
            processedQuizzes = processedQuizzes.filter(
                (quiz) =>
                    quiz.title
                        .toLowerCase()
                        .includes(searchQuery.toLowerCase()) ||
                    quiz.description
                        ?.toLowerCase()
                        .includes(searchQuery.toLowerCase()),
            );
        }

        // Apply difficulty filter
        if (filters.difficulty !== 'semua') {
            processedQuizzes = processedQuizzes.filter(
                (quiz) =>
                    quiz.difficulty.toLowerCase() ===
                    filters.difficulty.toLowerCase(),
            );
        }

        // Apply status filter
        if (filters.status !== 'semua') {
            if (filters.status === 'aktif') {
                processedQuizzes = processedQuizzes.filter(
                    (quiz) => quiz.is_active,
                );
            } else if (filters.status === 'nonaktif') {
                processedQuizzes = processedQuizzes.filter(
                    (quiz) => !quiz.is_active,
                );
            }
        }

        // Apply points range filter
        if (filters.minPoints) {
            const minPoints = parseInt(filters.minPoints);
            processedQuizzes = processedQuizzes.filter(
                (quiz) => quiz.points_reward >= minPoints,
            );
        }

        if (filters.maxPoints) {
            const maxPoints = parseInt(filters.maxPoints);
            processedQuizzes = processedQuizzes.filter(
                (quiz) => quiz.points_reward <= maxPoints,
            );
        }

        // Apply sorting
        if (sortBy === 'newest') {
            processedQuizzes.sort(
                (a, b) =>
                    new Date(b.created_at).getTime() -
                    new Date(a.created_at).getTime(),
            );
        } else if (sortBy === 'oldest') {
            processedQuizzes.sort(
                (a, b) =>
                    new Date(a.created_at).getTime() -
                    new Date(b.created_at).getTime(),
            );
        } else if (sortBy === 'points') {
            processedQuizzes.sort((a, b) => b.points_reward - a.points_reward);
        } else if (sortBy === 'difficulty') {
            const difficultyOrder = { mudah: 1, sedang: 2, sulit: 3 };
            processedQuizzes.sort(
                (a, b) =>
                    difficultyOrder[a.difficulty] -
                    difficultyOrder[b.difficulty],
            );
        } else if (sortBy === 'title') {
            processedQuizzes.sort((a, b) => a.title.localeCompare(b.title));
        }

        setFilteredQuizzes(processedQuizzes);
    }, [quizzes, searchQuery, sortBy, filters]);

    const getDifficultyColor = (difficulty: string) => {
        switch (difficulty) {
            case 'mudah':
                return 'bg-green-100 text-green-700 border-green-200';
            case 'sedang':
                return 'bg-yellow-100 text-yellow-700 border-yellow-200';
            case 'sulit':
                return 'bg-red-100 text-red-700 border-red-200';
            default:
                return 'bg-gray-100 text-gray-700 border-gray-200';
        }
    };

    const getDifficultyLabel = (difficulty: string) => {
        switch (difficulty) {
            case 'mudah':
                return 'Mudah';
            case 'sedang':
                return 'Sedang';
            case 'sulit':
                return 'Sulit';
            default:
                return difficulty;
        }
    };

    const hasActiveFilters =
        searchQuery.trim() ||
        filters.difficulty !== 'semua' ||
        filters.status !== 'semua' ||
        filters.minPoints ||
        filters.maxPoints;

    return (
        <div className="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div className="flex flex-col items-start justify-between mb-8 md:flex-row md:items-center">
                <div>
                    <h1 className="mb-2 text-3xl font-bold text-gray-900">
                        Daftar Kuis
                    </h1>
                    <p className="text-gray-600">
                        Asah pengetahuan lingkungan Anda dan dapatkan poin
                        reward
                    </p>
                </div>
                {canCreate && onCreateQuiz && (
                    <Button
                        onClick={onCreateQuiz}
                        className="mt-4 text-white bg-sky-700 hover:bg-sky-800 md:mt-0"
                        size="lg"
                    >
                        <Target size={20} className="mr-2" />
                        Buat Kuis Baru
                    </Button>
                )}
            </div>

            <div className="grid grid-cols-1 gap-8 lg:grid-cols-4">
                {/* Filter Sidebar */}
                <div className="lg:col-span-1">
                    <Card className="sticky overflow-visible top-24">
                        <CardHeader>
                            <CardTitle className="flex items-center justify-between text-lg">
                                <div className="flex items-center">
                                    <SlidersHorizontal
                                        size={20}
                                        className="mr-2 text-sky-600"
                                    />
                                    Filter Kuis
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
                                    Tingkat Kesulitan
                                </label>
                                <Select
                                    value={filters.difficulty}
                                    onValueChange={(value) =>
                                        updateFilter('difficulty', value)
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih kesulitan" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="semua">
                                            Semua Tingkat
                                        </SelectItem>
                                        <SelectItem value="mudah">
                                            Mudah
                                        </SelectItem>
                                        <SelectItem value="sedang">
                                            Sedang
                                        </SelectItem>
                                        <SelectItem value="sulit">
                                            Sulit
                                        </SelectItem>
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
                                        <SelectItem value="aktif">
                                            Aktif
                                        </SelectItem>
                                        <SelectItem value="nonaktif">
                                            Non-aktif
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">
                                    Poin Minimum
                                </label>
                                <Input
                                    type="number"
                                    placeholder="0"
                                    value={filters.minPoints}
                                    onChange={(e) =>
                                        updateFilter(
                                            'minPoints',
                                            e.target.value,
                                        )
                                    }
                                />
                            </div>

                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">
                                    Poin Maksimum
                                </label>
                                <Input
                                    type="number"
                                    placeholder="100"
                                    value={filters.maxPoints}
                                    onChange={(e) =>
                                        updateFilter(
                                            'maxPoints',
                                            e.target.value,
                                        )
                                    }
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
                                    <SelectItem value="points">
                                        Poin Tertinggi
                                    </SelectItem>
                                    <SelectItem value="difficulty">
                                        Kesulitan
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
                                placeholder="Cari kuis..."
                                className="pl-10"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                            />
                        </div>
                    </div>

                    {/* Active Filters Display */}
                    {(filters.difficulty !== 'semua' ||
                        filters.status !== 'semua' ||
                        filters.minPoints ||
                        filters.maxPoints ||
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
                            {filters.difficulty !== 'semua' && (
                                <Badge
                                    variant="secondary"
                                    className="flex items-center gap-1"
                                >
                                    Kesulitan:{' '}
                                    {getDifficultyLabel(filters.difficulty)}
                                    <button
                                        onClick={() =>
                                            updateFilter('difficulty', 'semua')
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
                            {(filters.minPoints || filters.maxPoints) && (
                                <Badge
                                    variant="secondary"
                                    className="flex items-center gap-1"
                                >
                                    Poin: {filters.minPoints || '0'} -{' '}
                                    {filters.maxPoints || '∞'}
                                    <button
                                        onClick={() => {
                                            updateFilter('minPoints', '');
                                            updateFilter('maxPoints', '');
                                        }}
                                        className="ml-1 text-xs"
                                    >
                                        ×
                                    </button>
                                </Badge>
                            )}
                        </div>
                    )}

                    {filteredQuizzes.length > 0 ? (
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                            {filteredQuizzes.map((quiz: Quiz) => (
                                <Card
                                    key={quiz.id}
                                    className="transition-all duration-300 border-0 shadow-md cursor-pointer group hover:-translate-y-1 hover:shadow-lg"
                                    onClick={() => onViewDetails(quiz.id)}
                                >
                                    <div className="relative overflow-hidden rounded-t-lg">
                                        {quiz.thumbnail_url ? (
                                            <img
                                                src={
                                                    quiz.thumbnail_url.startsWith(
                                                        '/storage/',
                                                    )
                                                        ? quiz.thumbnail_url
                                                        : `/storage/${quiz.thumbnail_url}`
                                                }
                                                alt={quiz.title}
                                                className="object-cover w-full h-48 transition-transform duration-300 group-hover:scale-105"
                                            />
                                        ) : (
                                            <div className="flex items-center justify-center w-full h-48 bg-gradient-to-br from-sky-400 to-sky-600">
                                                <Target className="w-16 h-16 text-white opacity-80" />
                                            </div>
                                        )}

                                        <div className="absolute right-3 top-3">
                                            <Badge
                                                className={getDifficultyColor(
                                                    quiz.difficulty,
                                                )}
                                            >
                                                {getDifficultyLabel(
                                                    quiz.difficulty,
                                                )}
                                            </Badge>
                                        </div>

                                        {!quiz.is_active && (
                                            <div className="absolute left-3 top-3">
                                                <Badge className="text-gray-700 bg-gray-100">
                                                    Non-aktif
                                                </Badge>
                                            </div>
                                        )}
                                    </div>

                                    <CardContent className="p-4">
                                        <h3 className="mb-2 font-semibold text-gray-900 transition-colors line-clamp-2 group-hover:text-sky-600">
                                            {quiz.title}
                                        </h3>

                                        {quiz.description && (
                                            <p className="mb-3 text-sm text-gray-600 line-clamp-2">
                                                {quiz.description}
                                            </p>
                                        )}

                                        <div className="flex items-center justify-between mb-4">
                                            <div className="flex items-center text-sm text-yellow-600">
                                                <Trophy
                                                    size={14}
                                                    className="mr-1"
                                                />
                                                <span className="font-medium">
                                                    {quiz.points_reward} poin
                                                </span>
                                            </div>
                                            <div className="flex items-center text-sm text-gray-500">
                                                <Clock
                                                    size={14}
                                                    className="mr-1"
                                                />
                                                <span>
                                                    {new Date(
                                                        quiz.created_at,
                                                    ).toLocaleDateString(
                                                        'id-ID',
                                                    )}
                                                </span>
                                            </div>
                                        </div>

                                        <Button
                                            className="w-full mt-auto transition-colors duration-200 bg-cyan-600 hover:bg-cyan-700"
                                            onClick={(e) => {
                                                e.stopPropagation();
                                                onViewDetails(quiz.id);
                                            }}
                                            disabled={!quiz.is_active}
                                        >
                                            <Play size={16} className="mr-2" />
                                            {quiz.is_active
                                                ? 'Mulai Kuis'
                                                : 'Kuis Non-aktif'}
                                        </Button>
                                    </CardContent>
                                </Card>
                            ))}
                        </div>
                    ) : (
                        <div className="flex items-center justify-center w-full">
                            <Card className="w-full px-8 py-32 text-center">
                                <div className="flex justify-center mb-4">
                                    <div className="p-4 bg-gray-100 rounded-full">
                                        <Target className="w-12 h-12 text-gray-400" />
                                    </div>
                                </div>
                                <h3 className="mb-2 text-lg font-semibold text-gray-900">
                                    {searchQuery ||
                                    filters.difficulty !== 'semua' ||
                                    filters.status !== 'semua' ||
                                    filters.minPoints ||
                                    filters.maxPoints
                                        ? 'Tidak Ada Kuis yang Sesuai Filter'
                                        : 'Kuis Belum Tersedia'}
                                </h3>
                                <p className="text-sm text-gray-500">
                                    {searchQuery ||
                                    filters.difficulty !== 'semua' ||
                                    filters.status !== 'semua' ||
                                    filters.minPoints ||
                                    filters.maxPoints
                                        ? 'Coba ubah atau hapus beberapa filter untuk melihat lebih banyak kuis.'
                                        : 'Belum ada kuis yang tersedia saat ini. Silakan cek kembali nanti.'}
                                </p>
                                {(searchQuery ||
                                    filters.difficulty !== 'semua' ||
                                    filters.status !== 'semua' ||
                                    filters.minPoints ||
                                    filters.maxPoints) && (
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

export default QuizzesPage;
