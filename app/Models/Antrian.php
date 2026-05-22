<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $table      = 'antrian';
    protected $primaryKey = 'id';       
    public $incrementing  = true;       
    protected $keyType    = 'int';       

    protected $fillable = [
        'nomor_antrian',
        'nama',
        'ruangan',
        'status',
    ];
}