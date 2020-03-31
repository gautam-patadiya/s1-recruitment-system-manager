<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplicationStatusHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'creator_id', 'job_application_id', 'status', 'created_at', 'updated_at'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
