<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                =>      $this->id,
            'company'           =>      $this->company,
            'name'              =>      $this->name,
            'email'             =>      $this->email,
            'photo_profile'     =>      $this->profile_image,
            'created_at'        =>      $this->created_at->format('D F Y'),
            'updated_at'        =>      $this->updated_at->diffForhumans(),
        ];
    }
}
