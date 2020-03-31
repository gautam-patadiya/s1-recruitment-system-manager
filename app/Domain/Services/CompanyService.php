<?php

namespace App\Domain\Services;

use App\Domain\Models\Company;
use App\Domain\Models\Job;
use Illuminate\Validation\ValidationException;

class CompanyService extends BaseService
{
    /**
     * @var Company
     */
    public $model = Company::class;

    public $searchColumns = ['name', 'email', 'phone', 'additional_contact', 'additional_contact_number', 'description', 'address'];

    public $filterColumns = ['name', 'email', 'phone'];

    public $with = [];

    protected $primaryKey = 'id';

    protected $guidKey = 'guid';

    public function deleteByGuid($id, array $input = [])
    {
        $entity = $this->model::query()->where('guid', $id)->first();
        $jobs = Job::where('company_id', optional($entity)->id)->count();

        if ($jobs > 0) {
            throw ValidationException::withMessages([]);
        }

        if ($entity)
            return $entity->delete();

        return false;
    }
}
