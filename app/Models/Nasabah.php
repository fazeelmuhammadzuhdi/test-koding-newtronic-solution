<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'telepon',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function antrians()
    {
        return $this->hasMany(Antrian::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($nasabah) {
            $nasabah->user_id = auth()->user()->id;
        });
    }

    public function scopeUserId($q)
    {
        return $q->where('user_id', auth()->user()->id);
    }
}
