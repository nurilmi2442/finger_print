<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Workingsch;

class Pegawai extends Model
{
    protected $connection = 'mysql_eoffice';
    protected $table = 'pegawai';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $guarded = [];

    public function workingsch()
    {
        return $this->hasMany(Workingsch::class, 'id_pegawai','id');
    }
}
