<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Generate extends Model
{
    use HasFactory;
    protected $table='generates';

    protected $fillable = [
        'name',
        'user_id',      
    ];
}
