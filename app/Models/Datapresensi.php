<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datapresensi extends Model
{
    protected $table = 'data_presensi';
    protected $primaryKey = 'id';

    protected $guarded = [];
}
