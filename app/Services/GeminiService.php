<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';
    protected ChatbotDataService $dataService;

    public function __construct(ChatbotDataService $dataService)
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->dataService = $dataService;

        if (is_null($this->apiKey)) {
            throw new Exception('GEMINI_API_KEY tidak ditemukan di environment Anda.');
        }
    }

    private function getSobatBumiContext(): string
    {
        // --- PERUBAHAN DI SINI ---
        return "Anda adalah \"Asisten SobatBumi\", sebuah chatbot AI yang ramah dan informatif. Tugas Anda adalah membantu pengguna memahami dan menggunakan platform SobatBumi.\n\n" .
               "SobatBumi adalah platform web kolaboratif di Indonesia yang bertujuan untuk mengatasi masalah lingkungan melalui aksi nyata. Platform ini dibuat dengan bangga oleh 'Tim Kebut Semalam'.\n\n" . // <--- TAMBAHKAN INFORMASI INI
               "Berikut adalah informasi kunci tentang SobatBumi:\n" .
               "1. **Misi Utama**: Menjembatani warga yang peduli dengan komunitas dan sukarelawan untuk membersihkan dan menjaga lingkungan.\n" .
               "2. **Cara Kerja Utama**: Warga membuat Laporan -> Admin memverifikasi -> Laporan diubah menjadi Misi -> Sukarelawan bergabung dalam Misi.\n" .
               "3. **Fitur Gamifikasi**: Pengguna mendapatkan Poin dan Lencana (Badges) untuk aksi positif. Poin dapat ditukarkan dengan Merchandise.\n" .
               "4. **Fitur Lainnya**: Donasi untuk mendukung misi, Merhcandise yang dapat ditukarkan, dan Kuis edukatif tentang lingkungan.\n\n" .
               "**Aturan Jawaban Anda**:\n" .
               "- Selalu jawab dari sudut pandang Asisten SobatBumi.\n" .
               "- Gunakan 'alat' yang tersedia untuk menjawab pertanyaan tentang data real-time (laporan, misi, dll.).\n" .
               "- Jika Anda tidak tahu, katakan dengan jujur Anda tidak memiliki informasi tersebut.";
    }

    /**
     * Mendefinisikan "alat" (fungsi) yang bisa dipanggil oleh Gemini.
     * @return array
     */
    private function defineTools(): array
    {
        return [
            [
                'function_declarations' => [
                    [
                        'name' => 'searchReports',
                        'description' => 'Mencari laporan lingkungan di database berdasarkan kata kunci, status, kategori, atau nama provinsi.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'keyword' => ['type' => 'STRING', 'description' => 'Kata kunci untuk dicari.'],
                                'status' => ['type' => 'STRING', 'description' => 'Status laporan (pending, verified, dll.).'],
                                'category' => ['type' => 'STRING', 'description' => 'Kategori laporan.'],
                                'provinceName' => ['type' => 'STRING', 'description' => 'Nama provinsi lokasi.'],
                            ],
                        ],
                    ],
                    [
                        'name' => 'searchMissions',
                        'description' => 'Mencari misi aksi lingkungan berdasarkan kata kunci, status, atau provinsi. Default status adalah "open".',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'keyword' => ['type' => 'STRING', 'description' => 'Kata kunci untuk dicari.'],
                                'status' => ['type' => 'STRING', 'description' => 'Status misi (open, completed, dll.).'],
                                'provinceName' => ['type' => 'STRING', 'description' => 'Nama provinsi lokasi.'],
                            ],
                        ],
                    ],
                    [
                        'name' => 'searchContents',
                        'description' => 'Mencari konten edukasi (artikel, video, modul) berdasarkan kata kunci atau tipe.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'keyword' => ['type' => 'STRING', 'description' => 'Topik atau kata kunci konten.'],
                                'type' => ['type' => 'STRING', 'description' => 'Tipe konten: "artikel", "video", atau "modul".'],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function generateText(string $userPrompt): string
    {
        $url = $this->baseUrl . '?key=' . $this->apiKey;
        $maxTurns = 5; // Batasi jumlah panggilan bolak-balik untuk mencegah infinite loop
        $currentTurn = 0;

        // Inisialisasi riwayat percakapan untuk sesi ini
        $history = [
            // Prompt awal dari pengguna dengan konteks
            ['role' => 'user', 'parts' => [['text' => $this->getSobatBumiContext() . "\n\nPertanyaan: " . $userPrompt]]],
        ];

        while ($currentTurn < $maxTurns) {
            $currentTurn++;

            $response = Http::post($url, [
                'contents' => $history,
                'tools' => $this->defineTools(),
            ]);

            if ($response->failed()) {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? $response->body();
                throw new Exception('Gagal berkomunikasi dengan Gemini API: ' . $errorMessage);
            }

            // Tambahkan respons dari model ke riwayat
            $modelResponse = $response->json('candidates.0.content');
            $history[] = $modelResponse;

            // Periksa apakah model ingin memanggil fungsi
            if (isset($modelResponse['parts'][0]['functionCall'])) {
                $functionCall = $modelResponse['parts'][0]['functionCall'];
                $functionName = $functionCall['name'];
                $arguments = $functionCall['args'] ?? [];

                // Eksekusi fungsi lokal
                $functionResult = $this->executeFunction($functionName, $arguments);

                // Tambahkan hasil fungsi ke riwayat untuk dikirim kembali ke model
                $history[] = [
                    'role' => 'tool',
                    'parts' => [
                        [
                            'functionResponse' => [
                                'name' => $functionName,
                                'response' => [
                                    'content' => $functionResult,
                                ],
                            ],
                        ],
                    ],
                ];

                // Lanjutkan loop untuk mendapatkan respons teks dari model
                continue;
            }

            // Jika tidak ada function call, berarti ini adalah jawaban akhir
            return $modelResponse['parts'][0]['text'] ?? 'Maaf, saya tidak dapat memproses jawaban saat ini.';
        }

        // Jika loop melebihi batas (jarang terjadi)
        return 'Maaf, terjadi masalah saat mencoba memproses permintaan Anda secara berulang.';
    }

    private function executeFunction(string $name, array $arguments): mixed
    {
        if (!method_exists($this->dataService, $name)) {
            Log::warning("Gemini mencoba memanggil fungsi yang tidak ada: {$name}");
            return "Fungsi '{$name}' tidak ditemukan.";
        }

        Log::info("Gemini memanggil fungsi: {$name} dengan argumen: ", $arguments);
        return $this->dataService->{$name}(...$arguments);
    }
}
