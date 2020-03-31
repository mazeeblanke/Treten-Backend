<?php

namespace App;

use App\Traits\Filterable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use Filterable;
    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'other_name',
        'phone_number',
        'profile_pic',
        'provider',
        'provider_id',
        'email',
        'title',
        'password',
        'status'
    ];

    /**
     * The attributes to include on every request
     *
     * @var array
     */
    protected $appends = [
        'role',
        'name',
        'gravatar',
        'friendly_created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the full name of a user
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the gravatar image of the user
     *
     * @return string
     */
    public function getGravatarAttribute(): string
    {
        return  $this->profile_pic ?? Gravatar::get($this->email);
    }

    /**
     * Get friendly version of the created at field
     *
     * @return string
     */
    public function getFriendlyCreatedAtAttribute(): string
    {
        return $this->created_at->format('d M Y');
    }

    /**
     * Logic to determine the profile picture
     *
     * @param [type] $value
     * @return string
     */
    public function getProfilePicAttribute($value)
    {
        if ($this->provider && preg_match("/^(http|https)/", $value)) return $value;
        return $value
            ? \Storage::url($value) . '?=' . time()
            : \Gravatar::get($this->email) . '?=' . time();
    }

    /**
     * Relationship to more user details
     *
     * @return MorphTo
     */
    public function userable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Determine the role of a user
     *
     * @return string
     */
    public function getRoleAttribute(): string
    {
        // dd($this->roles->first()->name);
        return optional($this->roles->first())->name ?? '';
        // return auth()->check()
        //     ? optional($this->roles->first())->name ?? ''
        //     : '';
    }

    public function details()
    {
        return $this->userable();
    }

    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'user_group_allocations', 'user_id', 'user_group_id');
    }

     /**
     * Relationship for message sender uuid
     *
     * @return HasOne
     */
    public function msuuid(): HasOne
    {
        return $this
            ->hasOne(Message::class, 'sender_id', 'id')
            ->where(function ($query) {
                if (auth()->check())
                {
                    return $query->where('receiver_id', auth()->user()->id);
                }
                return $query;
            });
    }

    /**
     * Relationship fo message receiver uuid
     *
     * @return HasOne
     */
    public function mruuid(): HasOne
    {
        return $this
            ->hasOne(Message::class, 'receiver_id', 'id')
            ->where(function ($query) {
                if (auth()->check())
                {
                    return $query->where('sender_id', auth()->user()->id);
                }
                return $query;
            });
    }

    public function scopeHasCourseTimetable ($query)
    {
        return $query
            ->where(function ($query) {
                $query
                    ->orWhere('timetable', '!=', 'a:0:{}')
                    ->orWhere('timetable', '!=', null);
            })
            ->where('users.userable_type', '!=', null);
    }

    public function scopeDoesntHaveCourseTimetable ($query)
    {
        return $query
            ->where(function ($query) {
                $query
                    ->orWhere('timetable', 'a:0:{}')
                    ->orWhere('timetable', null);
            })
            ->where('users.userable_type', null);
    }

    /**
     * Get a CSV array format of the user
     *
     * @param [type] $type
     * @return array
     */
    public static function toCSV($type): array
    {
        $users = static::whereHas('roles', function ($query) use ($type) {
            return $query->where('roles.name', \Str::singular($type));
        })->get()->map(function ($user) {
            return [
                $user->name,
                $user->email,
                $user->phone_number,
                $user->status,
                $user->created_at->format('Y-m-d'),
            ];
        });

        return array_merge([
            [
                'Name',
                'Email Address',
                'Phone Number',
                'Status',
                'Sign up date and time'
            ]
        ], $users->toArray());
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Register a new user
     *
     * @param [type] $data
     * @return void
     */
    public static function register($data): void
    {
        static::create($data);
    }

    /**
     * Check if a uer is an instructor
     *
     * @return boolean
     */
    public function isAnInstructor(): bool
    {
        // return $this->userable_type === Instructor::class;
        return $this->roles->first()->name === 'instructor';
    }

    /**
     * Check if a user is a student
     *
     * @return boolean
     */
    public function isAStudent(): bool
    {
        // return $this->userable_type === Student::class;
        return $this->roles->first()->name === 'student';
    }

    /**
     * Check if a user is an admin
     *
     * @return boolean
     */
    public function isAnAdmin(): bool
    {
        // return $this->userable_type === Student::class;
        return $this->roles->first()->name === 'admin';
    }

    /**
     *
     * scope admin
     *
     */
    public function scopeAdmin ($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->whereName('admin');
        });
    }

    /**
     * Check if a user is an admin
     *
     * @return boolean
     */
    public function isNotActive(): bool
    {
        return $this->status != 'active';
    }
}
