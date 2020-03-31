<?php

namespace App\Http\Resources;

class InterviewStageHistoryResource extends CrudResource
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
            'id' => $this->id,
            'interview_stage' => optional($this->interview_stage)->title,
            'created_at' => $this->created_at,
        ];
    }
}
