<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_name',
        'email',        // تم إضافة البريد الإلكتروني
        'phone',        // تم إضافة رقم الهاتف
        'type',
        'quantity',
        'status',
    ];

    /**
     * Get the distributions for the donation.
     */
    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }
}
