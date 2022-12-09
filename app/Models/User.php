<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable ,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // relationships

    public function checkins()
    {
        return $this->hasMany(Checkin::class, 'file_checkin');
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function files()
    {
        return $this->hasMany(File::class,'owner_id');
    }

    public function ownerGroups()
    {
        return $this->hasMany(Group::class,'owner_id');
    }

    public function modifications()
    {
        return $this->hasMany(Modification::class);
    }

    public function accessGroups()
    {
        return $this->belongstoMany(User::class);
    }
}
