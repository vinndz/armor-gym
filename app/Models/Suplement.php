<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Suplement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'price',
        'description',
        'image'
    ];

    public function transactions()
    {
        return $this->belongsToMany(User::class, 'suplement_transactions');
    }


    public function image()
    {
        return Storage::url($this->image);
    }
}
