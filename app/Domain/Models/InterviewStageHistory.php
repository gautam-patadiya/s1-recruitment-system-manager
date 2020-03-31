<?php

namespace App\Domain\Models;

use App\Helpers\HasGuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewStageHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'interview_id', 'interview_stage_id', 'created_at', 'updated_at'
    ];

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }

    public function interview_stage()
    {
        return $this->belongsTo(InterviewStage::class);
    }
}
