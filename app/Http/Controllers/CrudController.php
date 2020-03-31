<?php

namespace App\Http\Controllers;

use App\Domain\Services\BaseService;
use App\Http\Resources\CrudResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CrudController extends Controller
{
    /**
     * @var BaseService
     */
    protected $service;

    public $resource = CrudResource::class;

    public $resourceCollection;

    public function getList(Request $request)
    {
        $entities = $this->service->getList($request->all());
        return $this->getResourceCollection($entities);
    }

    protected function validateInput(Request $request, $id = null)
    : array
    {
        return [];
    }

    public function postCreate(Request $request)
    {
        $validatedData = $this->validateInput($request);
        $entity = $this->service->create($validatedData);
        return $this->getResource($entity);
    }

    public function postUpdate(Request $request)
    {
        $id = $request->input('id');
        $validatedData = $this->validateInput($request, $id);
        $entity = $this->service->update($id, $validatedData);
        return $this->getResource($entity);
    }

    public function getDetail(Request $request, $id)
    {
        $entity = $this->service->getDetail($id, $request->all());

        return $this->getResource($entity);
    }

    public function postDelete(Request $request)
    {
        return response()->json(['success' => $this->service->delete($request->input('id'), $request->all())]);
    }

    protected function getResourceCollection($entities)
    {
        if ($this->resourceCollection) {
            return new $this->resourceCollection($entities);
        }

        return $this->resource::collection($entities);
    }

    protected function getResource($entity)
    {
        return new $this->resource($entity);
    }

    public function postUpdateByGuid(Request $request)
    {
        $id = $request->input('id');
        $validatedData = $this->validateInput($request, $id);
        $entity = $this->service->updateByGuid($id, $validatedData);
        return $this->getResource($entity);
    }

    public function getDetailByGuid(Request $request, $id)
    {
        $entity = $this->service->getDetailByGuid($id, $request->all());

        return $this->getResource($entity);
    }

    public function postDeleteByGuid(Request $request)
    {
        try{
            $this->service->deleteByGuid($request->input('id'), $request->all());
        } catch(ValidationException $e) {
            return response()->json([
                'message' => 'ERROR!'
            ], 419);
        }

        return response()->json([
            'success' => true
        ]);
    }
}
