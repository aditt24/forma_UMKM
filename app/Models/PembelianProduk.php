<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianProduk extends Model
{
    protected $table = 'pembelian_produk';

    protected $primaryKey = 'id_pembelian';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = true;

    protected $guarded = [];
}
