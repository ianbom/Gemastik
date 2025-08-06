<?php

namespace App\services;

use App\Jobs\NotificationJob;
use App\Models\Answer;
use App\Models\Point;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Services\PointService;
use Illuminate\Support\Facades\DB;

class QuizService
{
    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    public function calculateAndStoreAttempt(Quiz $quiz, User $user, array $submittedAnswers)
    {
        // Ambil semua ID pertanyaan dari kuis ini untuk efisiensi
        $questionIds = $quiz->questions->pluck('id');

        // Ambil semua jawaban yang benar untuk pertanyaan-pertanyaan ini dalam satu query
        $correctAnswers = Answer::whereIn('question_id', $questionIds)
            ->where('is_correct', true)
            ->pluck('id', 'question_id'); // Hasilnya: [question_id => correct_answer_id]

        $score = 0;
        $attemptAnswersData = [];

        // Loop melalui setiap pertanyaan di kuis
        foreach ($quiz->questions as $question) {
            $userAnswerId = $submittedAnswers[$question->id] ?? null;
            $correctAnswerId = $correctAnswers[$question->id] ?? null;

            $isCorrect = ($userAnswerId !== null && $userAnswerId == $correctAnswerId);

            if ($isCorrect) {
                $score++;
            }

            if ($userAnswerId) {
                $attemptAnswersData[] = [
                    'question_id' => $question->id,
                    'answer_id' => $userAnswerId,
                    'is_correct' => $isCorrect,
                ];
            }
        }


        return DB::transaction(function () use ($quiz, $user, $score, $attemptAnswersData) {


            $attempt = QuizAttempt::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'score' => $score,
                'total_questions' => $quiz->questions->count(),
            ]);



            if (!empty($attemptAnswersData)) {

                $dataToInsert = array_map(function ($answer) use ($attempt) {
                    $answer['quiz_attempt_id'] = $attempt->id;
                    $answer['created_at'] = now();
                    $answer['updated_at'] = now();
                    return $answer;
                }, $attemptAnswersData);

                DB::table('quiz_attempts_answer')->insert($dataToInsert);
            }

            $isPerfectScore = ($attempt->score == $attempt->total_questions);
            $score = $attempt->score / $attempt->total_questions * 100;

            $attempt->update([
                'score' => $score
            ]);

            if ($isPerfectScore) {
                $hasEarnedPointsBefore = Point::where('user_id', $user->id)
                    ->where('pointable_type', Quiz::class)
                    ->where('pointable_id', $quiz->id)
                    ->exists();

                // 5. Berikan poin HANYA jika skor sempurna DAN belum pernah dapat poin sebelumnya.
                if (!$hasEarnedPointsBefore) {
                    $this->pointService->increamentPoint(
                        'Penyelesaian Quiz Sempurna: ' . $quiz->title,
                        Quiz::class,
                        $quiz->id,
                        $quiz->points_reward,
                        $user->id
                    );

                    NotificationJob::dispatch(
                        'Quiz Diselesaikan Sempurna',
                        'Selamat! Anda mendapatkan ' . $quiz->points_reward . ' poin dari penyelesaian Quiz: ' . $quiz->title,
                        $user->id,
                        'Quiz'
                    );
                }
            }

            return $attempt;
        });
    }


}
