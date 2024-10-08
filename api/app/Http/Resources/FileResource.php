<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\File
 */
class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->name,
            'type' => $this->type,
            'size' => $this->size,
            'url' => route('v1.files.show', ['file' => $this->getKey()]),
            'updated_at' => $this->updated_at,
        ];
    }
}
