<?php

namespace App\Http\Resources;

use App\Models\Abonnement;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
         $s = Abonnement::where('abonnes_id',$this->id)->get();
        
        $response = [

           'id' => $this->id,
           'nom' => $this->nom,
           'prenom' => $this->prenom,
           'email' =>$this->email,
           'pays' => $this->pays,
           'created_at' =>$this->created_at->format("d/m/Y"),
           'tel' => $this->tel,
           'status' => $s[0]['status'],
            
        ];
        return $response;
    }
}
