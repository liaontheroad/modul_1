<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class kategori extends Model
{
    use SoftDeletes;

    protected $table = 'kategori';
    protected $primaryKey = 'idkategori';
    public $timestamps = false; 
    protected $fillable = ['nama_kategori'];
    public function buku()

    {
        return $this->hasMany(Buku::class, 'idkategori', 'idkategori');
    }
}
