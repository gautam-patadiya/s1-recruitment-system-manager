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
        return [
            'id' => $this->guid,
            'job' => new JobResource($this->whenLoaded('job')),
            'candidate' => new UserResource($this->whenLoaded('candidate')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'job_application_number' => $this->job_application_number,
            'status' => $this->status,
            '_status_' => config('sattviki.job_application_status')[$this->status],
            'job_id' => $this->job->guid,
            'candidate_id' => $this->candidate->guid,
            'interview_date' => optional($this->interview)->interview_date,
            'user_document' => new DocumentResource($this->whenLoaded('user_document')),
        ];
    }
}
