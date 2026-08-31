<?php

namespace App\Http\Resources;

class JobApplicationResource extends CrudResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $displayStatus = $this->status;

        if ($this->interview) {
            $displayStatus = $this->interview->status;
        }

        return [
            'id' => $this->guid,
            'job' => new JobResource($this->whenLoaded('job')),
            'candidate' => new UserResource($this->whenLoaded('candidate')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'job_application_number' => $this->job_application_number,
            'status' => $this->status,
            '_status_' => config('sattviki.job_application_status')[$this->status],
            'display_status' => $displayStatus,
            '_display_status_' => config('sattviki.job_application_status')[$displayStatus],
            'job_id' => $this->job->guid,
            'candidate_id' => $this->candidate->guid,
            'interview_date' => optional($this->interview)->interview_date,
            'joining_date' => optional($this->interview)->joining_date,
            'user_document' => new DocumentResource($this->whenLoaded('user_document')),
        ];
    }
}
