<?php
/**
 * Created by VeHo.
 * Year: 2022-04-08
 */

namespace App\Http\Resources;
use Helper\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class NoticesResource extends JsonResource
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
            "public_date_display" => $this->public_date_display,
            "created_by" => $this->created_by,
            "viewed_count" => $this->viewed_count,
            "viewer_left" => $this->view_left,
            "has_attatch_file" => $this->has_attatch_file,
            "survey_result" => $this->survey_result,
            "is_draft" => $this->is_draft,
            "list_file_display" => $this->list_file_display,
        ];
    }

//    /**
//     * @param mixed $resource
//     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
//     */
//    public static function collection($resource)
//    {
//        $result = parent::collection($resource);
//        if ($resource instanceof LengthAwarePaginator) {
//            return ResponseService::responsePaginate($result, $resource);
//        }
//        return $result;
//    }
}
