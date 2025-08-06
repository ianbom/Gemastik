<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


class QuizController extends Controller
{
    protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function index()
    {
        $quizzes = Quiz::where('is_active', true)->get();

        return Inertia::render('Citizen/Quiz/QuizPage', ['quizzes' => $quizzes]);
    }

    public function show(Quiz $quiz)
    {
        $quiz = Quiz::with('questions.answers')->findOrFail($quiz->id);
        return Inertia::render('Citizen/Quiz/DetailQuizPage', ['quiz' => $quiz]);
    }
    public function viewMyQuiz()
    {
        $user = Auth::user();
        $myQuizAttempts = $user->quizAttempt()->with('quiz')->latest()->get();
        return Inertia::render('Citizen/Quiz/MyQuizPage', [
            'myQuizAttempts' => $myQuizAttempts
        ]);
    }
    public function submit(Request $request, Quiz $quiz)
    {
        // Validasi input
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer|exists:answers,id',
        ]);

        $user = Auth::user();
        $submittedAnswers = $validated['answers'];

        // Panggil service untuk melakukan semua pekerjaan berat
        $newAttempt = $this->quizService->calculateAndStoreAttempt($quiz, $user, $submittedAnswers);


        return redirect()->back()->with('success', 'Quiz diselesaikan');
    }

    public function result(QuizAttempt $attempt)
    {
        // Pastikan pengguna hanya bisa melihat hasilnya sendiri
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Muat data yang dibutuhkan untuk ditampilkan di halaman hasil
        $attempt->load('quiz', 'attemptAnswers.question.answers');

        return Inertia::render('Citizen/Quiz/QuizResultPage', [
            'attempt' => $attempt,
        ]);
    }
}
