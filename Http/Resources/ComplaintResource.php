<?php
declare(strict_types=1);

namespace UserFeed\Http\Resources;


use App\Http\Resources\JsonResource;
use App\Http\Resources\ShortUserResource;
use UserFeed\Models\Complaint;

/**
 * Class ComplaintResource
 * @package UserFeed\Http\Resources
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintResource extends JsonResource
{
    /** @var Complaint */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->getKey(),
            'type' => new ComplaintTypeResource($this->resource->type),
            'description' => $this->resource->description,
            'user' => new ShortUserResource($this->resource->user),
            'created_at' => $this->resource->created_at->toDateTimeString()
        ];
    }

}
