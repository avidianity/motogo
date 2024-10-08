<?php

namespace App\Models;

class File extends Model
{
    protected $fillable = [
        'name',
        'type',
        'size',
        'path',
        'driver',
    ];
}
