<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymSchedule extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'membership_transaction_id',
        'date',
        'status',
    ];

    protected $casts = [ "date" => "datetime:Y-m-d\TH:i:s" ];

    public function user()
    { 
        return $this->belongsTo(User::class);
    }

    public function membershipTransaction()
    { 
        return $this->belongsTo(MembershipTransaction::class);
    }
}
