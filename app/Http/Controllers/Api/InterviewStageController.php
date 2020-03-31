<?php

namespace App\Http\Controllers\Api;

use App\Domain\Services\InterviewStageService;
use App\Domain\Services\QualificationService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\InterviewStageResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InterviewStageController extends CrudController
{
    /**
     * @var InterviewStageService
     */
    public $service;

    public $resource = InterviewStageResource::class;

    public function __construct(InterviewStageService $interviewStageService)
    {
        $this->service = $interviewStageService;
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
            "title" => [
                "required",
                Rule::unique('interview_stages')
                    ->ignore($id, 'guid'),
            ],
            "i_order" => 'required|numeric'
        ];

        if (!$id) {
            //
        }

        $validated = $this->validate($request, $rules);
        return $validated;
    }
}
