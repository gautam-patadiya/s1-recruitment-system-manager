<?php

namespace App\Http\Controllers\Api;

use App\Domain\Models\Company;
use App\Domain\Models\InterviewStage;
use App\Domain\Models\User;
use App\Domain\Services\InterviewService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\InterviewResource;
use Illuminate\Http\Request;

class InterviewController extends CrudController
{
    /**
     * @var InterviewService
     */
    public $service;

    public $resource = InterviewResource::class;

    public function __construct(InterviewService $interviewService)
    {
        $this->service = $interviewService;
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
            'candidate_id' => ['required'],
            'company_id' => ['required'],
            'interviewer_name' => ['nullable','max:255'],
            "interview_stage_id" => ['required_if:status,2'],
            'interview_date' => ['required_if:status,2', 'date', 'date_format:Y-m-d'],
            'joining_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'status' => ['required'],
            'salary' => [],
            'notes' => [],
        ];

        if (!$id) {
            //
        }

        $validated = $this->validate($request, $rules);
        $validated["interview_stage_id"] = ($validated['status'] == 2) ? optional(InterviewStage::where('guid', $validated['interview_stage_id'])->first())->id : null;
        $validated["joining_date"] = ($validated['status'] == 5) ?$validated['joining_date'] : null;
        $validated["company_id"] = optional(Company::where('guid', $validated['company_id'])->first())->id;
        $validated["candidate_id"] = optional(User::where('guid', $validated['candidate_id'])->first())->id;
        $validated['creator_id'] = auth()->guard('api')->user()->id;
        return $validated;
    }
}
