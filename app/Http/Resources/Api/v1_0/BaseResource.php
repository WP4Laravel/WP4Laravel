<?php

namespace App\Http\Resources\Api\v1_0;

use Illuminate\Http\Resources\Json\Resource;

class BaseResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
