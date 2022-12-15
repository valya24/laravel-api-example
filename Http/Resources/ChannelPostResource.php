<?php
declare(strict_types=1);

namespace UserFeed\Http\Resources;

use App\Http\Resources\ShortUserResource;
use App\Http\Resources\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use UserFeed\Classes\Dto\ChannelPostSeoDto;
use UserFeed\Models\ChannelPost;

/**
 * Class ChannelPostResource
 * @package UserFeed\Http\Resources
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostResource extends JsonResource
{
    /** @var ChannelPost */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if (!$this->resource) {
            return [];
        }

        return [
            'id' => $this->resource->id,
            'channel_id' => $this->resource->channel_id,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'short_description' => $this->resource->short_description,
            'description' => $this->resource->description,
            'seo' => array_merge((new ChannelPostSeoDto)->toArray(), (array) $this->seo),
            'created_at' => $this->resource->created_at,
            'user'          => new ShortUserResource($this->resource->channel->user),
            'has_update' => Gate::allows('update',  [ChannelPost::class, $this->channel_id]),
            'has_delete' => Gate::allows('destroy', [ChannelPost::class, $this->channel_id]),
            'viewed' => (int) $this->resource->viewed,
            'reactions' => array_merge(['like' => 0, 'dislike' => 0], $this->resource->reaction_summary->toArray()),
            'current_reacted' => $this->getCurrentReacted(),
            'last_modified' => $this->getLastModified()
        ];
    }

    /**
     * @return string
     */
    private function getCurrentReacted(): string
    {
        if (!Auth::check()) {
            return '';
        }

        return $this->resource->reacted->type ?? '';
    }

    /**
     * @return string
     */
    private function getLastModified(): string
    {
        if (empty($this->modified_by)) {
            return '';
        }

        $isAdminOwner = $this->resource->user_id === $this->resource->channel->user_id;
        return $isAdminOwner && $this->modified_by === $this->user_id ? 'user' : 'admin';
    }

}
