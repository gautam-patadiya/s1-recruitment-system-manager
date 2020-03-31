<?php

namespace App\Domain\Services;

use App\Domain\Models\ExperienceLevel;
use App\Domain\Models\Job;
use Illuminate\Validation\ValidationException;

class ExperienceLevelService extends BaseService
{
    /**
     * @var ExperienceLevel
     */
    public $model = ExperienceLevel::class;

    public $searchColumns = ['title'];

    public $filterColumns = ['title'];

    public $with = [];

    protected $primaryKey = 'id';

    protected $guidKey = 'guid';

    public function deleteByGuid($id, array $input = [])
    {
        $entity = $this->model::query()->where('guid', $id)->first();

        $jobs = Job::select(['jobs.*'])
            ->join('job_experience_levels', function ($join) use ($id) {
                $join->on('job_experience_levels.job_id', '=', 'jobs.id');
                $join->on('job_experience_levels.experience_level_id', '=', \DB::raw("'" . $id . "'"));
            })
            ->count();

        if ($jobs > 0) {
            throw ValidationException::withMessages([]);
        }

        if ($entity)
            return $entity->delete();

        return false;
    }
}
