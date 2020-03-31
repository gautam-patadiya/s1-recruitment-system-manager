<?php

namespace App\Domain\Models;

use App\Helpers\HasGuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasGuidTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'guid', 'status', 'name', 'email', 'phone', 'additional_contact', 'additional_contact_number', 'description', 'address', 'created_at',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_id', 'id');
    }
}
