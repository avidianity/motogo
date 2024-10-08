<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = [
        'name',
        'type',
        'size',
        'path',
        'driver',
        'root',
        'serve',
        'throw',
    ];

    protected static function booted()
    {
        static::deleted(static function (File $file) {
            $storage = Storage::build([
                'driver' => $file->driver,
                'root' => $file->root,
                'serve' => $file->serve,
                'throw' => $file->throw,
            ]);

            $storage->delete($file->path);
        });
    }
    protected function casts(): array
    {
        return [
            'serve' => 'boolean',
            'throw' => 'boolean',
        ];
    }
}
