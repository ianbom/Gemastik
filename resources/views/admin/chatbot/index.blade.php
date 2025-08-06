@extends('admin.layouts.app')

@section('content')

     <style>
        /* Animasi untuk indikator mengetik */
        .typing-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: white;
            border-radius: 50%;
        }
    </style>

    <div class="w-full max-w-2xl bg-white rounded-lg shadow-2xl flex flex-col h-[95vh] md:h-[90vh]">

        <!-- Header Chat -->
        <div class="p-4 border-b bg-blue-600 text-white rounded-t-lg flex items-center space-x-3 shadow-md">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.25 9.75 19.5 12m0 0 2.25 2.25M19.5 12l-2.25 2.25m-10.5-6 4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z"></path></svg>
            <div>
                <h1 class="text-xl font-bold">Asisten AI</h1>
                <p class="text-xs text-blue-200">Ditenagai oleh Gemini</p>
            </div>
        </div>

        <!-- Riwayat Percakapan (Chat History) -->
        <div id="chat-history" class="flex-1 p-6 space-y-4 overflow-y-auto bg-gray-50">
            <!-- Pesan pembuka dari bot -->
            <div class="flex items-start gap-3">
                <div class="bg-blue-500 text-white p-3 rounded-xl max-w-md">
                    <p>Halo! Saya adalah asisten AI yang siap membantu Anda. Apa yang ingin Anda tanyakan hari ini?</p>
                </div>
            </div>
        </div>

        <!-- Form Input Pesan -->
        <div class="p-4 border-t bg-white rounded-b-lg">
            <form id="chat-form" class="flex items-center space-x-3">
                <input type="text" id="prompt-input" placeholder="Ketik pertanyaan Anda di sini..." autocomplete="off"
                       class="flex-1 w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 transition-shadow">

                <button type="submit" id="send-button"
                        class="p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:bg-blue-400 disabled:cursor-wait transition-colors">

                    <!-- Ikon Kirim -->
                    <svg id="send-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>

                    <!-- Ikon Spinner (Loading) -->
                    <svg id="spinner-icon" class="animate-spin h-6 w-6 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

@endsection



@push('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Ambil semua elemen interaktif dari DOM
    const chatForm = document.getElementById('chat-form');
    const promptInput = document.getElementById('prompt-input');
    const chatHistory = document.getElementById('chat-history');
    const sendButton = document.getElementById('send-button');
    const sendIcon = document.getElementById('send-icon');
    const spinnerIcon = document.getElementById('spinner-icon');

    // Ambil CSRF token dari meta tag untuk keamanan
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Tambahkan event listener untuk form submission
    chatForm.addEventListener('submit', async function (e) {
        e.preventDefault(); // Mencegah form dari refresh halaman

        const prompt = promptInput.value.trim();
        if (prompt === '') return; // Jangan kirim pesan kosong

        // Tampilkan pesan pengguna di layar dan bersihkan input
        appendMessage(prompt, 'user');
        promptInput.value = '';

        // Tampilkan indikator "mengetik" dari bot dan aktifkan status loading
        const typingIndicator = showTypingIndicator();
        toggleLoading(true);

        try {
            // Kirim request ke backend menggunakan Fetch API
            const response = await fetch("/api/chatbot/send", { // Perhatikan URL baru
            method: 'POST',
            headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', // Tambahkan header ini sebagai praktik baik
                        'X-CSRF-TOKEN': csrfToken, // CSRF tetap bisa digunakan jika perlu, tapi tidak wajib di api.php
                     },
            body: JSON.stringify({ prompt: prompt }),
        });

            // Hapus indikator "mengetik" setelah respons diterima
            chatHistory.removeChild(typingIndicator);

            const data = await response.json();

            if (!response.ok || !data.success) {
                // Jika respons gagal atau ada flag error dari backend
                throw new Error(data.error || 'Terjadi kesalahan pada server.');
            }

            // Tampilkan respons dari bot
            appendMessage(data.response, 'bot');

        } catch (error) {
            console.error('Chatbot Error:', error);
            // Tampilkan pesan error yang user-friendly di dalam chat
            appendMessage('Oops, sepertinya terjadi gangguan. Mohon coba lagi sesaat lagi.', 'bot-error');
        } finally {
            // Selalu matikan status loading di akhir, baik sukses maupun gagal
            toggleLoading(false);
        }
    });

    /**
     * Fungsi untuk menambahkan gelembung pesan ke dalam riwayat chat.
     * @param {string} message - Isi pesan.
     * @param {string} sender - Pengirim pesan ('user', 'bot', atau 'bot-error').
     */
    function appendMessage(message, sender) {
        const messageWrapper = document.createElement('div');
        const messageBubble = document.createElement('div');

        // Ganti karakter newline (\n) dengan tag <br> agar bisa tampil di HTML
        messageBubble.innerHTML = `<p>${message.replace(/\n/g, '<br>')}</p>`;

        // Atur style berdasarkan pengirim
        if (sender === 'user') {
            messageWrapper.className = 'flex items-start gap-3 justify-end';
            messageBubble.className = 'bg-gray-200 text-gray-800 p-3 rounded-xl max-w-md';
        } else if (sender === 'bot-error') {
            messageWrapper.className = 'flex items-start gap-3';
            messageBubble.className = 'bg-red-500 text-white p-3 rounded-xl max-w-md';
        } else { // 'bot'
            messageWrapper.className = 'flex items-start gap-3';
            messageBubble.className = 'bg-blue-500 text-white p-3 rounded-xl max-w-md';
        }

        messageWrapper.appendChild(messageBubble);
        chatHistory.appendChild(messageWrapper);

        // Otomatis scroll ke pesan terbaru
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    /**
     * Fungsi untuk menampilkan animasi "mengetik".
     * @returns {HTMLElement} Elemen indikator yang bisa dihapus nanti.
     */
    function showTypingIndicator() {
        const typingWrapper = document.createElement('div');
        typingWrapper.className = 'flex items-start gap-3';
        typingWrapper.innerHTML = `
            <div class="bg-blue-500 p-3 rounded-xl flex items-center space-x-1">
                <span class="typing-dot animate-bounce"></span>
                <span class="typing-dot animate-bounce" style="animation-delay: 0.2s;"></span>
                <span class="typing-dot animate-bounce" style="animation-delay: 0.4s;"></span>
            </div>
        `;

        chatHistory.appendChild(typingWrapper);
        chatHistory.scrollTop = chatHistory.scrollHeight;
        return typingWrapper;
    }

    /**
     * Fungsi untuk mengaktifkan/menonaktifkan status loading pada form.
     * @param {boolean} isLoading - True jika sedang loading, false jika tidak.
     */
    function toggleLoading(isLoading) {
        promptInput.disabled = isLoading;
        sendButton.disabled = isLoading;
        sendIcon.classList.toggle('hidden', isLoading);
        spinnerIcon.classList.toggle('hidden', !isLoading);
    }
});
</script>
@endpush
