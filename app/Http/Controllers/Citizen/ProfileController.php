<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Services\ProfileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Province;
use App\Models\Report;
use Throwable;
use Inertia\Inertia;
use Pest\Plugins\Profile;
use App\Models\Donation;
use App\Models\Point;
use App\Models\UserBadge;
use App\Models\UserCertificate;

class ProfileController extends Controller
{

    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }
    public function showProfile()
    {
        $user = User::with('province', 'city', 'district')->find(Auth::id());
        $myReports = Report::with(['reporter'])->where('reporter_id', $user->id)->get();
        $myReportCount = Report::where('reporter_id', $user->id)->count();
        $myMissions = $user->volunteeredMissions;
        $myMissionCounts = $myMissions->count();
        $myDonations = Donation::with('report')
            ->where('user_id', $user->id)
            ->latest()->get();
        $myPoints = Point::with('pointable')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
        $myCertificates = UserCertificate::with('user')->where('user_id', $user->id)->get();
        $myBadges = UserBadge::with('user', 'badge')->where('user_id', $user->id)->get();
        $myBadgeCounts = $myBadges->count();
        return Inertia::render('Citizen/Profile/ProfilePage', [
            'auth' => [
                'user' => $user,
            ],
            'myReports' => $myReports,
            'myReportsCount' => $myReportCount,
            'myMissions' => $myMissions,
            'myMissionCounts' => $myMissionCounts,
            'myCertificates' => $myCertificates,
            'myDonations' => $myDonations,
            'myPoints' => $myPoints,
            'myBadges' => $myBadges,
            'myBadgeCounts' => $myBadgeCounts
        ]);
    }


    public function completeProfile()
    {
        $user = Auth::user();
        if ($user->role === 'community') {
            $user->load('community');
        }
        $provinces = Province::with('cities.districts')->get();
        if ($user->role === 'community') {
            return Inertia::render('Community/CompleteProfile', [
                'auth' => [
                    'user' => $user
                ],
                'provinces' => $provinces
            ]);
        }
        return Inertia::render('Citizen/CompleteProfile', [
            'auth' => [
                'user' => $user
            ],
            'provinces' => $provinces
        ]);
    }

    public function editProfile()
    {
        $user = Auth::user();
        $provinces = Province::with('cities.districts')->get();
        return Inertia::render('Citizen/Profile/EditProfilePage', [
            'provinces' => $provinces,
            'auth' => [
                'user' => $user
            ]
        ]);
    }
    public function updateProfile(ProfileRequest $request)
    {
        $data = $request->validated();

        try {
            if ($request->hasFile('profile_url')) {
                $file = $request->file('profile_url');
                $path = $file->store('profile_url', 'public');
                $data['profile_url'] = $path;
            }
            $this->profileService->updateProfileData($data);
            return redirect()
                ->route('profile.show')
                ->with('success', 'Profile berhasil diperbarui');
        } catch (Throwable $th) {
            return back()
                ->withErrors(['error' => 'Gagal memperbarui profile. ' . $th->getMessage()])
                ->withInput();
        }
    }
    public function updateCompleteProfile(ProfileRequest $request)
    {
        $data = $request->validated();
        try {
            $user = Auth::user();
            if ($user->role === 'community') {
                $this->profileService->updateProfileDataCommunity($data);
                return redirect()
                    ->route('community.profile.show')
                    ->with('success', 'Data profil berhasil diperbarui');
            } else {
                $this->profileService->updateProfile($data);
                return redirect()
                    ->route('profile.show')
                    ->with('success', 'Data profil berhasil diperbarui');
            }
        } catch (Throwable $th) {
            return back()
                ->withErrors(['error' => 'Gagal memperbarui profil. ' . $th->getMessage()])
                ->withInput();
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success'
        ]);
    }

    public function me()
    {
        $user = Auth::user();
        return response()->json([
            'data' => $user,
        ]);
    }
}
