<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tiket extends Model
{
    protected $fillable = [
        'user_id',
        'kode_tiket',
        'judul_kendala',
        'kategori',
        'prioritas',
        'status',
        'deskripsi',
        'foto_kendala',
        'solusi_teknis',
        'rating',
        'komentar_balasan',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tiket) {
            if (empty($tiket->kode_tiket)) {
                $year = date('Y');
                $lastTiket = self::whereYear('created_at', $year)
                    ->orderBy('id', 'desc')
                    ->first();
                
                $sequence = $lastTiket 
                    ? (int) substr($lastTiket->kode_tiket, -3) + 1 
                    : 1;
                
                $tiket->kode_tiket = 'TKT-' . $year . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
