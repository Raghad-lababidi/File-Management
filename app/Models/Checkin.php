<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
    ];

    protected $hidden = ['pivot'];

    // relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function files()
    {
        return $this->belongstoMany(File::class);
    }
}
