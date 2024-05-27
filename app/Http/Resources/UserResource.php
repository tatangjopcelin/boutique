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
        $response = [       
            'id'  => $this->id,
            'nom' => $this->name,
            'email' => $this->email,
            'abonne il y a' => $this->created_at->diffForHumans(),
         ];

        return $response;
    }
}
