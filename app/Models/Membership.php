<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 
        'price', 
        'description',
    ];

    public function membershipTransactions()
    {
        return $this->hasMany(MembershipTransaction::class, 'membership_id');
    }

    
}
