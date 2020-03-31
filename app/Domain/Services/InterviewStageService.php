<?php

namespace App\Domain\Services;

use App\Domain\Models\Interview;
use App\Domain\Models\InterviewStage;
use Illuminate\Validation\ValidationException;

class InterviewStageService extends BaseService
{
    /**
     * @var InterviewStage
     */
    public $model = InterviewStage::class;

    public $searchColumns = ['title'];

    public $filterColumns = ['title'];

    public $with = [];

    protected $primaryKey = 'id';

    protected $guidKey = 'guid';

    public function deleteByGuid($id, array $input = [])
    {
        $entity = $this->model::query()->where('guid', $id)->first();
        $interviews = Interview::join('interview_stage_histories', function ($join) use ($id) {
            $join->on('interview_stage_histories.interview_id', '=', 'interviews.id');
            $join->on('interview_stage_histories.interview_stage_id', '=', \DB::raw("'" . $id . "'"));
        })->count();

        if ($interviews > 0) {
            throw ValidationException::withMessages([]);
        }

        if ($entity)
            return $entity->delete();

        return false;
    }

}
