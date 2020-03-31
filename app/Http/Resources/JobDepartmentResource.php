<?php

namespace App\Http\Resources;

class JobDepartmentResource extends CrudResource
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
            'name' => $this->name,
            'created_at' => $this->created_at,
        ];
    }
}
