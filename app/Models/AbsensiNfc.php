<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiNfc extends Model
{
    protected $table = 'absensi_nfc';

    protected $fillable = [
        'user_id',
        'nfc_card_id',
        'tanggal',
        'waktu_scan',
        'status',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nfcCard()
    {
        return $this->belongsTo(NfcCard::class);
    }
}