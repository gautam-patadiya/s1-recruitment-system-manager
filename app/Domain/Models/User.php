<?php

namespace App\Domain\Models;

use App\Helpers\HasGuidTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasGuidTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'guid', 'type', 'gender', 'first_name', 'last_name', 'email', 'email_verified_at', 'password', 'remember_token', 'birthday', 'last_login_at', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'candidate_id', 'id');
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class, 'candidate_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(UserDocument::class, 'creator_id', 'id')->where('object_type', 'user');
    }
}
