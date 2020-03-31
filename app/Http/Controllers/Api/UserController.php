<?php

namespace App\Http\Controllers\Api;

use App\Domain\Services\UserService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class UserController extends CrudController
{
    /**
     * @var UserService
     */
    public $service;

    public $resource = UserResource::class;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
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
            "first_name" => "required|max:255",
            "last_name" => "required|max:255",
            "gender" => "required|max:5",
            "birthday" => "required|date|before:today|date_format:Y-m-d",
            "email" => [
                "required",
                Rule::unique('users')
                    ->ignore($id, 'guid'),
            ],
            "password" => "nullable|confirmed|min:8|max:255",
            "status" => "",
        ];

        if (!$id) {
            $rules['password'] = "required|confirmed|min:8|max:255";
            $rules['type'] = "required";
        }

        $validated = $this->validate($request, $rules);
        $validated['type'] = 1;
        return $validated;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function postProfileByGuid(Request $request)
    {
        $id = $request->user()->guid;

        $this->validate($request, [
            "first_name" => "required|max:255",
            "last_name" => "required|max:255",
            "gender" => "required|max:5",
            "birthday" => "required|date|before:today",
            "email" => [
                "required",
                Rule::unique('users')
                    ->ignore($id, 'guid'),
            ],
            "password" => "nullable|confirmed",
        ]);

        $this->service->updateByGuid($id, Arr::only($request->all(), ['first_name', 'last_name', 'email', 'birthday', 'gender', 'password']));
        $entity = $this->service->getDetailByGuid($id);
        return new AuthResource($entity);
    }

    public function postDeleteByGuid(Request $request)
    {
        $inputs = $request->all();
        $inputs['creator_id'] = auth()->guard('api')->id();
        return response()->json(['success' => $this->service->deleteByGuid($request->input('id'), $inputs)]);
    }
}
