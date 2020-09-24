<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->is_deleted){
            return null;
        }

        return [
            'image_url'         => $this->path,
            'deleted'           => $this->is_deleted,
        ];
    }
}
