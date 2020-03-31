<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;

class DocumentResource extends CrudResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->guid,
            'document_type' => optional($this->document_type)->name,
            'name' => $this->name,
            'filename' => $this->filename,
//            'mimetype' => $this->mimetype,
//            'size' => $this->size,
//            'header' => $this->header,
            'url' => url(Storage::url($this->path)),
            'download_url' => url(Storage::url($this->path)),
        ];
    }
}
