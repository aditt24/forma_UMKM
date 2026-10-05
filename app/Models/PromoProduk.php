<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoProduk extends Model
{
    protected $table = 'promo_produk';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];
}
