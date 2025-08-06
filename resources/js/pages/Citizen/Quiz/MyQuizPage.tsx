import CitizenLayout from '@/components/layouts/CitizenLayout';
import MyQuizPage from '@/components/quiz/MyQuizPage';
import { PageProps } from '@/types';
import { QuizAttempt } from '@/types/quiz/attempt';
import { usePage } from '@inertiajs/react';

interface MyQuizPageRouteProps {
    myQuizAttempts: QuizAttempt[];
    [key: string]: unknown;
}
const MyQuizPageRoute = () => {
    const { props } = usePage<PageProps<MyQuizPageRouteProps>>();
    const myQuizAttempts = props.myQuizAttempts || [];
    const user = props.auth?.user ?? null;
    if (!user) return null;

    return (
        <CitizenLayout currentPage="my-merchandise">
            <MyQuizPage auth={{ user }} myQuizAttempts={myQuizAttempts} />
        </CitizenLayout>
    );
};

export default MyQuizPageRoute;
