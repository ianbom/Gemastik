@extends('admin.layouts.app')

@section('content')
<div class="container px-4 py-4 mx-auto">
    <div class="mx-auto max-w-7xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="mb-2 text-3xl font-bold text-gray-900">Buat Quiz Baru</h1>
            <p class="text-gray-600">Lengkapi informasi quiz, pertanyaan, dan jawaban</p>
        </div>

        <form action="{{ route('admin.quizzes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Quiz Information -->
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h2 class="flex items-center mb-6 text-xl font-semibold text-gray-800">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Quiz
                </h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-700">Judul Quiz *</label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Masukkan judul quiz"
                               required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Masukkan deskripsi quiz">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="difficulty" class="block mb-2 text-sm font-medium text-gray-700">Tingkat Kesulitan</label>
                        <select id="difficulty"
                                name="difficulty"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="mudah" {{ old('difficulty') == 'mudah' ? 'selected' : '' }}>Mudah</option>
                            <option value="sedang" {{ old('difficulty') == 'sedang' || !old('difficulty') ? 'selected' : '' }}>Sedang</option>
                            <option value="sulit" {{ old('difficulty') == 'sulit' ? 'selected' : '' }}>Sulit</option>
                        </select>
                    </div>

                    <div>
                        <label for="points_reward" class="block mb-2 text-sm font-medium text-gray-700">Poin Reward</label>
                        <input type="number"
                               id="points_reward"
                               name="points_reward"
                               value="{{ old('points_reward', 10) }}"
                               min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="10">
                    </div>

                    <div>
                        <label for="thumbnail" class="block mb-2 text-sm font-medium text-gray-700">Thumbnail</label>
                        <input type="file"
                               id="thumbnail"
                               name="thumbnail"
                               accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500">Upload gambar thumbnail (opsional)</p>
                    </div>

                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_active" class="block ml-2 text-sm text-gray-700">Quiz aktif</label>
                    </div>
                </div>
            </div>

            <!-- Questions Section -->
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="flex items-center text-xl font-semibold text-gray-800">
                        <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pertanyaan
                    </h2>
                    <button type="button"
                            id="addQuestion"
                            class="flex items-center px-4 py-2 text-white transition duration-200 bg-green-600 rounded-lg hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Pertanyaan
                    </button>
                </div>

                <div id="questionsContainer">
                    <!-- Questions will be added here dynamically -->
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.quizzes.index') }}"
                   class="px-6 py-2 text-gray-700 transition duration-200 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700">
                    Simpan Quiz
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionIndex = 0;
    const questionsContainer = document.getElementById('questionsContainer');
    const addQuestionBtn = document.getElementById('addQuestion');

    // --- Template untuk Pertanyaan Baru ---
    const createQuestionTemplate = (qIndex) => `
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-800">Pertanyaan ${qIndex + 1}</h3>
            <div class="flex space-x-2">
                <button type="button" class="px-3 py-1 text-sm text-white bg-blue-500 rounded add-answer-btn hover:bg-blue-600">
                    + Jawaban
                </button>
                ${qIndex > 0 ? `
                <button type="button" class="px-3 py-1 text-sm text-white bg-red-500 rounded remove-question-btn hover:bg-red-600">
                    Hapus
                </button>
                ` : ''}
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Teks Pertanyaan *</label>
                <textarea name="questions[${qIndex}][question_text]" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Masukkan pertanyaan..." required></textarea>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Gambar Pertanyaan (Opsional)</label>
                <input type="file" name="questions[${qIndex}][question_image]" accept="image/*" class="w-full text-sm">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Urutan</label>
                <input type="number" name="questions[${qIndex}][order]" value="${qIndex + 1}" class="w-20 px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="answers-container">
                <h4 class="mb-3 font-medium text-gray-700 text-md">Jawaban (Pilih satu yang benar)</h4>
                <div class="space-y-3 answers-list">
                    <!-- Jawaban dinamis akan ditambahkan di sini -->
                </div>
            </div>
        </div>
    `;

    // --- Template untuk Jawaban Baru ---
    const createAnswerTemplate = (qIndex, aIndex) => `
        <div class="flex items-start p-4 space-x-3 border border-gray-100 rounded-lg answer-item bg-gray-50">
            <div class="flex items-center mt-2">
                <input type="radio"
                       name="questions[${qIndex}][correct_answer]"
                       value="${aIndex}"
                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                       title="Pilih sebagai jawaban benar"
                       required>
            </div>
            <div class="flex-1 space-y-3">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-600">Teks Jawaban</label>
                    <textarea name="questions[${qIndex}][answers][${aIndex}][answer_text]" rows="2" class="w-full px-3 py-2 text-sm border border-gray-300 rounded" placeholder="Masukkan teks jawaban (opsional jika ada gambar)"></textarea>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-600">Gambar Jawaban (Opsional)</label>
                    <input type="file" name="questions[${qIndex}][answers][${aIndex}][image]" accept="image/*" class="w-full text-sm">
                </div>
            </div>
            <div class="mt-2">
                <button type="button" class="text-red-500 remove-answer-btn hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        </div>
    `;

    // --- Fungsi Utama ---
    const addQuestion = () => {
        const questionDiv = document.createElement('div');
        questionDiv.className = 'question-item border border-gray-200 rounded-lg p-6 mb-6';
        questionDiv.setAttribute('data-index', questionIndex);
        questionDiv.innerHTML = createQuestionTemplate(questionIndex);
        questionsContainer.appendChild(questionDiv);

        // Tambahkan 2 jawaban awal untuk pertanyaan baru
        addAnswer(questionDiv);
        addAnswer(questionDiv);

        // Tandai radio button pertama sebagai checked secara default
        questionDiv.querySelector('input[type="radio"]').checked = true;

        questionIndex++;
    };

    const addAnswer = (questionDiv) => {
        const qIndex = questionDiv.getAttribute('data-index');
        const answersList = questionDiv.querySelector('.answers-list');
        const answerIndex = answersList.children.length;

        const answerDiv = document.createElement('div');
        answerDiv.innerHTML = createAnswerTemplate(qIndex, answerIndex);
        answersList.appendChild(answerDiv.firstElementChild);
    };

    const updateDOMIndices = () => {
        document.querySelectorAll('.question-item').forEach((questionDiv, qIdx) => {
            // Update question index
            questionDiv.setAttribute('data-index', qIdx);
            questionDiv.querySelector('h3').textContent = `Pertanyaan ${qIdx + 1}`;
            questionDiv.querySelectorAll('[name*="questions["]').forEach(input => {
                input.name = input.name.replace(/questions\[\d+\]/, `questions[${qIdx}]`);
            });

            // Update answer indices within the question
            questionDiv.querySelectorAll('.answer-item').forEach((answerDiv, aIdx) => {
                answerDiv.querySelectorAll('[name*="[answers]"]').forEach(input => {
                    input.name = input.name.replace(/\[answers\]\[\d+\]/, `\[answers\][${aIdx}]`);
                });
                const radio = answerDiv.querySelector('input[type="radio"]');
                if (radio) {
                    radio.value = aIdx;
                }
            });
        });
    };

    // --- Event Delegation (Cara yang lebih efisien) ---
    questionsContainer.addEventListener('click', function(e) {
        // Hapus Pertanyaan
        if (e.target.closest('.remove-question-btn')) {
            e.target.closest('.question-item').remove();
            updateDOMIndices(); // Re-index semua pertanyaan dan jawaban
        }

        // Tambah Jawaban
        if (e.target.closest('.add-answer-btn')) {
            addAnswer(e.target.closest('.question-item'));
        }

        // Hapus Jawaban
        if (e.target.closest('.remove-answer-btn')) {
            const questionDiv = e.target.closest('.question-item');
            const answersList = questionDiv.querySelector('.answers-list');
            if (answersList.children.length > 2) {
                e.target.closest('.answer-item').remove();
                updateDOMIndices(); // Re-index jawaban di dalam pertanyaan ini
            } else {
                alert('Setiap pertanyaan harus memiliki minimal 2 pilihan jawaban.');
            }
        }
    });

    addQuestionBtn.addEventListener('click', addQuestion);

    // Tambahkan pertanyaan pertama saat halaman dimuat
    addQuestion();
});
</script>
@endsection
