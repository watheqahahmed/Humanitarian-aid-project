<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AidRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary_id',
        'type',
        'description',
        'document_url',
        'status',
    ];

    /**
     * Get the beneficiary that owns the aid request.
     */
    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id');
    }

    /**
     * Get the distributions for the aid request.
     */
    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }
}
