<?php

namespace App\Domain\Services;

use App\Domain\Models\Job;
use App\Domain\Models\Qualification;
use Illuminate\Validation\ValidationException;

class QualificationService extends BaseService
{
    /**
     * @var Qualification
     */
    public $model = Qualification::class;

    public $searchColumns = ['title'];

    public $filterColumns = ['title'];

    public $with = [];

    protected $primaryKey = 'id';

    protected $guidKey = 'guid';

    public function deleteByGuid($id, array $input = [])
    {
        $entity = $this->model::query()->where('guid', $id)->first();

        $jobs = Job::select(['jobs.*'])
            ->join('job_qualifications', function ($join) use ($id) {
                $join->on('job_qualifications.job_id', '=', 'jobs.id');
                $join->on('job_qualifications.qualification_id', '=', \DB::raw("'" . $id . "'"));
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
