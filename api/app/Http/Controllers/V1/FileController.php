<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function show(File $file)
    {
        $storage = Storage::build([
            'driver' => $file->driver,
            'root' => $file->root,
            'serve' => $file->serve,
            'throw' => $file->throw,
        ]);

        return $storage->get($file->path);
    }
}
