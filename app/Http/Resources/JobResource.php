<?php

namespace App\Http\Resources;

use App\Domain\Models\Qualification;

class JobResource extends CrudResource
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
            'open_vacancies' => $this->open_vacancies,
            'company_id' => optional($this->company)->guid,
            'job_department_id' => optional($this->jobDepartment)->guid,
            'company' => optional($this->company)->name,
            'department' => optional($this->jobDepartment)->name,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'company_description' => $this->company_description,
            'job_description' => $this->job_description,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d'),
            'experience_levels' => ExperienceLevelResource::collection($this->whenLoaded('experienceLevels')),
            'qualifications' => QualificationResource::collection($this->whenLoaded('qualifications')),
            'archived_at' => $this->archived_at,
        ];
    }
}
