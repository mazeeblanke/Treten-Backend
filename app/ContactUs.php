<?php

namespace App;

use App\Jobs\ProcessContactUsMail;
use App\Mail\ContactUsMail;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $data = [];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'message',
        'phone_number',
        'user_id'
    ];

    public static $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'message' => 'required',
        'phone_number' => 'required',
        'user_id' => 'sometimes|exists:users,id',
    ]; 

    public static $messages = [

    ];

    public static function initialize (Array $data)
    {
        $instance = new static;
        $instance->data = $data;

        $instance->create($data);
        
        return $instance;

    }

    public function fire()
    {
        \Mail::to(config('mail.to'))
            ->queue(new ContactUsMail($this->data)); 
    }
}
