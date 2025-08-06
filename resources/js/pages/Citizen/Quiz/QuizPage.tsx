import CitizenLayout from '@/components/layouts/CitizenLayout';
import QuizzesPage from '@/components/quiz/QuizPage';
import { PageProps } from '@/types';
import { router as Inertia, usePage } from '@inertiajs/react';

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

interface QuizzesPageRouteProps {
    quizzes: Quiz[];
    canCreate?: boolean;
    [key: string]: unknown;
}

const QuizzesPageRoute = () => {
    const { props } = usePage<PageProps<QuizzesPageRouteProps>>();
    const quizzes = props.quizzes
    const canCreate = props.canCreate || false;

    const handleViewDetails = (id: number) => {
        // Ganti dengan route yang sesuai untuk detail/mulai kuis
        Inertia.visit(route('quiz.show', { id }));
    };

    const handleCreateQuiz = () => {
        // Ganti dengan route yang sesuai untuk membuat kuis
        Inertia.visit(route('quiz.create'));
    };

    console.log('quizzes', quizzes);

    return (
        <CitizenLayout currentPage="quiz">
            <QuizzesPage
                quizzes={quizzes}
                onViewDetails={handleViewDetails}
                onCreateQuiz={canCreate ? handleCreateQuiz : undefined}
                canCreate={canCreate}
            />
        </CitizenLayout>
    );
};

export default QuizzesPageRoute;
