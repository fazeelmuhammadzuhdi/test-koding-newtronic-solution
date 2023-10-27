<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nasabah_id',
        'no_antrian',
        'status',
        'layanan',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public static function getNoAntrianTerakhirBerdasarkanLayanan($layanan)
    {
        return static::where('layanan', $layanan)->max('no_antrian');
    }

    public static function getNoAntrianTerakhirBerdasarkanLayananDanStatus($layanan, $status)
    {
        return static::where('layanan', $layanan)
            ->where('status', $status)
            ->orderBy('updated_at', 'desc')
            ->value('no_antrian');
    }

    public static function countJumlahNasabahYangSudahDiPanggil($layanan, $status)
    {
        return static::where('layanan', $layanan)
            ->where('status', $status)
            ->count();
    }

    public function getStatusTeksAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge bg-danger">BELUM DI PANGGIL</span>';
        } else {
            return '<span class="badge bg-success">DI PANGGIL</span>';
        }
    }
}
