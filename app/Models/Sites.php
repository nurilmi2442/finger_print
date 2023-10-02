<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sites extends Model
{
    protected $table = 'sites';
    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $fillable = [
        'nama',
    ];

}
