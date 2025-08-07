<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatGroupController extends Controller
{

    public function index()
    {

        $userId = Auth::id();
        $chatGroups = ChatGroup::with([
            'mission',
            'chats.user' => function ($query) {
                $query->latest()->limit(1);
            }
        ])
            ->whereHas('mission.volunteers', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereIn('participation_status', ['confirmed', 'attended', 'pending']);
            })
            ->get();

        // return response()->json([
        //     'chatGroups' => $chatGroups
        // ]);

        return Inertia::render('Citizen/ChatGroup/ChatGroup', ['chatGroups' => $chatGroups]);
    }

    public function show($id)
    {
        try {
            $userId = Auth::id();
            $chatGroup = ChatGroup::findOrFail($id);
            // $hasAccess = $chatGroup->mission->volunteers()
            //     ->where('user_id', $userId)
            //     ->whereIn('participation_status', ['confirmed', 'attended'])
            //     ->exists();

            // if (!$hasAccess) {
            //     return response()->json(['error' => 'Unauthorized'], 403);
            // }

            $messages = Chat::where('chat_group_id', $chatGroup->id)
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get();
            $allGroups = ChatGroup::with([
                'mission',
                'chats.user' => function ($query) {
                    $query->latest()->limit(1);
                }
            ])
                ->whereHas('mission.volunteers', function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->whereIn('participation_status', ['confirmed', 'attended', 'pending']);
                })
                ->get();

            return Inertia::render('Citizen/ChatGroup/DetailChatGroup', [
                'messages' => $messages,
                'allGroups' => $allGroups,
                'activeGroupId' => $id,
                'currentUserId' => auth()->id(),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data pesan',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function storeMessage(Request $request, $chatGroupId)
    {
        try {
            $userId = Auth::id();

            // Validate request
            $request->validate([
                'content' => 'nullable|string|max:1000',
                'media_url' => 'nullable',
                'media_type' => 'nullable',
                'reply_id' => 'nullable|exists:chats,id'
            ]);

            // Check if user has access to this chat group
            $chatGroup = ChatGroup::findOrFail($chatGroupId);

            // Uncomment if you want to check access
            // $hasAccess = $chatGroup->mission->volunteers()
            //     ->where('user_id', $userId)
            //     ->whereIn('participation_status', ['confirmed', 'attended'])
            //     ->exists();
            // if (!$hasAccess) {
            //     return response()->json(['error' => 'Unauthorized'], 403);
            // }

            if ($request->has('media_url')) {
                $filePath = $request->file('media_url')->store('chat_media', 'public');
                $mediaType = $this->getMediaType($request->file('media_url'));
            } else {
                $filePath = null;
                $mediaType = null; // Default to null if no file is uploaded
            }

            $message = Chat::create([
                'chat_group_id' => $chatGroupId,
                'user_id' => $userId,
                'content' => $request->content,
                'media_url' => $filePath,
                'media_type' => $mediaType,
                'reply_id' => $request->reply_id,
                'created_at' => now()
            ]);

            // Load the user relationship
            $message->load('user');

            // You can broadcast the message here if using real-time chat
            // broadcast(new MessageSent($message))->toOthers();

            return redirect()->back()->with('success', 'Pesan berhasil dikirim');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim pesan',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    private function getMediaType($file): string
    {
        if (!$file || !$file->isValid()) {
            return 'image'; // default fallback
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'wmv'];
        $documentExtensions = ['pdf'];

        if (in_array($extension, $imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $videoExtensions)) {
            return 'video';
        } elseif (in_array($extension, $documentExtensions)) {
            return 'document';
        }

        return 'image';
    }

    public function sendMessage(Request $request, ChatGroup $chatGroup)
    {
        // Check if user has access to this chat group
        $userId = Auth::id();
        $hasAccess = $chatGroup->mission->volunteers()
            ->where('user_id', $userId)
            ->whereIn('participation_status', ['confirmed', 'attended'])
            ->exists();

        if (!$hasAccess) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = Chat::create([
            'chat_group_id' => $chatGroup->id,
            'user_id' => $userId,
            'content' => $request->content,
        ]);

        $message->load(['user:id,name,profile_url']);

        return response()->json([
            'message' => $message
        ]);
    }
}
