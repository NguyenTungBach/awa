<?php
/**
 * Created by VeHo.
 * Year: 2022-07-28
 */

namespace App\Http\Resources;

class CourseResource extends BaseResource
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
