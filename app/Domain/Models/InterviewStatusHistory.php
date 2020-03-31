<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewStatusHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'creator_id', 'interview_id', 'status'
    ];
}
