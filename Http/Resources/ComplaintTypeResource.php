<?php
declare(strict_types=1);

namespace UserFeed\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ComplaintTypeResource
 * @package UserFeed\Http\Resources
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintTypeResource extends JsonResource
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
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'has_free' => $this->has_free,
        ];
    }

}
