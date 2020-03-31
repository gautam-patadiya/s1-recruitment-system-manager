<?php

namespace App\Domain\Models;

use App\Helpers\HasGuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interview extends Model
{
    use HasGuidTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'guid', 'job_application_id', 'candidate_id', 'company_id', 'interview_stage_id', 'status', 'interviewer_name', 'interview_date', 'rejected_at', 'hired_at', 'joining_date', 'salary', 'notes', 'created_at', 'updated_at'
    ];

    public function job_application()
    {
        return $this->hasOne(JobApplication::class, 'id', 'job_application_id');
    }

    public function interview_stage()
    {
        return $this->belongsTo(InterviewStage::class);
    }

    public function interview_stage_histories()
    {
        return $this->hasMany(InterviewStageHistory::class);
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
