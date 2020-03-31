<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyUser extends Model
{
    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillable = ['user_id', 'token', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
