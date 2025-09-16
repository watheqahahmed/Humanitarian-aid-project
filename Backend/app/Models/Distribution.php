<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_id',
        'beneficiary_id',
        'donation_id',
        'delivery_status',
        'proof_file',
    ];

    /**
     * Get the volunteer that delivered the distribution.
     */
    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    /**
     * Get the beneficiary who received the distribution.
     */
    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id');
    }

    /**
     * Get the donation that was distributed.
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
