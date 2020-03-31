<?php

namespace App\Http\Controllers\Api;

use App\Domain\Models\DocumentType;
use App\Domain\Services\DocumentService;
use App\Domain\Services\DocumentTypeService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\DocumentResource;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Collection;

class DocumentController extends CrudController
{
    /**
     * @var DocumentTypeService
     */
    public $service;

    public $resource = DocumentResource::class;

    public function __construct(DocumentService $documentService)
    {
        $this->service = $documentService;
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
        $rules = [];

        if (!$id) {
            $rules = [
                'file' => 'required|mimes:jpeg,jpg,pdf,docs',
                'document_type' => 'required',
            ];
        }

        return $this->validate($request, $rules);
    }

    public function postCreate(Request $request)
    {
        $user = auth()->guard('api')->user();
        abort_if(!$user, 401);

        $validatedData = $this->validateInput($request);
        $validatedData['type'] = 'tmp';
        $validatedData['object_type'] = 'user';
        $validatedData['object_id'] = $user->id;
        $validatedData['creator_id'] = $user->id;
        $validatedData['disk'] = 'public';
        $validatedData['document_type_id'] = optional(DocumentType::where('guid', $validatedData['document_type'])->first())->id;
        $validatedData['path'] = config('sattviki.file_structure.documents');
        $entity = $this->service->create($validatedData);

        return $this->getResource($entity);
    }

    public function postMiscellaneousCreate(Request $request)
    {
        $user = auth()->guard('api')->user();
        abort_if(!$user, 401);

        $validatedData['file'] = $request->file('file');
        $validatedData['object_type'] = $request->input('type', 'miscellaneous');
        $validatedData['object_id'] = $user->id;
        $validatedData['creator_id'] = $user->id;
        $validatedData['disk'] = 'public';
        $validatedData['document_type_id'] = optional(DocumentType::where('guid', $request->input('document_type_id'))->first())->id;
        $validatedData['path'] = config('sattviki.file_structure.documents');
        $entity = $this->service->create($validatedData);

        return $this->getResource($entity);
    }

    public function getOwnList(Request $request)
    {
        $inputs = $request->all();
        $inputs['creator_id'] = auth()->guard('api')->id();
        $entities = collect([]);
        if($inputs['creator_id']) {
            $entities = $this->service->getOwnList($inputs);
        }

        return $this->getResourceCollection($entities);
    }
}
