<?php

namespace App\Http\Controllers\Api;

use App\Domain\Models\Job;
use App\Domain\Services\CompanyService;
use App\Domain\Services\QualificationService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CompanyController extends CrudController
{
    /**
     * @var CompanyService
     */
    public $service;

    public $resource = CompanyResource::class;

    public function __construct(CompanyService $companyService)
    {
        $this->service = $companyService;
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
                Rule::unique('companies')
                    ->ignore($id, 'guid'),
            ],
            "email" => [
                "required",
                Rule::unique('companies')
                    ->ignore($id, 'guid'),
            ],
            "phone" => ['required'],
            "additional_contact" => ['nullable', "max:255"],
            "additional_contact_number" => ['nullable', "max:255"],
            "description" => ['required'],
            "address" => ['required'],
            "status" => ['required'],
        ];

        if (!$id) {
            //
        }

        $validated = $this->validate($request, $rules);
        return $validated;
    }
}
