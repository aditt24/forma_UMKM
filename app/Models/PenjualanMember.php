<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanMember extends Model
{
    protected $table = 'penjualan_member';

    protected $primaryKey = 'id_penjualan';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $guarded = [];
}
