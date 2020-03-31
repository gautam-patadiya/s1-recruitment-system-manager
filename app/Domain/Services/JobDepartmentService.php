<?php

namespace App\Domain\Services;

use App\Domain\Models\Job;
use App\Domain\Models\JobDepartment;
use Illuminate\Validation\ValidationException;

class JobDepartmentService extends BaseService
{
    /**
     * @var JobDepartment
     */
    public $model = JobDepartment::class;

    public $searchColumns = ['name'];

    public $filterColumns = ['name'];

    public $with = [];

    protected $primaryKey = 'id';

    protected $guidKey = 'guid';

    public function deleteByGuid($id, array $input = [])
    {
        $entity = $this->model::query()->where('guid', $id)->first();

        $jobs = Job::query()
            ->where('job_department_id', optional($entity)->id)
            ->count();

        if ($jobs > 0) {
            throw ValidationException::withMessages([]);
        }

        if ($entity)
            return $entity->delete();

        return false;
    }
}
