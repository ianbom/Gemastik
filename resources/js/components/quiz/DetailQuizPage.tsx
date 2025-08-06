import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { router, useForm } from '@inertiajs/react';
import {
    AlertTriangle,
    ArrowLeft,
    ArrowRight,
    Award,
    CheckCircle,
    Clock,
    Flag,
    Loader2,
    Star,
} from 'lucide-react';
import { useEffect, useState } from 'react';

interface Answer {
    id: number;
    question_id: number;
    answer_text?: string;
    image_url?: string;
    is_correct: boolean;
}

interface Question {
    id: number;
    quiz_id: number;
    question_text: string;
    question_image_url?: string;
    order: number;
    answers: Answer[];
}

interface Quiz {
    id: number;
    title: string;
    description?: string;
    thumbnail_url?: string;
    difficulty: 'mudah' | 'sedang' | 'sulit';
    points_reward: number;
    is_active: boolean;
    questions: Question[];
}

interface QuizTakingPageProps {
    quiz: Quiz;
    onSubmitQuiz: (answers: Record<number, number>) => void;
    onExitQuiz: () => void;
    timeLimit: number;
}

const QuizTakingPage = ({ quiz, timeLimit = 30 }: QuizTakingPageProps) => {
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [selectedAnswers, setSelectedAnswers] = useState<
        Record<number, number>
    >({});
    const [timeRemaining, setTimeRemaining] = useState(timeLimit * 60); // in seconds
    const [showExitDialog, setShowExitDialog] = useState(false);
    const [showSubmitDialog, setShowSubmitDialog] = useState(false);
    const [isQuizFinished, setIsQuizFinished] = useState(false);

    // Inertia form untuk submit quiz
    // const { data, setData, post, processing, errors, reset } = useForm({
    //     answers: {} as Record<string, number>,
    // });
    const { setData, post, processing, errors } = useForm({
        answers: {} as Record<string, number>,
    });

    const currentQuestion = quiz.questions[currentQuestionIndex];
    const totalQuestions = quiz.questions.length;
    const answeredQuestions = Object.keys(selectedAnswers).length;
    const progressPercentage =
        ((currentQuestionIndex + 1) / totalQuestions) * 100;

    // Update form data ketika selectedAnswers berubah
    useEffect(() => {
        const formattedAnswers: Record<string, number> = {};
        Object.entries(selectedAnswers).forEach(([questionId, answerId]) => {
            formattedAnswers[questionId] = answerId;
        });
        setData('answers', formattedAnswers);
    }, [selectedAnswers, setData]);

    // Timer countdown
    useEffect(() => {
        if (isQuizFinished || processing) return;

        const timer = setInterval(() => {
            setTimeRemaining((prev) => {
                if (prev <= 1) {
                    handleSubmitQuiz();
                    return 0;
                }
                return prev - 1;
            });
        }, 1000);

        return () => clearInterval(timer);
    }, [isQuizFinished, processing]);

    const formatTime = (seconds: number) => {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    };

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

    const handleAnswerSelect = (answerId: number) => {
        setSelectedAnswers((prev) => ({
            ...prev,
            [currentQuestion.id]: answerId,
        }));
    };

    const goToNextQuestion = () => {
        if (currentQuestionIndex < totalQuestions - 1) {
            setCurrentQuestionIndex(currentQuestionIndex + 1);
        }
    };

    const goToPreviousQuestion = () => {
        if (currentQuestionIndex > 0) {
            setCurrentQuestionIndex(currentQuestionIndex - 1);
        }
    };

    const goToQuestion = (index: number) => {
        setCurrentQuestionIndex(index);
    };

    const handleSubmitQuiz = () => {
        setIsQuizFinished(true);
        setShowSubmitDialog(false);

        // Submit menggunakan Inertia
        post(route('quiz.submit', quiz.id), {
            onSuccess: (page) => {
                // Redirect ke halaman result atau tampilkan notifikasi sukses
                console.log('Quiz submitted successfully:', page.props);
                // Bisa redirect ke halaman hasil quiz
                // router.visit(route('quiz.result', quiz.id));
            },
            onError: (errors) => {
                console.error('Error submitting quiz:', errors);
                setIsQuizFinished(false);
                // Tampilkan error message jika perlu
                alert(
                    'Terjadi kesalahan saat mengirim jawaban. Silakan coba lagi.',
                );
            },
            onFinish: () => {
                // Reset form jika perlu
                // reset();
            },
        });
    };

    const handleExitQuiz = () => {
        setShowExitDialog(false);
        // Redirect kembali ke halaman quiz list atau dashboard
        router.visit(route('quiz.index'));
    };

    const getTimeColor = () => {
        if (timeRemaining > 300) return 'text-green-600';
        if (timeRemaining > 60) return 'text-yellow-600';
        return 'text-red-600'; // <= 1 minute
    };

    if (isQuizFinished) {
        return (
            <div className="mx-auto max-w-4xl px-4 py-8">
                <Card className="text-center">
                    <CardContent className="py-12">
                        <div className="mb-6">
                            <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                                {processing ? (
                                    <Loader2 className="h-8 w-8 animate-spin text-green-600" />
                                ) : (
                                    <CheckCircle className="h-8 w-8 text-green-600" />
                                )}
                            </div>
                            <h2 className="mb-2 text-2xl font-bold text-gray-900">
                                {processing
                                    ? 'Mengirim Jawaban...'
                                    : 'Quiz Selesai!'}
                            </h2>
                            <p className="text-gray-600">
                                {processing
                                    ? 'Sedang memproses jawaban Anda...'
                                    : 'Jawaban Anda telah berhasil dikirim!'}
                            </p>
                        </div>
                        {errors && Object.keys(errors).length > 0 && (
                            <div className="mt-4 rounded-lg bg-red-50 p-4 text-red-700">
                                <p className="font-medium">
                                    Terjadi kesalahan:
                                </p>
                                <ul className="mt-2 list-inside list-disc text-sm">
                                    {Object.entries(errors).map(
                                        ([key, error]) => (
                                            <li key={key}>{error}</li>
                                        ),
                                    )}
                                </ul>
                            </div>
                        )}

                        <div className="mt-8">
                            <Button
                                size="lg"
                                className="bg-emerald-600 hover:bg-emerald-700"
                                onClick={() => router.visit(route('my-quiz'))}
                            >
                                <Award className="mr-2 h-5 w-5" />
                                Lihat Skor Saya
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        );
    }

    return (
        <div className="mx-auto max-w-6xl px-4 py-6">
            {/* Header */}
            <div className="mb-6 flex items-center justify-between">
                <div className="flex items-center space-x-4">
                    <Button
                        variant="outline"
                        size="sm"
                        onClick={() => setShowExitDialog(true)}
                        className="flex items-center"
                        disabled={processing}
                    >
                        <ArrowLeft className="mr-2 h-4 w-4" />
                        Keluar
                    </Button>
                </div>

                <div className="flex items-center space-x-4">
                    <div
                        className={`flex items-center font-mono text-lg font-bold ${getTimeColor()}`}
                    >
                        <Clock className="mr-2 h-5 w-5" />
                        {formatTime(timeRemaining)}
                    </div>
                    <Button
                        onClick={() => setShowSubmitDialog(true)}
                        className="bg-emerald-600 hover:bg-emerald-700"
                        disabled={answeredQuestions === 0 || processing}
                    >
                        {processing ? (
                            <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                        ) : (
                            <Flag className="mr-2 h-4 w-4" />
                        )}
                        Selesai
                    </Button>
                </div>
            </div>

            {/* Progress */}
            <div className="mb-6">
                <div className="relative mb-2 overflow-hidden rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 px-4 py-4 shadow-lg">
                    <div className="relative z-10 flex items-start justify-between p-5">
                        <div className="mr-4 min-w-0 flex-1">
                            <h1 className="mb-2 line-clamp-2 text-2xl font-bold leading-tight text-white">
                                {quiz.title}
                            </h1>
                            <div className="flex flex-wrap items-center gap-2">
                                <Badge
                                    className={`${getDifficultyColor(quiz.difficulty)} shadow-sm`}
                                >
                                    {getDifficultyLabel(quiz.difficulty)}
                                </Badge>
                                <Badge className="flex items-center gap-1 bg-yellow-100 text-yellow-800">
                                    <Star
                                        size={12}
                                        className="text-yellow-500"
                                    />
                                    {quiz.points_reward} Poin
                                </Badge>
                            </div>
                        </div>

                        <div className="flex-shrink-0">
                            <div className="flex h-16 w-16 items-center justify-center rounded-xl border border-white/30 bg-white/20 backdrop-blur-sm">
                                <svg
                                    className="h-8 w-8 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path d="M9 21c0 .5.4 1 1 1h4c.6 0 1-.5 1-1v-1H9v1zm3-19C8.1 2 5 5.1 5 9c0 2.4 1.2 4.5 3 5.7V17c0 .5.4 1 1 1h6c.6 0 1-.5 1-1v-2.3c1.8-1.2 3-3.3 3-5.7 0-3.9-3.1-7-7-7z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="mb-2 flex items-center justify-between text-sm text-gray-600">
                    <span>
                        Pertanyaan {currentQuestionIndex + 1} dari{' '}
                        {totalQuestions}
                    </span>
                    <span>
                        {answeredQuestions} dari {totalQuestions} terjawab
                    </span>
                </div>
                <Progress
                    value={progressPercentage}
                    className="h-2 [&>div]:bg-emerald-600"
                />
            </div>

            <div className="grid grid-cols-1 gap-6 lg:grid-cols-4">
                {/* Question Navigation */}
                <div className="lg:col-span-1">
                    <Card className="sticky top-6">
                        <CardHeader>
                            <CardTitle className="text-lg">
                                Navigasi Soal
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="grid grid-cols-5 gap-2 lg:grid-cols-4">
                                {quiz.questions.map((question, index) => (
                                    <button
                                        key={question.id}
                                        onClick={() => goToQuestion(index)}
                                        disabled={processing}
                                        className={`aspect-square rounded border-2 text-sm font-medium transition-colors disabled:opacity-50 ${
                                            currentQuestionIndex === index
                                                ? 'border-sky-500 bg-sky-100 text-sky-700'
                                                : selectedAnswers[question.id]
                                                  ? 'border-green-500 bg-green-100 text-green-700'
                                                  : 'border-gray-300 bg-gray-100 text-gray-700 hover:border-gray-400'
                                        }`}
                                    >
                                        {index + 1}
                                    </button>
                                ))}
                            </div>
                            <div className="mt-4 space-y-2 text-xs text-gray-600">
                                <p>Keterangan:</p>
                                <div className="flex items-center">
                                    <div className="mr-2 h-3 w-3 rounded border-2 border-sky-500 bg-sky-100"></div>
                                    Soal saat ini
                                </div>
                                <div className="flex items-center">
                                    <div className="mr-2 h-3 w-3 rounded border-2 border-green-500 bg-green-100"></div>
                                    Sudah dijawab
                                </div>
                                <div className="flex items-center">
                                    <div className="mr-2 h-3 w-3 rounded border-2 border-gray-300 bg-gray-100"></div>
                                    Belum dijawab
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Main Question */}
                <div className="lg:col-span-3">
                    <Card className="bg-emerald-100">
                        <CardHeader>
                            <CardTitle className="text-xl text-emerald-800">
                                Pertanyaan {currentQuestionIndex + 1}
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            {/* Question Text */}
                            <div className="space-y-4">
                                <p className="text-2xl font-semibold leading-relaxed text-gray-900">
                                    {currentQuestion.question_text}
                                </p>
                                {currentQuestion.question_image_url && (
                                    <div className="flex justify-center">
                                        <img
                                            src={
                                                currentQuestion.question_image_url.startsWith(
                                                    '/storage/',
                                                )
                                                    ? currentQuestion.question_image_url
                                                    : `/storage/${currentQuestion.question_image_url}`
                                            }
                                            alt="Question illustration"
                                            className="max-h-64 rounded-lg border"
                                        />
                                    </div>
                                )}
                            </div>

                            {/* Answer Options */}
                            <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                {currentQuestion.answers.map(
                                    (answer, index) => (
                                        <button
                                            key={answer.id}
                                            onClick={() =>
                                                handleAnswerSelect(answer.id)
                                            }
                                            disabled={processing}
                                            className={`w-full rounded-lg border-2 p-4 text-left transition-all disabled:opacity-50 ${
                                                selectedAnswers[
                                                    currentQuestion.id
                                                ] === answer.id
                                                    ? 'border-emerald-700 bg-emerald-700'
                                                    : 'border-emerald-200 bg-white hover:scale-105 hover:border-emerald-300 hover:bg-gray-50'
                                            }`}
                                        >
                                            <div className="flex items-start space-x-3">
                                                <div
                                                    className={`mt-1 flex h-6 w-6 items-center justify-center rounded-md border-2 p-4 text-sm font-bold ${
                                                        selectedAnswers[
                                                            currentQuestion.id
                                                        ] === answer.id
                                                            ? 'border-emerald-200 bg-emerald-200 text-emerald-900'
                                                            : 'border-emerald-200 bg-white text-emerald-700'
                                                    }`}
                                                >
                                                    {String.fromCharCode(
                                                        65 + index,
                                                    )}
                                                </div>
                                                <div className="flex-1">
                                                    {answer.image_url && (
                                                        <img
                                                            src={
                                                                answer.image_url.startsWith(
                                                                    '/storage/',
                                                                )
                                                                    ? answer.image_url
                                                                    : `/storage/${answer.image_url}`
                                                            }
                                                            alt={`Option ${String.fromCharCode(65 + index)}`}
                                                            className="mb-2 max-h-32 rounded"
                                                        />
                                                    )}
                                                    {answer.answer_text && (
                                                        <p
                                                            className={`font-medium ${
                                                                selectedAnswers[
                                                                    currentQuestion
                                                                        .id
                                                                ] === answer.id
                                                                    ? 'text-white'
                                                                    : 'text-black'
                                                            }`}
                                                        >
                                                            {answer.answer_text}
                                                        </p>
                                                    )}
                                                </div>
                                            </div>
                                        </button>
                                    ),
                                )}
                            </div>

                            {/* Navigation Buttons */}
                            <div className="flex items-center justify-between border-t pt-6">
                                <Button
                                    variant="outline"
                                    onClick={goToPreviousQuestion}
                                    disabled={
                                        currentQuestionIndex === 0 || processing
                                    }
                                >
                                    <ArrowLeft className="mr-2 h-4 w-4" />
                                    Sebelumnya
                                </Button>

                                {currentQuestionIndex === totalQuestions - 1 ? (
                                    <Button
                                        onClick={() =>
                                            setShowSubmitDialog(true)
                                        }
                                        className="bg-emerald-600 hover:bg-emerald-700"
                                        disabled={
                                            answeredQuestions === 0 ||
                                            processing
                                        }
                                    >
                                        {processing ? (
                                            <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                                        ) : (
                                            <Flag className="mr-2 h-4 w-4" />
                                        )}
                                        Selesaikan Quiz
                                    </Button>
                                ) : (
                                    <Button
                                        onClick={goToNextQuestion}
                                        className="bg-emerald-600 hover:bg-emerald-700"
                                        disabled={processing}
                                    >
                                        Selanjutnya
                                        <ArrowRight className="ml-2 h-4 w-4" />
                                    </Button>
                                )}
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            {/* Exit Dialog */}
            <AlertDialog open={showExitDialog} onOpenChange={setShowExitDialog}>
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle className="flex items-center">
                            <AlertTriangle className="mr-2 h-5 w-5 text-amber-500" />
                            Keluar dari Quiz?
                        </AlertDialogTitle>
                        <AlertDialogDescription>
                            Apakah Anda yakin ingin keluar? Semua jawaban yang
                            sudah Anda pilih akan hilang dan tidak dapat
                            dikembalikan.
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel disabled={processing}>
                            Batal
                        </AlertDialogCancel>
                        <AlertDialogAction
                            onClick={handleExitQuiz}
                            className="bg-red-600 hover:bg-red-700"
                            disabled={processing}
                        >
                            Ya, Keluar
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>

            {/* Submit Dialog */}
            <AlertDialog
                open={showSubmitDialog}
                onOpenChange={setShowSubmitDialog}
            >
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle className="flex items-center">
                            <Flag className="mr-2 h-5 w-5 text-emerald-500" />
                            Selesaikan Quiz?
                        </AlertDialogTitle>
                        <AlertDialogDescription>
                            <div className="space-y-2">
                                <p>
                                    Anda telah menjawab {answeredQuestions} dari{' '}
                                    {totalQuestions} pertanyaan.
                                </p>
                                {answeredQuestions < totalQuestions && (
                                    <p className="text-amber-600">
                                        <AlertTriangle className="mr-1 inline h-4 w-4" />
                                        {totalQuestions - answeredQuestions}{' '}
                                        pertanyaan belum dijawab dan akan
                                        dianggap salah.
                                    </p>
                                )}
                                <p>
                                    Apakah Anda yakin ingin menyelesaikan quiz
                                    ini?
                                </p>
                            </div>
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel disabled={processing}>
                            Batal
                        </AlertDialogCancel>
                        <AlertDialogAction
                            onClick={handleSubmitQuiz}
                            className="bg-emerald-600 hover:bg-emerald-700"
                            disabled={processing}
                        >
                            {processing ? (
                                <>
                                    <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                                    Mengirim...
                                </>
                            ) : (
                                'Ya, Selesaikan'
                            )}
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    );
};

export default QuizTakingPage;
