<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GeminiService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatBotController extends Controller
{

    protected $geminiService;

    public function __construct(GeminiService $geminiService){
        $this->geminiService = $geminiService;
    }

    public function index(){

        return view('admin.chatbot.index');
    }

    public function send(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
        ]);

        try {

            $prompt = $request->input('prompt');
            $responseMessage = $this->geminiService->generateText($prompt);

            return response()->json([
                'success' => true,
                'response' => $responseMessage,
            ]);

        } catch (Exception $e) {

            Log::error('Chatbot Controller Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Maaf, sepertinya ada masalah di pihak kami. Silakan coba lagi nanti.',
            ], 500);
        }
    }

    public function testQuery(){
        try {
             $eligibleUsers = User::withCount(['missionVolunteers' => function ($query) {

            $query->where('participation_status', 'attended');
        }])
        ->having('mission_volunteers_count', '>=', 5)
        ->get();

        return response()->json(['users' => $eligibleUsers]);
        } catch (\Throwable $th) {
           return response()->json(['err' => $th->getMessage()]);
        }

    }
}
