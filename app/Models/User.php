<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'profile_url',
        'province_id',
        'city_id',
        'district_id',
        'google_id',
        'google_token',
        'google_refresh_token',
        'role',
    ];

    protected $guarded = [
        'id'
    ];

    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function chats(){
        return $this->hasMany(Chat::class);
    }

    public function quizAttempt()
    {
        return $this->hasMany(QuizAttempt::class);
    }
    public function quizAttemptAnswer()
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }

    public function reedems()
    {
        return $this->hasMany(Reedems::class);
    }
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the city that owns the user.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the district (district) that owns the user.
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the community created by the user.
     */
    public function community()
    {
        return $this->hasOne(Community::class, 'user_id');
    }

    /**
     * Get the reports reported by the user.
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    /**
     * Get the reports verified by the user.
     */
    public function verifiedReports()
    {
        return $this->hasMany(Report::class, 'verified_by_user_id');
    }

    /**
     * Get the reports completed by the user.
     */
    public function completedReports()
    {
        return $this->hasMany(Report::class, 'completed_by_user_id');
    }

    public function missionVolunteers()
    {
        return $this->hasMany(MissionVolunteer::class);
    }
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges');
    }

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }

    /**
     * Get the comments made by the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the report votes made by the user.
     */
    public function reportVotes()
    {
        return $this->hasMany(ReportVote::class);
    }

    /**
     * Get the donations made by the user.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the paid donations made by the user.
     */ public function paidDonations()
    {
        return $this->hasMany(Donation::class)->where('status', 'paid');
    }

    /**
     * Get the missions created by the user.
     */
    public function createdMissions()
    {
        return $this->hasMany(Mission::class, 'creator_user_id');
    }

    /**
     * Get the missions assigned to the user as a volunteer.
     */
    public function assignedMissions()
    {
        return $this->hasMany(Mission::class, 'assigned_volunteer_id');
    }

    /**
     * The missions the user volunteers in.
     */
    public function volunteeredMissions()
    {
        return $this->belongsToMany(Mission::class, 'mission_volunteers')
            ->withPivot('participation_status', 'is_leader', 'certificate_url', 'awarded_at')
            ->withTimestamps();
    }

    /**
     * Get the mission documentation uploaded by the user.
     */
    public function uploadedMissionDocumentation()
    {
        return $this->hasMany(MissionDocumentation::class, 'uploader_user_id');
    }

    /**
     * Get the contents authored by the user.
     */
    public function contents()
    {
        return $this->hasMany(Content::class, 'author_user_id');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isProfileComplete(): bool
    {

        $requiredFields = [
            'province_id',
            'city_id',
            'district_id',
            'address',
            'phone',
            'name',
            'email',

        ];

        foreach ($requiredFields as $field) {

            if (empty($this->$field)) {
                return false;
            }
        }


        // Tambahan khusus untuk role community
        if ($this->role === 'community') {
            $community = $this->community;

            if (!$community || empty($community->name) || empty($community->description)) {
                return false;
            }
        }

        return true;
    }
}
