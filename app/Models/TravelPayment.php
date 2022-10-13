<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->morphMany(PaymentApproval::class, 'payment');
    }
}
