<?php

namespace App\Domain\Services;

use App\Domain\Models\Company;
use App\Domain\Models\Job;
use App\Domain\Models\JobDepartment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Arr as ArrayHelper;

class JobService extends BaseService
{
    /**
     * @var Job
     */
    public $model = Job::class;

    public $searchColumns = ['title'];

    public $filterColumns = ['title', 'company_id', 'job_department_id', 'status'];

    public $with = ['experienceLevels', 'qualifications'];

    protected $primaryKey = 'id';

    protected $guidKey = 'guid';

    protected function filterQuery(Builder $query, $input = [])
    {
        foreach (ArrayHelper::get($input, 'filters', []) as $column => $value) {
            if ($value && in_array($column, $this->filterColumns)) {
                $value = (($column == 'job_department_id') ? optional(JobDepartment::where('guid', $value)->first())->id : $value);
                $value = (($column == 'company_id') ? optional(Company::where('guid', $value)->first())->id : $value);
                $query->where($column, $value);
            }
        }

        return $query;
    }

    public function create(array $input)
    {
        $entity = $this->model::create(Arr::except($input, ['job_qualifications', 'job_experience_levels']));
        $entity->qualifications()->attach($input['job_qualifications']);
        $entity->experienceLevels()->attach($input['job_experience_levels']);
        return $entity;
    }

    public function getDetailByGuid($id, array $input = [])
    {
        $query = $this->model::query()->with($this->with);

        foreach ($input as $column => $value) {
            $query->where($column, $value);
        }

        return $query->findOrFailByGuid($id);
    }

    public function updateByGuid($id, array $input)
    {
        $this->model::query()->where('guid', $id)->update(Arr::except($input, [
            'job_qualifications',
            'job_experience_levels',
        ]));

        $entity = $this->model::query()->where('guid', $id)->first();
        $entity->qualifications()->sync($input['job_qualifications']);
        $entity->experienceLevels()->sync($input['job_experience_levels']);
        return $this->getDetailByGuid($id, []);
    }

    public function archiveByGuid(array $input)
    {
        return $this->model::where('guid', $input['id'])->update([
            'archived_at' => now()
        ]);
    }

    protected function appendQuery(Builder $query, $input = [])
    {
        if(isset($input['filters']) && isset($input['filters']['unarchived_at']) && $input['filters']['unarchived_at'] === 'true') {
            $query = $query->unArchived();
        }

        if(isset($input['filters']) && isset($input['filters']['expired']) && $input['filters']['expired'] === 'true') {
            $query = $query->whereDate('to_date', '<', $input['filters']['now']);
        }

        return $query;
    }
}
