<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Masterunit extends Model
{
    protected $connection = 'mysql_eoffice';
    protected $table = 'master_unit';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $guarded = [];
}
