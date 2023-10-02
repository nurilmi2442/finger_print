<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datamesin extends Model
{
    protected $table = 'datamesin';
    protected $primaryKey = 'id';

    protected $guarded = [];

    // protected $fillable = [
    //     'ip',
    //     'mac_address',
    //     'comkey',
    //     'status',
    //     'id_sites',
    //     'lokasi',
    // ];

}
