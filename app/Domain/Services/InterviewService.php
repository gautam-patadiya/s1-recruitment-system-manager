<?php

namespace App\Domain\Services;

use App\Domain\Models\Company;
use App\Domain\Models\Interview;
use App\Domain\Models\InterviewStage;
use App\Domain\Models\InterviewStageHistory;
use App\Domain\Models\InterviewStatusHistory;
use App\Domain\Models\JobApplication;
use App\Domain\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Arr as ArrayHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class InterviewService extends BaseService
{
    /**
     * @var Interview
     */
    public $model = Interview::class;

    public $searchColumns = [];

    public $filterColumns = ['job_application_id', 'candidate_id', 'company_id', 'interview_stage_id', 'status'];

    public $with = ['interview_stage_histories'];

    protected $primaryKey = 'id';

    protected $guidKey = 'guid';

    public function create(array $input)
    {
        $entity = $this->model::create(Arr::except($input, ['creator_id']));

        if ($input['interview_stage_id']) {
            InterviewStageHistory::create([
                'interview_id' => $entity->id,
                'interview_stage_id' => $entity->interview_stage_id,
            ]);
        }

        InterviewStatusHistory::create([
            'creator_id' => $input['creator_id'],
            'interview_id' => $entity->id,
            'status' => $input['status'],
        ]);

        $this->updateStatusConnection($entity, $input);
        $entity->save();
        return $entity;
    }

    public function updateByGuid($id, array $input)
    {
        $entity = $this->model::query()->where('guid', $id)->firstOrFail();

        if($entity->status >= 2 && $input['status'] < 2) {
            throw ValidationException::withMessages(['status' => 'Status Can not be pending again.']);
        }

        if ($entity->status != $input['status']) {
            InterviewStatusHistory::create([
                'creator_id' => $input['creator_id'],
                'interview_id' => $entity->id,
                'status' => $input['status'],
            ]);
        }

        if ($input['interview_stage_id'] != $entity->interview_stage_id) {
            InterviewStageHistory::create([
                'interview_id' => $entity->id,
                'interview_stage_id' => $input['interview_stage_id'],
                'created_at' => $input['interview_date'].' 00:00:00',
            ]);
        }

        $this->updateStatusConnection($entity, $input);
        $entity->update(Arr::except($input, ['creator_id']));
        return $this->getDetailByGuid($id, []);
    }

    public function updateStatusConnection($entity, array $input)
    {
        $entity->rejected_at = (!$entity->rejected_at && $input['status'] === '4') ? now() : null;
        $entity->hired_at = (!$entity->hired_at && $input['status'] === '5') ? now() : null;
        $entity->save();
    }

    protected function filterQuery(Builder $query, $input = [])
    {
        foreach (ArrayHelper::get($input, 'filters', []) as $column => $value) {
            if ($value && in_array($column, $this->filterColumns)) {
                $value = (($column == 'candidate_id') ? optional(User::where('guid', $value)->first())->id : $value);
                $value = (($column == 'company_id') ? optional(Company::where('guid', $value)->first())->id : $value);
                $value = (($column == 'interview_stage_id') ? optional(InterviewStage::where('guid', $value)->first())->id : $value);
                $value = (($column == 'job_application_id') ? optional(JobApplication::where('guid', $value)->first())->id : $value);
                $query->where($column, $value);
            }
        }

        return $query;
    }

}
