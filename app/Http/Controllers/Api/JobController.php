<?php

namespace App\Http\Controllers\Api;

use App\Domain\Models\Company;
use App\Domain\Models\ExperienceLevel;
use App\Domain\Models\JobDepartment;
use App\Domain\Models\Qualification;
use App\Domain\Services\JobService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\JobResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobController extends CrudController
{
    /**
     * @var JobService
     */
    public $service;

    public $resource = JobResource::class;

    public function __construct(JobService $jobService)
    {
        $this->service = $jobService;
    }

    /**
     * @param Request $request
     * @param null $id
     * @param boolean $id_required
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateInput(Request $request, $id = null)
    : array
    {
        $rules = [
            "company_id" => ['required'],
            "job_department_id" => ['required'],
            "status" => [],
            "open_vacancies" => ['required'],
            "from_date" => ['required','date'],
            "to_date" => ['required','after:from_date'],
            "job_description" => ['required'],
            "company_description" => ['required'],
            "job_qualifications" => [],
            "job_experience_levels" => [],
            "title" => [
                "required",
            ],
        ];

        if (!$id) {
            //
        }

        $validated = $this->validate($request, $rules);
        $validated["company_id"] = optional(Company::where('guid', $validated['company_id'])->first())->id;
        $validated["job_department_id"] = optional(JobDepartment::where('guid',$validated['job_department_id'])->first())->id;
        $validated["job_qualifications"] = Qualification::whereIn('guid', $validated["job_qualifications"])->get()->pluck('id')->toArray();
        $validated["job_experience_levels"] = ExperienceLevel::whereIn('guid', $validated["job_experience_levels"])->get()->pluck('id')->toArray();
        $validated["status"] = ((isset($validated["status"]) && $validated["status"]) ? 1 : 0);
        return $validated;
    }

    public function postArchiveByGuid(Request $request)
    {
        return response()->json([
            'success' => $this->service->archiveByGuid($request->all())
        ]);
    }
}
