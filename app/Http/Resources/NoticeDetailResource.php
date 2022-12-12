<?php

/**
 * Created by VeHo.
 * Year: 2022-04-08
 */

namespace App\Http\Resources;

use Helper\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class NoticeDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "subject" => $this->subject,
            "content" => $this->content,
            "public_date" => $this->public_date,
            "public_date_display" => $this->public_date_display,
            "surveys" => $this->survey_question,
            "is_draft" => $this->is_draft,
            "list_file_display" => $this->list_file_display,
            "viewed" => $this->viewed
        ];
    }
}