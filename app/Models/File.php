<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'path',
        'status',
        'owner_id',
    ];

    protected $hidden = ['pivot'];

    // relationships

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function modifications()
    {
        return $this->hasMany(Modification::class);
    }
    
    public function groups()
    {
        return $this->belongstoMany(Group::class);
    }

    public function checkins()
    {
        return $this->belongstoMany(Checkin::class);
    }
}
