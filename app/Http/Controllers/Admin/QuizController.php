<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function index(){
        $quiz = Quiz::all();
        return view('admin.quiz.index', ['quizzes' => $quiz]);
    }

    public function create(){
        return view('admin.quiz.create');
    }

    public function store(Request $request)
    {


        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'nullable|in:mudah,sedang,sulit',
            'points_reward' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'thumbnail' => 'nullable',

            // Validasi untuk questions
            'questions' => 'nullable|array|min:1',
            'questions.*.question_text' => 'nullable|string',
            'questions.*.order' => 'nullable|integer|min:1',
            'questions.*.correct_answer' => 'nullable|integer|min:0',

            // Validasi untuk answers
            'questions.*.answers' => 'nullable|array|min:2',
            'questions.*.answers.*.answer_text' => 'nullable|string',
            'questions.*.answers.*.image' => 'nullable',
        ], [
            'title.required' => 'Judul quiz wajib diisi.',
            'questions.required' => 'Minimal harus ada 1 pertanyaan.',
            'questions.*.question_text.required' => 'Teks pertanyaan wajib diisi.',
            'questions.*.correct_answer.required' => 'Pilih jawaban yang benar untuk setiap pertanyaan.',
            'questions.*.answers.min' => 'Setiap pertanyaan harus memiliki minimal 2 jawaban.',
        ]);

        DB::beginTransaction();

        try {
            // Simpan thumbnail jika ada
            $thumbnailUrl = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailUrl = $this->uploadImage($request->file('thumbnail'), 'quiz-thumbnails');
            }

            // Buat quiz
            $quiz = Quiz::create([
                'title' => $request->title,
                'description' => $request->description,
                'thumbnail_url' => $thumbnailUrl,
                'difficulty' => $request->difficulty,
                'points_reward' => $request->points_reward,
                'is_active' => $request->boolean('is_active'),
            ]);

            // Simpan questions dan answers
            foreach ($request->questions as $questionData) {
                // Upload gambar pertanyaan jika ada
                $questionImageUrl = null;
                if (isset($questionData['question_image']) && $questionData['question_image']) {
                    $questionImageUrl = $this->uploadImage($questionData['question_image'], 'question-images');
                }

                // Buat question
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionData['question_text'],
                    'question_image_url' => $questionImageUrl,
                    'order' => $questionData['order'],
                ]);

                // Simpan answers
                foreach ($questionData['answers'] as $index => $answerData) {
                    // Skip jika answer_text kosong dan tidak ada gambar
                    if (empty($answerData['answer_text']) && !isset($answerData['image'])) {
                        continue;
                    }

                    // Upload gambar jawaban jika ada
                    $answerImageUrl = null;
                    if (isset($answerData['image']) && $answerData['image']) {
                        $answerImageUrl = $this->uploadImage($answerData['image'], 'answer-images');
                    }

                    // Tentukan apakah ini jawaban yang benar
                    $isCorrect = isset($questionData['correct_answer']) &&
                                $questionData['correct_answer'] == $index;

                    Answer::create([
                        'question_id' => $question->id,
                        'answer_text' => $answerData['answer_text'] ?? null,
                        'image_url' => $answerImageUrl,
                        'is_correct' => $isCorrect,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.quizzes.index')
                           ->with('success', 'Quiz berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['err' => $e->getMessage()]);
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
{
    $quiz = Quiz::findOrFail($id);
    return view('admin.quiz.edit', compact('quiz'));
}

/**
 * Update the specified quiz in storage.
 */
public function update(Request $request, $id)
{
    $quiz = Quiz::findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'difficulty' => 'required|in:mudah,sedang,sulit',
        'points_reward' => 'required|integer|min:1',
        'is_active' => 'boolean',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        // Validasi untuk questions
        'questions' => 'required|array|min:1',
        'questions.*.question_text' => 'required|string',
        'questions.*.order' => 'required|integer|min:1',
        'questions.*.correct_answer' => 'required|integer|min:0',

        // Validasi untuk answers
        'questions.*.answers' => 'required|array|min:2',
        'questions.*.answers.*.answer_text' => 'nullable|string',
        'questions.*.answers.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'title.required' => 'Judul quiz wajib diisi.',
        'questions.required' => 'Minimal harus ada 1 pertanyaan.',
        'questions.*.question_text.required' => 'Teks pertanyaan wajib diisi.',
        'questions.*.correct_answer.required' => 'Pilih jawaban yang benar untuk setiap pertanyaan.',
        'questions.*.answers.min' => 'Setiap pertanyaan harus memiliki minimal 2 jawaban.',
    ]);

    DB::beginTransaction();

    try {
        // Update thumbnail jika ada
        $thumbnailUrl = $quiz->thumbnail_url;
        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama jika ada
            if ($thumbnailUrl && Storage::exists('public/' . $thumbnailUrl)) {
                Storage::delete('public/' . $thumbnailUrl);
            }
            $thumbnailUrl = $this->uploadImage($request->file('thumbnail'), 'quiz-thumbnails');
        }

        // Update quiz
        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail_url' => $thumbnailUrl,
            'difficulty' => $request->difficulty,
            'points_reward' => $request->points_reward,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Kumpulkan ID pertanyaan dan jawaban yang ada di request
        $questionIds = [];
        $answerIds = [];

        // Update atau buat questions dan answers
        foreach ($request->questions as $questionData) {
            // Upload gambar pertanyaan jika ada
            $questionImageUrl = null;
            $existingQuestion = null;

            if (isset($questionData['id']) && $questionData['id']) {
                // Update existing question
                $existingQuestion = Question::find($questionData['id']);
                if ($existingQuestion) {
                    $questionImageUrl = $existingQuestion->question_image_url;
                    $questionIds[] = $existingQuestion->id;
                }
            }

            if (isset($questionData['question_image']) && $questionData['question_image']) {
                // Hapus gambar lama jika ada
                if ($questionImageUrl && Storage::exists('public/' . $questionImageUrl)) {
                    Storage::delete('public/' . $questionImageUrl);
                }
                $questionImageUrl = $this->uploadImage($questionData['question_image'], 'question-images');
            }

            // Update atau create question
            if ($existingQuestion) {
                $existingQuestion->update([
                    'question_text' => $questionData['question_text'],
                    'question_image_url' => $questionImageUrl,
                    'order' => $questionData['order'],
                ]);
                $question = $existingQuestion;
            } else {
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionData['question_text'],
                    'question_image_url' => $questionImageUrl,
                    'order' => $questionData['order'],
                ]);
                $questionIds[] = $question->id;
            }

            // Update atau buat answers
            foreach ($questionData['answers'] as $index => $answerData) {
                // Skip jika answer_text kosong dan tidak ada gambar yang di-upload
                if (empty($answerData['answer_text']) && !isset($answerData['image'])) {
                    continue;
                }

                $answerImageUrl = null;
                $existingAnswer = null;

                if (isset($answerData['id']) && $answerData['id']) {
                    // Update existing answer
                    $existingAnswer = Answer::find($answerData['id']);
                    if ($existingAnswer) {
                        $answerImageUrl = $existingAnswer->image_url;
                        $answerIds[] = $existingAnswer->id;
                    }
                }

                if (isset($answerData['image']) && $answerData['image']) {
                    // Hapus gambar lama jika ada
                    if ($answerImageUrl && Storage::exists('public/' . $answerImageUrl)) {
                        Storage::delete('public/' . $answerImageUrl);
                    }
                    $answerImageUrl = $this->uploadImage($answerData['image'], 'answer-images');
                }

                // Tentukan apakah ini jawaban yang benar
                $isCorrect = isset($questionData['correct_answer']) &&
                            $questionData['correct_answer'] == $index;

                // Update atau create answer
                if ($existingAnswer) {
                    $existingAnswer->update([
                        'answer_text' => $answerData['answer_text'] ?? null,
                        'image_url' => $answerImageUrl,
                        'is_correct' => $isCorrect,
                    ]);
                } else {
                    $newAnswer = Answer::create([
                        'question_id' => $question->id,
                        'answer_text' => $answerData['answer_text'] ?? null,
                        'image_url' => $answerImageUrl,
                        'is_correct' => $isCorrect,
                    ]);
                    $answerIds[] = $newAnswer->id;
                }
            }
        }

        // Hapus questions yang tidak ada di request (beserta answers-nya akan terhapus otomatis karena cascade)
        $quiz->questions()->whereNotIn('id', $questionIds)->get()->each(function ($question) {
            // Hapus gambar pertanyaan jika ada
            if ($question->question_image_url && Storage::exists('public/' . $question->question_image_url)) {
                Storage::delete('public/' . $question->question_image_url);
            }

            // Hapus gambar answers jika ada
            $question->answers->each(function ($answer) {
                if ($answer->image_url && Storage::exists('public/' . $answer->image_url)) {
                    Storage::delete('public/' . $answer->image_url);
                }
            });

            $question->delete();
        });

        // Hapus answers yang tidak ada di request
        if (!empty($answerIds)) {
            Answer::whereHas('question', function ($query) use ($quiz) {
                $query->where('quiz_id', $quiz->id);
            })->whereNotIn('id', $answerIds)->get()->each(function ($answer) {
                // Hapus gambar answer jika ada
                if ($answer->image_url && Storage::exists('public/' . $answer->image_url)) {
                    Storage::delete('public/' . $answer->image_url);
                }
                $answer->delete();
            });
        }

        DB::commit();

        return redirect()->route('admin.quizzes.index')
                       ->with('success', 'Quiz berhasil diupdate!');

    } catch (\Exception $e) {
        DB::rollback();

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    private function uploadImage($file, $folder)
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public');
        return $path;
    }



}
