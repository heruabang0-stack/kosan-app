<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'user_id',
        'kos_id',
        'tanggal_mulai',
        'durasi_bulan',
        'total_harga',
        'status',
        'catatan',
        'telepon',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }
}
