export interface QuizAnswer {
    id: number;
    quiz_attempt_id: number;
    question_id: number;
    answer_id: number;
    is_correct: boolean;
    created_at: string;
    updated_at: string;
}
