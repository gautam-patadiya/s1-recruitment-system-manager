<?php

namespace App\Domain\Models;

use App\Helpers\HasGuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasGuidTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'guid', 'company_id', 'job_department_id', 'status', 'open_vacancies', 'title', 'from_date', 'to_date', 'job_description', 'company_description', 'created_at', 'updated_at', 'deleted_at', 'archived_at'
    ];

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function scopeUnArchived($query)
    {
        return $query->whereNull('archived_at');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobDepartment()
    {
        return $this->belongsTo(JobDepartment::class);
    }

    public function experienceLevels()
    {
        return $this->belongsToMany(ExperienceLevel::class, 'job_experience_levels');
    }

    public function qualifications()
    {
        return $this->belongsToMany(Qualification::class, 'job_qualifications');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
