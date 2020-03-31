<?php

namespace App\Http\Controllers\Api;

use App\Domain\Models\Company;
use App\Domain\Models\InterviewStage;
use App\Domain\Models\Job;
use App\Domain\Models\JobApplication;
use App\Domain\Models\User;
use App\Domain\Models\UserDocument;
use App\Domain\Services\JobApplicationService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\JobApplicationResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JobApplicationController extends CrudController
{
    /**
     * @var JobApplicationService
     */
    public $service;

    public $resource = JobApplicationResource::class;

    public function __construct(JobApplicationService $jobApplicationService)
    {
        $this->service = $jobApplicationService;
    }

    /**
     * @param Request $request
     * @param null $id
     * @return array
     * @throws ValidationException
     */
    protected function validateInput(Request $request, $id = null)
    : array
    {
        if ($id) { // Edit
            $rules = [
                "status" => ['required'],
                "interview_date" => ['required_if:status,2', 'date', 'after:today', 'date_format:Y-m-d'],
                "interview_stage_id" => ['required_if:status,2'],
                "company_id" => ['required_if:status,2'],
                "candidate_id" => ['required_if:status,2'],
                "user_document_id" => [],
            ];

            $validated = $this->validate($request, $rules);
            $validated["creator_id"] = auth()->guard('api')->id();
            $validated["company_id"] = (isset($validated['company_id'])) ? optional(Company::where('guid', $validated['company_id'])->first())->id : null;
            $validated["interview_stage_id"] = (isset($validated['interview_stage_id'])) ? optional(InterviewStage::where('guid', $validated['interview_stage_id'])->first())->id : null;
            $validated["candidate_id"] = (isset($validated['candidate_id'])) ? optional(User::where('guid', $validated['candidate_id'])->first())->id : null;

            return $validated;
        }

        $rules = [
            "job_id" => ['required'],
            "candidate_id" => ['required'],
            "status" => ['required'],
            "user_document_id" => ['required'],
        ];

        $validated = $this->validate($request, $rules, [
            'user_document_id.required' => 'The user document field is required. You can upload document from Profile.'
        ]);

        $validated["creator_id"] = auth()->guard('api')->id();
        $validated["job_id"] = optional(Job::where('guid', $validated['job_id'])->first())->id;
        $validated["candidate_id"] = optional(User::where('guid', $validated['candidate_id'])->first())->id;
        $validated["user_document_id"] = optional(UserDocument::where('guid', $validated['user_document_id'])->first())->id;
        return $validated;
    }

    public function postCreate(Request $request)
    {
        $validatedData = $this->validateInput($request);

        if (JobApplication::where('job_id', $validatedData['job_id'])->where('candidate_id', $validatedData['candidate_id'])->count()) {
            return response()->json(['code' => 201], 201);
        }

        $entity = $this->service->create($validatedData);
        return $this->getResource($entity);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getOwnList(Request $request)
    {
        $inputs = $request->all();
        $inputs['candidate_id'] = auth()->guard('api')->user()->id;

        return JobApplicationResource::collection($this->service->getOwnList($inputs));
    }
}
