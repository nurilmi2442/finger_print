<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Workingsch extends Model
{
    protected $connection = 'mysql';
    protected $table = 'working_schedule';

    protected $guarded =[];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }


}
