<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramData extends Model
{
    use HasFactory;

    protected $table = 'program_datas';
    protected $fillable = [
        'name',
        'description',
    ];
}
