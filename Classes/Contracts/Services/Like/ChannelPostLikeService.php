<?php
declare(strict_types=1);

namespace UserFeed\Classes\Contracts\Services\Like;

use UserFeed\Models\ChannelPost;

/**
 * Interface ChannelPostLike
 * @package UserFeed\Classes\Contracts\Services\Like
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
interface ChannelPostLikeService
{
    /**
     * @param int $iPostId
     * @return ChannelPost|null
     */
    public function like(int $iPostId): ?ChannelPost;

    /**
     * @param int $iPostId
     * @return ChannelPost|null
     */
    public function dislike(int $iPostId): ?ChannelPost;

}
