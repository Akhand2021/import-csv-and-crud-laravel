<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutorials extends Model
{
    use HasFactory;
    public $table="tutorials_tbl";
    public $timestamps = false;
}
