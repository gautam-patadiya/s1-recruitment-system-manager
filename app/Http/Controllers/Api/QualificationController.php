<?php

namespace App\Http\Controllers\Api;

use App\Domain\Services\QualificationService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\QualificationResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class QualificationController extends CrudController
{
    /**
     * @var QualificationService
     */
    public $service;

    public $resource = QualificationResource::class;

    public function __construct(QualificationService $qualificationService)
    {
        $this->service = $qualificationService;
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
                Rule::unique('qualifications')
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
