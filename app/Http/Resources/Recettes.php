<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Recettes extends JsonResource
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
            'nom' => $this->nom,
            'description' => $request->description,
            'lien' => $this->lien,
            'vote' => $this->vote,
            'cree_le' => $this->created_at->format('d/m/Y'),
            'mise_ajour_le' => $this->updated_at->format('d/m/Y'),
        ];

        return $response;
    }
}
