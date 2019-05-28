<?php

namespace App\Http\Resources\Api\v1_0;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Api\v1_0\Traits\AcfFormatter;

class BaseResource extends Resource
{
    use AcfFormatter;

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
