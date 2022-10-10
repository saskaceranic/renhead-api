<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'payment_type',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function travelPayments()
    {
        return $this->belongsTo(TravelPayment::class);
    }

    public function scopeApprovedPayments($query)
    {
        return $query->where('status', 'approved');
    }
}
