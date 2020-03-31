<?php

namespace App\Http\Controllers\Api;

use App\Domain\Services\ExperienceLevelService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\ExperienceLevelResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ExperienceLevelController extends CrudController
{
    /**
     * @var ExperienceLevelService
     */
    public $service;

    public $resource = ExperienceLevelResource::class;

    public function __construct(ExperienceLevelService $userService)
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
            "title" => [
                "required",
                Rule::unique('experience_levels')
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
