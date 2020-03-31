<?php

namespace App\Http\Controllers\Api;

use App\Domain\Services\DocumentTypeService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\DocumentTypeResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentTypeController extends CrudController
{
    /**
     * @var DocumentTypeService
     */
    public $service;

    public $resource = DocumentTypeResource::class;

    public function __construct(DocumentTypeService $documentTypeService)
    {
        $this->service = $documentTypeService;
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
                Rule::unique('document_types')
                    ->ignore($id, 'guid'),
            ],
        ];

        if (!$id) {
            //
        }

        return $this->validate($request, $rules);
    }
}
