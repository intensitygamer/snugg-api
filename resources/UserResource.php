<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'firstname'         => $this->firstname,
            'middlename'        => $this->middlename,
            'lastname'          => $this->lastname,
            'type_id'           => $this->type_id,
            'email'             => $this->email,
            'contact_number'    => $this->contact_number,
            'address'           => $this->address,
            'lat'               => $this->lat,
            'lon'               => $this->lon,
            'device_id'         => $this->device_id,
        ];

        if(isset($this->brokerDetails)){
            $data['broker_details'] = new BrokerDetailsResource($this->brokerDetails);
        }

        return $data;
    }
}
