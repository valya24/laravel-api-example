<?php
declare(strict_types=1);

namespace UserFeed\Http\Resources;

use App\Http\Resources\ShortUserResource;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Http\Resources\JsonResource;
use Illuminate\Support\Facades\Auth;
use UserFeed\Models\Channel;

/**
 * Class ChannelResource
 * @package UserFeed\Http\Resources
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelResource extends JsonResource
{
    /** @var Channel */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this->resource);
        return [
            'id'              => $this->resource->user_id,
            'is_enabled'      => $this->resource->is_enabled,
            'subscribers'     => $this->getSubscribers(),
            'is_subscribed'   => $this->resource->isSubscribe(Auth::user()),
            'is_verified'     => (bool) $this->resource->user->is_verified,
            'user'            => new ShortUserResource($this->resource->user),
            'has_subscribe'   => \Gate::allows('hasSubscribe', $this->resource),
            'has_unsubscribe' => \Gate::allows('hasUnsubscribe', $this->resource),
            'is_muted'        => $this->resource->isMuted(Auth::user()),
        ];
    }

    /**
     * @param BelongsToMany $obSubscribers
     * @return int|null
     */
    private function getSubscribers(): ?int
    {
        if (\Gate::denies('seeSubscribers', $this->resource)) {
            return null;
        }

        return $this->resource->subscribers()->count();
    }

}
