<?php

namespace App\Http\Resources;

class UserResource extends CrudResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->guid,
            'gender' => $this->gender,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'birthday' => $this->birthday,
            'last_login_at' => $this->last_login_at,
            'type' => $this->type,
            'is_active' => (!!($this->email_verified_at)),
        ];
    }
}
