<?php

namespace App\Models;

class VehicleRegistration extends Model
{
    protected $fillable = [
        'file_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
