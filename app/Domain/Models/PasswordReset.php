<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Domain\Model\PasswordReset
 *
 * @property int $id
 * @property string $email
 * @property string|null $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Model\PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Model\PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Model\PasswordReset query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Model\PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Model\PasswordReset whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Model\PasswordReset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Model\PasswordReset whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Domain\Model\PasswordReset whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PasswordReset extends Model
{
    protected $fillable = [
        'email', 'token', 'created_at', 'updated_at'
    ];
}
