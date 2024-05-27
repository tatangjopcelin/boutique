<?php

namespace App\Http\Resources;

use App\Models\Met;
use App\Models\User;
use App\Models\Photo;
use App\Models\Video;
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
        $lien_image = Photo::where('recettes_id',$this->id)->get();
        $lien_video = Video::where('recettes_id',$this->id)->get();
        $response = [
            
            'id'  => $this->id,
            'nom' => $this->nom,
            'description' => $this->description,
            'vote' => $this->vote,
            'proprietaire' => User::find($this->users_id)->name,
            'met_de' => Met::find($this->mets_id)->nom,
            'cree_le' => $this->created_at->format('d/m/Y'),
            'mise_ajour_le' => $this->updated_at->format('d/m/Y'),
            'lien_image' => $lien_image[0]['photo_path'],
            'lien_video' => $lien_video[0]['path'],
        ];

        return $response;
    }
}
