<?php

namespace App\Http\Resources;

use Carbon\Carbon;

class AuthResource extends CrudResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->guid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'email' => $this->email,
            'type' => $this->type,
            'last_login_at' => $this->last_login_at,
            $this->mergeWhen($this->tokenResult, function () {
                return [
                    'access_token' => $this->tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $this->tokenResult->token->expires_at
                    )->toDateTimeString(),
                ];
            })
        ];
    }
}
