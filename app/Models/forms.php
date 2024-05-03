<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class forms extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'description',
        'hobbies',
        'experience',
        'impacto_social',
    ];
}
