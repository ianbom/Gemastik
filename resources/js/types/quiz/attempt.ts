import { User } from '../user/interface';
import { Quiz } from './quiz';
export interface QuizAttempt {
    id: number;
    user_id: number;
    quiz_id: number;
    score: number;
    quiz: Quiz;
    user: User;
    total_questions: number;
    created_at: string;
    updated_at: string;
}
