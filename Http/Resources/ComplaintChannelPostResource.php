<?php
declare(strict_types=1);

namespace UserFeed\Http\Resources;

use App\Http\Resources\ShortUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ComplaintChannelPostResource
 * @package UserFeed\Http\Resources
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintChannelPostResource extends JsonResource
{
    private static $short = false;

    public static function short()
    {
        static::$short = true;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->resource->getKey(),
            'status' => $this->resource->status,
            /** @deprecated */
            'complaint_request_status' => $this->resource->status,
            'delete_reason' => $this->resource->delete_reason,
            'created_at' => $this->resource->created_at,
            'complaints_count' => $this->resource->complaints->count(),
            'user' => $this->resource->user ? new ShortUserResource($this->resource->user) : null,
            'complaints_types' => ComplaintTypeResource::collection($this->resource
                ->complaints
                ->map
                ->type
                ->unique('id')),
        ];

        if (static::$short === false) {
            $resource['post'] = new ChannelPostResource($this->resource->post);
        }

        return $resource;
    }

}
