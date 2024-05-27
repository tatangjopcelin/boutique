<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\Recette;
use Illuminate\Http\Resources\Json\JsonResource;

class Met extends JsonResource
{
 
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $response = [
        //     'nom_du_met' =>$request->nom,
        //     'petite_description' => $request->description,
        //     'lien_image' => $request->image,
        // ];
        $response = [       
            'id'  => $this->id,
            'nom' => $this->nom,
            'description' => $this->description,
            'lien_image' => 'http://'.$this->image,
            'modifie_le' => $this->updated_at->format('d/m/Y'),
            'total_recette' => Recette::where('mets_id',$this->id)->count(),
         ];

        return $response;
        
    }
}
