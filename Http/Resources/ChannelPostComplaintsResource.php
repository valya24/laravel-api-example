<?php
declare(strict_types=1);

namespace UserFeed\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ChannelPostShowWithComplaintResource
 * @package UserFeed\Http\Resources
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 * @depreacted not use this Resource
 */
class ChannelPostComplaintsResource extends JsonResource
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
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'type' => $this->type->name,
            'other_description' => $this->description,
            'post_id' => $this->channel_post_id,
            'created_at' => $this->created_at
        ];
    }

}
