<?php

/**
 * Created by VeHo.
 * Year: 2022-04-09
 */

namespace App\Http\Resources;

class MessageResource extends BaseResource
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
            "message_id" => $this->id,
            "sender_id" => $this->sender_id,
            "sender_name" => $this->user->user_name,
            "sender_code" => $this->user->user_code,
            "content" => $this->content,
            "file" => $this->file,
            "created_at" => $this->created_at,
            "created_at_in_date" => date('Y-m-d', strtotime($this->created_at)),
            "created_at_hours_and_min" => date('H:i', strtotime($this->created_at))
        ];
    }
}
