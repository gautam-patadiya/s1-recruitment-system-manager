<?php

namespace App\Http\Resources;

class ExperienceLevelResource extends CrudResource
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
            'title' => $this->title,
            'created_at' => $this->created_at,
        ];
    }
}
