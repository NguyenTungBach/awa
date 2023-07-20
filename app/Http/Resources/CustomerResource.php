<?php
/**
 * Created by VeHo.
 * Year: 2023-07-20
 */

namespace App\Http\Resources;

class CustomerResource extends BaseResource
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
