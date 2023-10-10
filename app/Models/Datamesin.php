<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datamesin extends Model
{
    protected $table = 'datamesin';
    protected $primaryKey = 'id';

    protected $guarded = [];
    // protected $fillable = ['id'];

    // public function userfinger()
    // {
    //     return $this->belongsTo(Userfinger::class, 'id_mesin', 'id');
    // }
}
