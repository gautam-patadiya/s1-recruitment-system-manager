<?php

namespace App\Http\Controllers\Api;

use App\Domain\Services\JobDepartmentService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\JobDepartmentResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobDepartmentController extends CrudController
{
    /**
     * @var JobDepartmentService
     */
    public $service;

    public $resource = JobDepartmentResource::class;

    public function __construct(JobDepartmentService $jobDepartmentService)
    {
        $this->service = $jobDepartmentService;
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
            "name" => [
                "required",
                Rule::unique('job_departments')
                    ->ignore($id, 'guid'),
            ],
        ];

        if (!$id) {
            //
        }

        $validated = $this->validate($request, $rules);
        return $validated;
    }
}
