<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $primaryKey = 'id'; // or null

    public $incrementing = true;

    protected $appends = [
        'formatted_date',
    ];

    protected $fillable = [
        'read',
        'hash',
        'title',
        'message',
        'sender_id',
        'receiver_id',
        'message_type',
        'message_uuid',
    ];

    public function getFormattedDateAttribute ()
    {
        return \Carbon\Carbon::parse($this->created_at)->diffForHumans();
        // return $this->created_at->format('DD MM D');
    }

    public function sender ()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver ()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
