<?php

use App\Http\Controllers\Admin\ReedemsController as AdmReedemsController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\Citizen\NotificationController;
use App\Http\Controllers\Admin\BadgeController as AdmBadgeController;
use App\Http\Controllers\Admin\CertificateController as AdmCertificateController;
use App\Http\Controllers\Admin\ContentController as AdmContentController;
use App\Http\Controllers\Admin\DashboardController as AdmDashboardController;
use App\Http\Controllers\Admin\MerchandiseController as AdmMerchandiseController;
use App\Http\Controllers\Admin\MissionController as AdmMissionController;
use App\Http\Controllers\Admin\QuizController as AdmQuizController;
use App\Http\Controllers\Admin\ReportController as AdmReportController;
use App\Http\Controllers\Admin\UserController as AdmUserController;
use App\Http\Controllers\Citizen\ChatGroupController as CtzChatGroupController;
use App\Http\Controllers\Citizen\MerchandiseController as CtzMerchandiseController;
use App\Http\Controllers\Citizen\CommentController as CtzCommentController;
use App\Http\Controllers\Citizen\QuizController as CtzQuizController;
use App\Http\Controllers\Citizen\ReportController as CtzReportController;
use App\Http\Controllers\Citizen\ProfileController as CtzProfileController;
use App\Http\Controllers\Citizen\MapController as CtzMapController;
use App\Http\Controllers\Citizen\ContentController as CtzContentController;
use App\Http\Controllers\Citizen\DonationController as CtzDonationController;
use App\Http\Controllers\Citizen\MissionController as CtzMissionController;
use App\Http\Controllers\Community\ProfileController as ComProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('homepage');
});
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::middleware(['auth'])->group(function () {
    Route::get('/complete-profile', [CtzProfileController::class, 'completeProfile'])->name('profile.complete');
    Route::post('/complete-profile', [CtzProfileController::class, 'updateCompleteProfile'])->name('profile.complete.update');
});
Route::get('/dashboard', function () {
    return redirect()->route('homepage');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route untuk akses fitur peran warga
Route::prefix('')->middleware(['auth'])->group(function () {
    // Route landing page
    Route::get('/homepage', function () {
        return Inertia::render('Citizen/HomePage');
    })->name('homepage');
    // Route untuk keperluan yang berkaitan dengan Notifikasi
    Route::put('/read-notification/{id}', [NotificationController::class, 'readNotification'])->name('notification.read');
    Route::put('/read/all/notification', [NotificationController::class, 'readAllNotification'])->name('notification.readAll');
    Route::delete('/delete/notification/{id}', [NotificationController::class, 'destroy'])->name('notification.delete');
    // Route untuk keperluan yang berkaitan dengan Profil
    Route::get('/profile', [CtzProfileController::class, 'showProfile'])->name('profile.show');
    Route::get('/edit-profile', [CtzProfileController::class, 'editProfile'])->name('profile.edit');
    Route::post('/update-profile', [CtzProfileController::class, 'updateProfile'])->name('profile.update');
    // Route untuk keperluan yang berkaitan dengan Laporan
    Route::get('/report', [CtzReportController::class, 'viewAllReportsPage'])->name('report');
    Route::get('/my-report', [CtzReportController::class, 'viewMyReportsPage'])->name('my-report');
    Route::get('/report/{id}', [CtzReportController::class, 'show'])->name('report.show');
    Route::get('/report-create', [CtzReportController::class, 'create'])->name('create.report');
    Route::post('/reports', [CtzReportController::class, 'store'])->name('reports.store');
    Route::post('/reports/{report}/vote', [CtzReportController::class, 'vote'])->name('report.vote');
    // Route untuk keperluan yang berkaitan dengan Donasi
    Route::post('/donate/report/{id}', [CtzDonationController::class, 'donateReport'])->name('donate.report');
    Route::get('/my-donations', [CtzDonationController::class, 'viewMyDonations'])->name('my-donations');
    // Route untuk keperluan yang berkaitan dengan Komentar
    Route::post('comments/store', [CtzCommentController::class, 'store'])->name('comments.store');
    // Route untuk keperluan yang berkaitan dengan misi
    Route::get('/mission', [CtzMissionController::class, 'index'])->name('mission');

    Route::get('/my-mission', [CtzMissionController::class, 'myMissions'])->name('my-mission');
    Route::post('/join-missions/{id}', [CtzMissionController::class, 'join'])->name('mission.join');
    Route::post('/attendance-members', [CtzMissionController::class, 'attend'])->name('attendance.store');
    Route::delete('/volunteers/{mission}', [CtzMissionController::class, 'cancel'])->name('volunteer.cancel');
    Route::post('/mission/media-documentation/upload', [CtzMissionController::class, 'uploadDocumentation'])->name('mission.documentation.upload');
    // Route untuk keperluan yang berkaitan dengan Peta
    Route::get('/map', [CtzMapController::class, 'indexMap'])->name('map.index');
    // Route untuk keperluan yang berkaitan dengan Konten Edukasi
    Route::get('/education', [CtzContentController::class, 'index'])->name('content.index');
    Route::get('/education/{id}', [CtzContentController::class, 'show'])->name('content.show');
    // Route untuk keperluan yang berkaitan dengan Merchandise
    Route::resource('merchandise', CtzMerchandiseController::class);
    Route::get('/my-merchandise', [CtzMerchandiseController::class, 'viewMyMerchandise'])->name('my-merchandise');
    // Route untuk keperluan yang berkaitan dengan Quiz
    Route::resource('quiz', CtzQuizController::class);
    Route::get('/my-quiz-attempt', [CtzQuizController::class, 'viewMyQuiz'])->name('my-quiz');
    Route::post('quiz-submit/{quiz}', [CtzQuizController::class, 'submit'])->name('quiz.submit');
    Route::get('quiz-result', [CtzQuizController::class, 'result'])->name('quiz.result');
    // Route untuk keperluan yang berkaitan dengan Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'indexLeaderboard'])->name('leaderboard.indexLeaderboard');

    Route::resource('chatgroup', CtzChatGroupController::class);
    Route::post('/chat-groups/{chatGroup}/messages', [CtzChatGroupController::class, 'storeMessage'])
    ->name('chat-groups.messages.store');
});

// Route untuk akses fitur peran komunitas
Route::prefix('community')->as('community.')->middleware(['auth'])->group(function () {
    Route::get('/profile', [ComProfileController::class, 'showProfile'])->name('profile.show');
    Route::get('/edit-profile', [ComProfileController::class, 'editProfile'])->name('profile.edit');
    Route::post('/update-profile', [ComProfileController::class, 'updateProfile'])->name('profile.update');
});

// Route untuk akses fitur peran admin
Route::prefix('admin')->as('admin.')->middleware(['auth'])->group(function () {
    Route::resource('missions', AdmMissionController::class);
    Route::put('missions/update/volunteer/{missionVolunteer}', [AdmMissionController::class, 'updateStatusVolunteer'])->name('update.volunteerStatus');
    Route::put('missions/share-point/{mission}', [AdmMissionController::class, 'shareMissionPoint'])->name('missions.sharePoint');
    Route::resource('reports', AdmReportController::class);
    Route::put('reject-report/{report}', [AdmReportController::class, 'rejectReport'])->name('reports.reject');
    Route::put('accept-report/{report}', [AdmReportController::class, 'acceptReport'])->name('reports.accept');
    Route::put('authority-report/{report}', [AdmReportController::class, 'underAuthority'])->name('reports.underAuthority');
    Route::put('{id}/toggle-donation', [AdmReportController::class, 'toggleDonation'])->name('reports.toggle-donation');
    Route::resource('contents', AdmContentController::class);
    Route::delete('content-media/{contentMedia}', [AdmContentController::class, 'deleteMedia'])->name('delete.contentMedia');
    Route::resource('badges', AdmBadgeController::class);
    Route::resource('users', AdmUserController::class);
    Route::get('certificates/generate', [AdmCertificateController::class, 'generateCertificate'])->name('certificate.generate');
    Route::post('/missions/certificates/generate', [AdmCertificateController::class, 'generate'])->name('missions.certificates.generate');
    Route::resource('certificates', AdmCertificateController::class);
    Route::resource('dashboard', AdmDashboardController::class);
    Route::resource('merchandise', AdmMerchandiseController::class);
    Route::resource('quizzes', AdmQuizController::class);
    Route::resource('redeems', AdmReedemsController::class);
    Route::resource('chatbot', ChatBotController::class);
    Route::get('test', [ChatBotController::class, 'testQuery']);
});

require __DIR__ . '/auth.php';
