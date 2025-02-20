<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;
    protected $table='systems';
    protected $fillable = [
        'keyword',
        'content',
        'language_id',
        'user_id',
    ];

}