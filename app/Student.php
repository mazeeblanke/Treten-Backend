<?php

namespace App;

use App\Interfaces\Registrable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model implements Registrable
{
    //
    use SoftDeletes;


    protected $fillable = [
        'best_instructor',
    ];

    public static $rules = [
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'as' => ['required', 'string', 'in:student,instructor'],
        'phone_number' => ['required', 'string'],
        'other_name' => ['string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,provider,provider_id'],
        'password' => ['required', 'string', 'min:8'],
    ];

    public static function register ($data)
    {
        $student = static::create($data);
        return $student->details;
    }

    public function details ()
    {
        return $this->morphOne(User::class, 'userable');
    }
}
