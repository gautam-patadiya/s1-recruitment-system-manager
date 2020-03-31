<?php

namespace App\Domain\Services;

use App\Domain\Models\Interview;
use App\Domain\Models\InterviewStageHistory;
use App\Domain\Models\InterviewStatusHistory;
use App\Domain\Models\JobApplication;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class JobApplicationService extends BaseService
{
    /**
     * @var JobApplication
     */
    public $model = JobApplication::class;

    public $searchColumns = [];

    public $filterColumns = ['job_application_number', 'status'];

    public $with = ['job', 'candidate', 'user_document'];

    protected $primaryKey = 'id';

    protected $guidKey = 'guid';

    public function create(array $input)
    {
        $entity = $this->model::create(Arr::except($input, ['creator_id']));

        $entity->numberIncrement();
        return $entity;
    }

    public function updateByGuid($id, array $input)
    {
        $entity = $this->model::query()->where('guid', $id)->firstOrFail();

        if ($entity->status !== $input['status']) {
            $entity->status = $input['status'];

            if ($input['status'] == 2) {
                $interview = Interview::create([
                    'job_application_id' => $entity->id,
                    'candidate_id' => $input['candidate_id'],
                    'company_id' => $input['company_id'],
                    'status' => 2,
                ]);

                $interview->interview_date = $input['interview_date'];
                $interview->interview_stage_id = $input['interview_stage_id'];
                $interview->rejected_at = null;
                $interview->hired_at = null;
                $interview->save();

                InterviewStatusHistory::create([
                    'creator_id' => $input['creator_id'],
                    'interview_id' => $interview->id,
                    'status' => $input['status'],
                ]);

                if ($input['interview_stage_id']) {
                    InterviewStageHistory::create([
                        'interview_id' => $interview->id,
                        'interview_stage_id' => $interview->interview_stage_id,
                    ]);
                }
            }

            $entity->save();
        }

        return $this->getDetailByGuid($id, []);
    }

    public function getOwnList(array $input = [])
    {
        $query = $this->model::query()->with(['job.experienceLevels', 'job.qualifications']);
        $query = $this->listQuery($query, $input);
        $query = $query->where('candidate_id', $input['candidate_id']);
        return $this->resultQuery($query, $input);
    }
}
