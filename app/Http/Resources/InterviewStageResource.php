<?php

namespace App\Http\Resources;

class InterviewStageResource extends CrudResource
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
            'i_order' => $this->i_order,
            'created_at' => $this->created_at,
        ];
    }
}
