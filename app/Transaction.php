<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

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
