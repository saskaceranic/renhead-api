<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentApprovals()
    {
        return $this->hasMany(PaymentApproval::class);
    }
}
