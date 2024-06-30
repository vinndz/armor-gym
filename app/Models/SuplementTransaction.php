<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuplementTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'suplement_id',
        'date',
        'amount',
        'total',
    ];

    public function user()
    { 
        return $this->belongsTo(User::class);
    }

    public function suplement()
    {
        return $this->belongsTo(Suplement::class);
    }
}
