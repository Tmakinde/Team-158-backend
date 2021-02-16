<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $dataFarmer = [];
        for($i =0;$i < count($this->farmers);$i++){
            $dataFarmer[] = ["id" => $this->farmers[$i]["id"], "type" => "Farmer"];
        };
        return [
                "id" => (string)$this->id,
                "type" => "Investor",
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
                            "self" => "http://localhost:8001/api/v2/investors/".(string)$this->id."/relationship/myfarmers",
                            "related" => "http://localhost:8001/api/v2/investors/".(string)$this->id."/myfarmers",
                        ],
                        "data" => $dataFarmer 
                    ]
                ],
                "includes" => $dataFarmer,
        ];
    }
}
