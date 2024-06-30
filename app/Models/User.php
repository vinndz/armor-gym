<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'date_of_birth',
        'gender',
        'username',
        'email',
        'password',
        'role',
        'image'
    ];
 
    protected $hidden = [
        'password',
        'remember_token',
    ];
 
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role($value)
    {
        $roles = ["MEMBER", "ADMIN", "OWNER", "INSTRUCTOR", "GUEST"];
        return $roles[$value];
    }

    public function suplements()
    {
        return $this->belongsToMany(Suplement::class, 'suplement_transactions')
                    ->withPivot('date', 'amount', 'total')
                    ->withTimestamps();
    }

    public function image()
    {
        return Storage::url($this->image);
    }
    
    
    
}
