<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramMember extends Model
{
    use HasFactory;
    
    protected $table = 'program_members';

    protected $fillable = [
        'user_id',
        'membership_transaction_id',
        'program_data_id',
        'date',
        'status',
    ];

    public function user()
    { 
        return $this->belongsTo(User::class);
    }

    public function membershipTransaction()
    {
        return $this->belongsTo(MembershipTransaction::class);
    }

    public function programData()
    {
        return $this->belongsTo(ProgramData::class);
    }
}
