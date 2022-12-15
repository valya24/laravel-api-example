<?php
declare(strict_types=1);

namespace UserFeed\Http\Resources;


use App\Http\Resources\JsonResource;

/**
 * Class ComplaintTypeResource
 * @package UserFeed\Http\Resources
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class LikeResource extends JsonResource
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
            'count' => $this->resource->reaction_summary->toArray(),
        ];
    }

}
