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
import { QuizAttempt } from '@/types/quiz/attempt';
import { User } from '@/types/user/interface';
import { formatFullDateTime } from '@/utils/formatDate';
import { getDifficultyColor } from '@/utils/quiz/getDifficultyColor';
import { getScoreBadgeColor } from '@/utils/quiz/getScoreBadgeColor';
import { getScoreColor } from '@/utils/quiz/getScoreColor';
import {
    CheckCircle,
    Clock,
    Lightbulb,
    RefreshCcw,
    RotateCcw,
    SlidersHorizontal,
    Trophy,
} from 'lucide-react';
import { useState } from 'react';

interface MyQuizPageProps {
    auth: {
        user: User;
    };
    myQuizAttempts: QuizAttempt[];
}

const MyQuizPage = ({ myQuizAttempts }: MyQuizPageProps) => {
    console.log('myQuizAttempts:', myQuizAttempts);
    const displayQuizzes =
        myQuizAttempts.length > 0 ? myQuizAttempts : myQuizAttempts;

    const [searchQuery, setSearchQuery] = useState('');
    const [categoryFilter, setCategoryFilter] = useState('all');
    const [difficultyFilter, setDifficultyFilter] = useState('all');
    const [scoreFilter, setScoreFilter] = useState('all');

    const filteredQuizzes = displayQuizzes.filter((quiz) => {
        const matchesSearch = quiz.quiz.title
            .toLowerCase()
            .includes(searchQuery.toLowerCase());
        const matchesDifficulty =
            difficultyFilter === 'all' ||
            quiz.quiz.difficulty === difficultyFilter;

        let matchesScore = true;
        if (scoreFilter === 'perfect') matchesScore = quiz.score === 100;
        else if (scoreFilter === 'good')
            matchesScore = quiz.score >= 80 && quiz.score < 100;
        else if (scoreFilter === 'fair')
            matchesScore = quiz.score >= 60 && quiz.score < 80;
        else if (scoreFilter === 'poor') matchesScore = quiz.score < 60;
        return matchesSearch && matchesDifficulty && matchesScore;
    });

    const resetFilters = () => {
        setSearchQuery('');
        setCategoryFilter('all');
        setDifficultyFilter('all');
        setScoreFilter('all');
    };

    const hasActiveFilters =
        searchQuery.trim() ||
        categoryFilter !== 'all' ||
        difficultyFilter !== 'all' ||
        scoreFilter !== 'all';

    const retakeQuiz = (quizId: number) => {
        console.log(`Retaking quiz with ID: ${quizId}`);
        window.location.href = `/quiz/${quizId}`;
    };

    return (
        <div className="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div className="flex flex-col justify-between gap-4 mb-8 sm:flex-row sm:items-center">
                <div>
                    <h1 className="mb-1 text-2xl font-bold text-gray-900 sm:text-3xl">
                        Riwayat Kuis Yang Dikerjakan
                    </h1>
                    <p className="text-gray-600">
                        Berikut adalah riwayat kuis yang telah Anda kerjakan.
                        Kerjakan ulang kuis untuk meningkatkan skor dan
                        mendapatkan poin!
                    </p>
                    <div className="flex items-center gap-4 mt-2 text-sm text-gray-500">
                        <span>
                            Total Kuis Dikerjakan: {displayQuizzes.length}
                        </span>
                        <span>
                            Skor Rata-rata:{' '}
                            {Math.round(
                                displayQuizzes.reduce(
                                    (acc, quiz) => acc + quiz.score,
                                    0,
                                ) / displayQuizzes.length,
                            ) || 0}
                        </span>
                        <span>
                            Perfect Score:{' '}
                            {
                                displayQuizzes.filter((q) => q.score === 100)
                                    .length
                            }
                        </span>
                    </div>
                </div>
                <div className="flex items-center gap-3">
                    <Button
                        className="bg-sky-600 hover:bg-sky-700"
                        onClick={() => (window.location.href = '/quiz')}
                    >
                        Cari Kuis Baru
                    </Button>
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
                                Filter Kuis
                            </div>
                            {hasActiveFilters && (
                                <Badge
                                    variant="secondary"
                                    className="bg-emerald-100 text-emerald-600"
                                >
                                    Filter Aktif
                                </Badge>
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
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div className="space-y-2">
                            <label className="text-sm font-medium">
                                Tingkat Kesulitan
                            </label>
                            <Select
                                value={difficultyFilter}
                                onValueChange={setDifficultyFilter}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Semua Tingkat" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">
                                        Semua Tingkat
                                    </SelectItem>
                                    <SelectItem value="mudah">Mudah</SelectItem>
                                    <SelectItem value="sedang">
                                        Sedang
                                    </SelectItem>
                                    <SelectItem value="sulit">Sulit</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="space-y-2">
                            <label className="text-sm font-medium">Skor</label>
                            <Select
                                value={scoreFilter}
                                onValueChange={setScoreFilter}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Semua Skor" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">
                                        Semua Skor
                                    </SelectItem>
                                    <SelectItem value="perfect">
                                        Perfect (100)
                                    </SelectItem>
                                    <SelectItem value="good">
                                        Bagus (80-99)
                                    </SelectItem>
                                    <SelectItem value="fair">
                                        Cukup (60-79)
                                    </SelectItem>
                                    <SelectItem value="poor">
                                        Perlu Diperbaiki (&lt;60)
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="space-y-2">
                            <label className="text-sm font-medium">
                                Pencarian Kuis
                            </label>
                            <Input
                                type="text"
                                placeholder="Cari berdasarkan judul kuis..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                            />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <div className="space-y-4">
                {filteredQuizzes.map((attempt) => (
                    <Card
                        key={attempt.id}
                        className="transition-shadow hover:shadow-md"
                    >
                        <CardContent className="p-6">
                            <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div className="flex-1">
                                    <div className="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 className="mb-2 text-xl font-semibold text-gray-800">
                                                {attempt.quiz.title}
                                            </h3>
                                            <div className="flex items-center gap-2 mb-2">
                                                {attempt.quiz.difficulty && (
                                                    <Badge
                                                        className={`text-xs ${getDifficultyColor(attempt.quiz.difficulty)}`}
                                                    >
                                                        {attempt.quiz
                                                            .difficulty ===
                                                        'mudah'
                                                            ? 'Mudah'
                                                            : attempt.quiz
                                                                    .difficulty ===
                                                                'sedang'
                                                              ? 'Sedang'
                                                              : 'Sulit'}
                                                    </Badge>
                                                )}
                                                <Badge
                                                    className={`text-xs ${getScoreBadgeColor(attempt.score)}`}
                                                >
                                                    {attempt.score === 100
                                                        ? 'Perfect!'
                                                        : attempt.score >= 80
                                                          ? 'Bagus'
                                                          : attempt.score >= 60
                                                            ? 'Cukup'
                                                            : 'Perlu Diperbaiki'}
                                                </Badge>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="grid grid-cols-2 gap-4 text-sm text-gray-600 lg:grid-cols-3">
                                        <div className="flex items-center gap-2">
                                            <Clock className="w-4 h-4 text-emerald-600" />
                                            <span>
                                                {formatFullDateTime(
                                                    attempt.created_at,
                                                )}
                                            </span>
                                        </div>
                                        {attempt.total_questions && (
                                            <div className="flex items-center gap-2">
                                                <Lightbulb className="w-4 h-4 text-yellow-600" />
                                                <span>
                                                    Soal:{' '}
                                                    {attempt.total_questions}{' '}
                                                    pertanyaan
                                                </span>
                                            </div>
                                        )}
                                        {attempt.score === 100 && (
                                            <div className="flex items-center gap-2">
                                                <Trophy className="w-4 h-4 text-purple-600" />
                                                <span>
                                                    Poin: +
                                                    {attempt.quiz.points_reward}
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div className="flex flex-col items-end gap-3 lg:min-w-[200px]">
                                    <div className="text-right">
                                        <div className="flex items-center gap-2 mb-1">
                                            {attempt.score === 100 && (
                                                <CheckCircle className="w-5 h-5 text-green-600" />
                                            )}
                                            <p
                                                className={`text-3xl font-bold ${getScoreColor(attempt.score)}`}
                                            >
                                                {attempt.score}
                                            </p>
                                            <span className="text-lg text-gray-500">
                                                / 100
                                            </span>
                                        </div>
                                        <p className="text-sm text-gray-500">
                                            Skor Akhir
                                        </p>
                                    </div>

                                    {attempt.score < 100 && (
                                        <Button
                                            onClick={() =>
                                                retakeQuiz(attempt.quiz.id)
                                            }
                                            className="flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700"
                                            size="sm"
                                        >
                                            <RotateCcw className="w-4 h-4" />
                                            Kerjakan Ulang
                                        </Button>
                                    )}

                                    {attempt.score === 100 && (
                                        <div className="flex items-center gap-2 text-sm font-medium text-green-600">
                                            <CheckCircle className="w-4 h-4" />
                                            Skor Sempurna!
                                        </div>
                                    )}
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                ))}

                {filteredQuizzes.length === 0 && (
                    <Card>
                        <CardContent className="flex flex-col items-center py-12 text-center">
                            <Lightbulb className="w-12 h-12 mb-4 text-gray-400" />
                            <h3 className="mb-2 text-lg font-medium text-gray-900">
                                Tidak ada kuis ditemukan
                            </h3>
                            <p className="mb-4 text-gray-500">
                                {hasActiveFilters
                                    ? 'Coba ubah filter pencarian atau reset filter untuk melihat semua kuis.'
                                    : 'Belum ada kuis yang dikerjakan. Mulai kerjakan kuis pertama Anda!'}
                            </p>
                            {hasActiveFilters ? (
                                <Button
                                    variant="outline"
                                    onClick={resetFilters}
                                >
                                    <RefreshCcw className="w-4 h-4 mr-2" />
                                    Reset Filter
                                </Button>
                            ) : (
                                <Button
                                    className="bg-emerald-600 hover:bg-emerald-700"
                                    onClick={() =>
                                        (window.location.href = '/quiz')
                                    }
                                >
                                    Mulai Kuis
                                </Button>
                            )}
                        </CardContent>
                    </Card>
                )}
            </div>
        </div>
    );
};

export default MyQuizPage;
