<?php
declare(strict_types=1);

namespace UserFeed\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use UserFeed\Classes\Contracts\Services\ChannelPostService as ChannelPostServiceContract;
use UserFeed\Classes\Contracts\Services\Complaint\ComplaintRequestService;
use UserFeed\Classes\Dto\ChannelPostSeoDto;
use UserFeed\Classes\Dto\ChannelPostShowDto;
use UserFeed\Classes\Dto\ChannelPostsShowDto;
use UserFeed\Classes\Dto\ChannelPostStoreDto;
use UserFeed\Classes\Dto\ChannelPostUpdateDto;
use UserFeed\Classes\Support\HtmlImageHandler;
use UserFeed\Models\Channel;
use UserFeed\Models\ChannelPost;

/**
 * Class ChannelPostService
 * @package UserFeed\Services
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostService implements ChannelPostServiceContract
{
    /**
     * Users should not unsubscribe from this user
     * @deprecated see config('app.user_nixon_id')
     */
    private const R_Nixon_Id = 1;

    /**
     * limit items in chunk
     */
    public const LIMIT_CHUNK = 10;

    /**
     * @inheritDoc
     */
    public function getChannelPosts(ChannelPostsShowDto $obChannelPostsShowDto): ?Collection
    {
        return ChannelPost::with('channel.user')
            ->where('channel_id', $obChannelPostsShowDto->channel_id)
            ->orderBy('created_at', 'DESC')
            ->offset($obChannelPostsShowDto->offset)
            ->limit(static::LIMIT_CHUNK)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getFeeds(int $iOffset, User $obUser): ?Collection
    {
        return ChannelPost::where(function(Builder $obQuery) use($obUser) {
                $obQuery->whereHas('channel.subscribers', function (Builder $obQuery) use ($obUser) {
                    $obQuery->where('subscriber_id', $obUser->id);
                })->orWhereHas('channel', function(Builder $obQuery) {
                    $obQuery->where('user_id', config('app.user_nixon_id'));
                });
            })
            ->offset($iOffset)
            ->limit(static::LIMIT_CHUNK)
            ->orderBy('channel_posts.created_at', 'DESC')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function show(ChannelPostShowDto $obChannelPostShowDto): ?ChannelPost
    {
        $obChannel = Channel::findOrFail($obChannelPostShowDto->channel_id);
        $obChannelPost = $obChannel
            ->channelPosts()
            ->where('id', $obChannelPostShowDto->post_id)
            ->when(
                $obChannelPostShowDto->obUser && $obChannelPostShowDto->obUser->isAdmin,
                static function (Builder $obQuery) {
                    /** @var Builder|Channel $obQuery */
                    $obQuery->withTrashed();
                }
            )
            ->firstOrFail();

        return $obChannelPost;
    }

    /**
     * @inheritDoc
     */
    public function getByIdOrSlug(string $sIdOrSlug, ?User $obUser = null): ?ChannelPost
    {
        $obChannelPost =  ChannelPost::query()
            ->when($obUser && $obUser->is_admin, static function (Builder $obQuery) {
                 $obQuery->withTrashed();
            })
            ->where('slug', $sIdOrSlug)
            ->first();

        if (!empty($obChannelPost)) {
            $obChannelPost->increment('viewed');
        }

        return $obChannelPost;

    }

    /**
     * @inheritDoc
     */
    public function store(User $obUser, ChannelPostStoreDto $obPostStoreDto): ChannelPost
    {
        return DB::transaction(static function () use ($obUser, $obPostStoreDto) {
            $obChannelPost = new ChannelPost((array)\Arr::except($obPostStoreDto->all(), ['seo']));
            $obChannelPost->seo = $obPostStoreDto->seo;
            $obChannelPost->slug = $obPostStoreDto->title;
            $obChannelPost->user()->associate($obUser);

            $htmlImageHandler = new HtmlImageHandler($obUser, $obChannelPost);
            $obChannelPost->description = $htmlImageHandler->convert();
            $obChannelPost->save();

            $htmlImageHandler->saveImages();
            return $obChannelPost;
        });
    }

    /**
     * @inheritDoc
     */
    public function update(User $obUser, ChannelPostUpdateDto $obPostUpdateDto, int $iChannelPostId): ChannelPost
    {
        $arFiltered = array_filter(\Arr::except($obPostUpdateDto->toArray(), 'seo'));

        $obChannelPost = ChannelPost::findOrFail($iChannelPostId);
        $obChannelPost->fill(array_merge($arFiltered, ['slug' => $obPostUpdateDto->title]));

        $obSeo = $obPostUpdateDto->seo;
        if ($obPostUpdateDto->seo && $obChannelPost->seo) {
            $obSeo = array_merge(
                array_filter($obChannelPost->seo->toArray()),
                array_filter($obPostUpdateDto->seo->toArray())
            );
            $obSeo = new ChannelPostSeoDto($obSeo);
        }

        if ($obSeo) {
            $obChannelPost->seo = $obSeo;
        }

        return DB::transaction(static function () use ($obChannelPost, $obUser) {
            if ($obChannelPost->isDirty('description')) {
                $htmlImageHandler = new HtmlImageHandler($obUser, $obChannelPost);
                $obChannelPost->description = $htmlImageHandler->convert();

                $htmlImageHandler->saveImages();
                $htmlImageHandler->diffImages();
            }

            $obChannelPost->save();
            return $obChannelPost;
        });
    }

    /**
     * @inheritDoc
     */
    public function destroy(ChannelPost $obChannelPost, ?User $obUser = null): bool
    {
        try {
            if ($obUser && $obChannelPost->currentComplaintRequest) {
                /** @var ComplaintRequestService $obComplaintRequestService */
                $obComplaintRequestService = app(ComplaintRequestService::class);
                $obComplaintRequestService->deleteComplaintPostByUser($obUser, $obChannelPost->currentComplaintRequest);
            }

            return (bool) $obChannelPost->delete();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function destroyById(int $iChannelPostId): bool
    {
        $obChannelPost = ChannelPost::findOrFail($iChannelPostId);
        return $this->destroy($obChannelPost);
    }

}
