<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProgramData extends Model
{
    use HasFactory;

    protected $table = 'program_datas';
    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    public function programMembers()
    {
        return $this->hasMany(ProgramMember::class, 'program_data_id');
    }

    public function image()
    {
        return Storage::url($this->image);
    }

}
