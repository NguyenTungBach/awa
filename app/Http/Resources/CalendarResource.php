<?php
/**
 * Created by VeHo.
 * Year: 2022-07-29
 */

namespace App\Http\Resources;

class CalendarResource extends BaseResource
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
