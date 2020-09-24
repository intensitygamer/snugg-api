<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrokerDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'subscription_id'   => $this->subscription_id,
            'id_status'         => $this->id_status,
            'expiration_date'   => $this->expiration_date,
            'prc_id'            => $this->prc_id,
        ];

        if(isset($this->images)){
            $data['images'] = ImageResource::collection($this->images);
        }
        return $data;
    }
}
