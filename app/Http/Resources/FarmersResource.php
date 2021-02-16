<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $dataInvestor = [];
        for($i =0;$i < count($this->investors);$i++){
            $dataInvestor[] = ['id' => $this->investors[$i]['id'], "type" => "investor"];
        };

        return [
                "id" => (string)$this->id,
                "type" => "Farmer",
                "attributes" => [
                    "first_name" => $this->first_name,
                    "email" => $this->email,
                    "state_id" => (string)$this->state_id,
                    "status" => $this->status,
                    'api_token' => $this->api_token,
                     "time_stamp" =>[
                        "created_at" => (string)$this->created_at,
                        "updated_at" => (string)$this->updated_at,
                    ],
                ],
                "relationships" => [
                    "investors" => [
                        "links" => [
                            "self" => "http://localhost:8001/api/farmers/".(string)$this->id."relationship/investors",
                            "related" => "http://localhost:8001/api/farmers/".(string)$this->id."/myinvestors",
                        ],
                        "data" => $dataInvestor 
                    ]
                ],
                "includes" => $dataInvestor,
                
        ];
        
    }
}
