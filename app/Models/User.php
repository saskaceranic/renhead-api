<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'type',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const APPROVER_TYPE = 'approver';
    const ADMIN_TYPE = 'admin';

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function travelPayments()
    {
        return $this->hasMany(TravelPayment::class);
    }

    public function paymentApprovals()
    {
        return $this->hasMany(PaymentApproval::class);
    }

    public function scopeApprover($query)
    {
        return $query->where('type', 'approver');
    }

    public function scopeAdmin($query)
    {
        return $query->where('type', 'admin');
    }

    public function isAdmin()
    {
        return $this->type === self::ADMIN_TYPE;
    }

    public function isApprover()
    {
        return $this->type === self::APPROVER_TYPE;
    }
}
