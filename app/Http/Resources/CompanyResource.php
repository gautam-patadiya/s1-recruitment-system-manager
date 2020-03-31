<?php

namespace App\Http\Resources;

class CompanyResource extends CrudResource
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
            'status' => $this->status,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'additional_contact' => $this->additional_contact,
            'additional_contact_number' => $this->additional_contact_number,
            'description' => $this->description,
            'address' => $this->address,
            'created_at' => $this->created_at,
        ];
    }
}
