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
        return $this->morphTo(__FUNCTION__, 'payment_type', 'payment_id');
    }

    public function scopeApprovedPayments($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeDisapprovedPayments($query)
    {
        return $query->where('status', 'disapproved');
    }
}
