<?php

namespace App;

use App\Student;
use App\Instructor;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;


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
        'password',
        'status'
    ];

    protected $appends = [
        'role',
        'name',
        'gravatar'
    ];

    // protected $dateFormat = 'Y-m-d';

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


    public function getGravatarAttribute ()
    {
        // dd($this->profile_pic);
        return  $this->profile_pic ?? Gravatar::get($this->email);
    }

    public function userable ()
    {
        return $this->morphTo();
    }

    public function details ()
    {
        return $this->userable();
    }

    public function msuuid ()
    {
        return $this->hasOne(Message::class, 'sender_id', 'id')->where('receiver_id', auth()->user()->id);
    }

    public function mruuid ()
    {
        return $this->hasOne(Message::class, 'receiver_id', 'id')->where('sender_id', auth()->user()->id);
    }

    public function getProfilePicAttribute($value)
    {
        if ($this->provider && preg_match("/^(http|https)/", $value)) return $value;

        return $value
            ? \Storage::url($value).'?='.time()
            : \Gravatar::get($this->email).'?='.time();
    }


    public static function toCSV($type)
    {
        $type = $type === 'instructors' ? Instructor::class : Student::class;
        $users = static::where('userable_type', $type)->get()->map(function ($user) {
            // dd($user->created_at->format('Y-m-d'));
            return [
                $user->name,
                $user->email,
                $user->phone_number,
                $user->status,
                $user->created_at->format('Y-m-d'),
            ];
        });

        return array_merge([
            ['Name', 'Email Address', 'Phone Number', 'Status', 'Sign up date and time']
        ], $users->toArray());
    }

    public function getNameAttribute()
    {
        return $this->first_name .' '. $this->last_name;
    }

    public function getRoleAttribute()
    {
        // return 'instructor';
        return $this->isAnInstructor()
            ? 'instructor'
            : 'admin';
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }


    public static function register ($data)
    {
        $user = static::create($data);
    }

    public function isAnInstructor ()
    {
        return $this->userable_type === Instructor::class;
    }
    
    public function isAStudent ()
    {
        return $this->userable_type === Student::class;
    }
}
