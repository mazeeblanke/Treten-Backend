<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_id',
        'name',
        'email',
        'description',
        'amount',
        'transaction_id',
        'user_id',
        'course_id',
        'course_batch_id',
        'authentication_code',
        'status',
        'data'
    ];

    protected $appends = [
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDateAttribute()
    {
        return $this->created_at->format('Y/m/d');
    }

    public function getDataAttribute($value)
    {
        return unserialize($value)
        ? unserialize($value)
        : [];
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = serialize($value);
    }

    public static function toCSV()
    {
        $transactions = static::all()->map(function ($transaction) {

            return [
                $transaction->date,
                $transaction->invoice_id,
                $transaction->name,
                $transaction->email,
                $transaction->description,
                $transaction->amount,
            ];
        });

        return array_merge([
            ['Date', 'Invoice ID', 'Name', 'Email Address', 'Description', 'Amount'],
        ], $transactions->toArray());
    }
}
