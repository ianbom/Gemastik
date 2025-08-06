<?php

namespace App\Http\Middleware;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'notifications' => function () {
                if (Auth::check()) {
                    $userId = Auth::id();
                    $notifications = Notification::where('user_id', $userId)
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $unreadCount = Notification::where('user_id', $userId)
                        ->where('is_read', false)
                        ->count();

                    return [
                        'unread_count' => $unreadCount,
                        'notifications' => $notifications,
                    ];
                }

                return [
                    'unread_count' => 0,
                    'latest' => [],
                ];
            },
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
                'info' => fn() => $request->session()->get('info'),
                'warning' => fn() => $request->session()->get('warning'),
            ],
        ];
    }
}
