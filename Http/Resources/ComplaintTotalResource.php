<?php
declare(strict_types=1);

namespace UserFeed\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use UserFeed\Models\ChannelPost;

/**
 * Class ComplaintTotalResource
 * @package UserFeed\Http\Resources
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintTotalResource extends JsonResource
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
            $this->complaint_status => $this->total
        ];
    }


}
