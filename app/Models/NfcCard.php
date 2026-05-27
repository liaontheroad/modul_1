<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NfcCard extends Model
{
    protected $fillable = [
        'user_id',
        'serial_number',
        'card_uid',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function absensi()
    {
        return $this->hasMany(AbsensiNfc::class);
    }
}