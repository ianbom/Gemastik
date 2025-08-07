import CitizenLayout from '@/components/layouts/CitizenLayout';
import QuizTakingPage from '@/components/quiz/DetailQuizPage';
import { PageProps } from '@/types';
import { router as Inertia, usePage } from '@inertiajs/react';
import { useEffect } from 'react';

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

interface QuizTakingRouteProps {
    quiz: Quiz;
    timeLimit?: number; // in minutes
    [key: string]: unknown;
}

const QuizTakingRoute = () => {
    const { props } = usePage<PageProps<QuizTakingRouteProps>>();
    const quiz = props.quiz;
    const timeLimit = props.timeLimit || 30;

    // Redirect jika quiz tidak aktif
    useEffect(() => {
        if (!quiz.is_active) {
            Inertia.visit(route('quiz.index'), {
                onError: () => {
                    // Handle error jika route tidak tersedia
                    window.history.back();
                },
            });
        }
    }, [quiz.is_active]);

    // Redirect jika tidak ada pertanyaan
    useEffect(() => {
        if (quiz.questions.length === 0) {
            Inertia.visit(route('quiz.index'), {
                onError: () => {
                    window.history.back();
                },
            });
        }
    }, [quiz.questions.length]);

    const handleSubmitQuiz = (answers: Record<number, number>) => {
        // Submit jawaban ke backend
        Inertia.post(
            route('quiz.submit', { id: quiz.id }),
            {
                answers: answers,
            },
            {
                onSuccess: () => {
                    // Redirect ke halaman hasil atau kembali ke daftar quiz
                    // Backend bisa return redirect ke hasil quiz
                },
                // onSuccess: (page) => {
                //     // Redirect ke halaman hasil atau kembali ke daftar quiz
                //     // Backend bisa return redirect ke hasil quiz
                // },
                onError: (errors) => {
                    console.error('Error submitting quiz:', errors);
                    // Handle error, mungkin tampilkan toast atau alert
                },
                preserveState: false,
            },
        );
    };

    const handleExitQuiz = () => {
        // Kembali ke daftar quiz
        Inertia.visit(route('quiz.index'), {
            onError: () => {
                window.history.back();
            },
        });
    };

    // Prevent page refresh/back during quiz
    useEffect(() => {
        const handleBeforeUnload = (e: BeforeUnloadEvent) => {
            e.preventDefault();
            e.returnValue =
                'Apakah Anda yakin ingin meninggalkan halaman? Jawaban Anda akan hilang.';
            return e.returnValue;
        };

        const handlePopState = (e: PopStateEvent) => {
            if (
                confirm(
                    'Apakah Anda yakin ingin meninggalkan quiz? Jawaban Anda akan hilang.',
                )
            ) {
                // Allow navigation
                return;
            } else {
                // Prevent navigation
                e.preventDefault();
                window.history.pushState(null, '', window.location.href);
            }
        };

        // Add event listeners
        window.addEventListener('beforeunload', handleBeforeUnload);
        window.addEventListener('popstate', handlePopState);

        // Push current state to prevent back button
        window.history.pushState(null, '', window.location.href);

        return () => {
            window.removeEventListener('beforeunload', handleBeforeUnload);
            window.removeEventListener('popstate', handlePopState);
        };
    }, []);

    // Loading state jika quiz belum ready
    if (!quiz || !quiz.questions || quiz.questions.length === 0) {
        return (
            <CitizenLayout currentPage="quiz">
                <div className="max-w-4xl px-4 py-8 mx-auto">
                    <div className="flex items-center justify-center py-32">
                        <div className="text-center">
                            <div className="w-12 h-12 mx-auto mb-4 border-b-2 rounded-full animate-spin border-emerald-600"></div>
                            <p className="text-gray-600">Memuat quiz...</p>
                        </div>
                    </div>
                </div>
            </CitizenLayout>
        );
    }

    // Error state jika quiz tidak aktif
    if (!quiz.is_active) {
        return (
            <CitizenLayout currentPage="quiz">
                <div className="max-w-4xl px-4 py-8 mx-auto">
                    <div className="py-32 text-center">
                        <h2 className="mb-4 text-2xl font-bold text-gray-900">
                            Quiz Tidak Tersedia
                        </h2>
                        <p className="mb-8 text-gray-600">
                            Quiz ini sedang tidak aktif dan tidak dapat
                            dikerjakan.
                        </p>
                        <button
                            onClick={() => window.history.back()}
                            className="px-6 py-2 text-white rounded-lg bg-emerald-600 hover:bg-emerald-700"
                        >
                            Kembali
                        </button>
                    </div>
                </div>
            </CitizenLayout>
        );
    }

    console.log('Quiz data:', quiz);

    return (
        <CitizenLayout currentPage="quiz">
            <QuizTakingPage
                quiz={quiz}
                onSubmitQuiz={handleSubmitQuiz}
                onExitQuiz={handleExitQuiz}
                timeLimit={timeLimit}
            />
        </CitizenLayout>
    );
};

export default QuizTakingRoute;
