<?php
declare(strict_types=1);

namespace UserFeed\Services\Like;

use UserFeed\Classes\Contracts\Services\Like\ChannelPostLikeService as ChannelPostLikeServiceContract;
use UserFeed\Models\ChannelPost;

/**
 * Class LikeService
 * @package UserFeed\Services\Like
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class LikeService implements ChannelPostLikeServiceContract
{
    /**
     * @inheritDoc
     */
    public function like(int $iPostId): ?ChannelPost
    {
        $obChannelPost = ChannelPost::findOrFail($iPostId);

        $obChannelPost->toggleReaction('like');

        return $obChannelPost;
    }

    /**
     * @inheritDoc
     */
    public function dislike(int $iPostId): ?ChannelPost
    {
        $obChannelPost = ChannelPost::findOrFail($iPostId);

        $obChannelPost->toggleReaction('dislike');

        return $obChannelPost;
    }

}
