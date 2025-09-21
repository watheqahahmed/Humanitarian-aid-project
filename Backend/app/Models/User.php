<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\AidRequest;
use App\Models\Distribution;
use App\Models\Notification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // علاقة مع طلبات المساعدة (المستفيد)
    public function aidRequests()
    {
        return $this->hasMany(AidRequest::class, 'beneficiary_id');
    }

    // علاقة مع التوزيعات المسندة (المتطوع)
    public function assignedDeliveries()
    {
        return $this->hasMany(Distribution::class, 'volunteer_id');
    }

    // علاقة الإشعارات
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
