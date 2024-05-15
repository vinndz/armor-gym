<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'membership_transaction_id',
        'program_data_id',
    ];
}
