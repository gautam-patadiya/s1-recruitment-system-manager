<?php

namespace App\Domain\Models;

use App\Helpers\HasGuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class JobApplication extends Model
{
    use HasGuidTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'guid', 'job_application_number', 'increment_number', 'job_id', 'candidate_id', 'user_document_id', 'status', 'created_at', 'updated_at'
    ];

    public function scopeNumberIncrement()
    {
        $settings = json_decode(file_get_contents(app_path('../front/public/settings.json')), true);
        if (env('APP_ENV') === 'production' && File::exists(public_path('settings.json'))) {
            $settings = json_decode(File::get(public_path('settings.json')), true);
        }

        $prefix = (($settings && isset($settings['job_application_number_prefix'])) ? $settings['job_application_number_prefix'] : '');
        $number = self::max('increment_number');
        if ($number <= 0 || !$number) {
            $number = 1;
        } else {
            $number = $number + 1;
        }

        $this->increment_number = $number;
        $this->job_application_number = $prefix.$number;
        $this->save();
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function interview()
    {
        return $this->belongsTo(Interview::class, 'id', 'job_application_id');
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id', 'id');
    }

    public function user_document()
    {
        return $this->belongsTo(UserDocument::class, 'user_document_id', 'id');
    }
}
