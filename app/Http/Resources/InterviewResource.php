<?php

namespace App\Http\Resources;

class InterviewResource extends CrudResource
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
            'interviewer_name' => $this->interviewer_name,
            'interview_date' => $this->interview_date,
            'rejected_at' => $this->rejected_at,
            'hired_at' => $this->hired_at,
            'joining_date' => $this->joining_date,
            'status' => $this->status,
            '_status_' => config('sattviki.job_application_status')[$this->status],
            'salary' => $this->salary,
            'notes' => $this->notes,
            'job_application_number' => optional($this->job_application)->job_application_number,
            'candidate' => optional($this->candidate)->first_name.' '.optional($this->candidate)->last_name,
            'company' => optional($this->company)->name,
            'interview_stage' => optional($this->interview_stage)->title,
            'created_at' => $this->created_at,
            'candidate_id' => optional($this->candidate)->guid,
            'company_id' => optional($this->company)->guid,
            'interview_stage_id' => optional($this->interview_stage)->guid,
            'interview_stage_history' => InterviewStageHistoryResource::collection($this->whenLoaded('interview_stage_histories')),
        ];
    }
}
