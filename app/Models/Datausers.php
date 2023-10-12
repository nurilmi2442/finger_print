<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datausers extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $cats = [
        'password' => 'hashed',
    ];
    protected $guarded = [];
}
