<?php

namespace App;

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
        'user_id',
    ];

    public static $rules = [
        'firstName' => 'required',
        'lastName' => 'required',
        'email' => 'required|email',
        'message' => 'required',
        'phoneNumber' => 'required',
        // 'userId' => 'sometimes|exists:users,id',
    ];

    public static $messages = [

    ];

    public static function initialize(array $data)
    {
        $instance = new static;
        $payload = [
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'email' => $data['email'],
            'phone_number' => $data['phoneNumber'],
            'message' => $data['message'],
            'user_id' => auth()->check() ? request()->user()->id: null
        ];
        $instance->data = $payload;

        $instance->create($payload);

        return $instance;

    }

    public function fire()
    {
        \Mail::to(config('mail.to'))
            ->send(new ContactUsMail($this->data));
        // ->queue(new ContactUsMail($this->data));
    }
}