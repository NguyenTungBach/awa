<?php
/**
 * Created by VeHo.
 * Year: 2022-04-08
 */

namespace App\Http\Resources;

use Helper\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class NoticesMobileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "subject" => $this->subject,
            "public_date" => $this->public_date,
            "viewed" => $this->viewed,
        ];
    }

    /**
     * @param mixed $resource
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        $result = parent::collection($resource);
        if ($resource instanceof LengthAwarePaginator) {
            return ResponseService::responsePaginate($result, $resource);
        }
        return $result;
    }
}
