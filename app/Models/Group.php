<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'owner_id',
    ];

    //relationships

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function files()
    {
        return $this->belongstoMany(File::class);
    }

    public function users()
    {
        return $this->belongstoMany(User::class,'owner_id');
    }
}
