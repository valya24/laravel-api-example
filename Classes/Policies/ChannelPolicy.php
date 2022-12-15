<?php
declare(strict_types=1);

namespace UserFeed\Classes\Policies;

use App\Models\User;
use UserFeed\Models\Channel;

/**
 * Class ChannelPolicy
 * @package UserFeed\Classes\Policies
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPolicy
{
    /**
     * @param User $obUser
     * @param int $iChannelId
     * @return bool
     */
    public function subscribe(User $obUser, int $iChannelId): bool
    {
        return $obUser->getKey() !== $iChannelId;
    }

    /**
     * @param User $obUser
     * @param int $iUserId
     * @return bool
     */
    public function update(User $obUser, int $iUserId): bool
    {
        return $obUser->isAdmin || $obUser->getKey() == $iUserId;
    }

    /**
     * @param User|null $obUser
     * @param Channel $obChannel
     * @return bool
     */
    public function show(?User $obUser = null, Channel $obChannel): bool
    {
        return $obChannel->is_enabled;
    }

    /**
     * Policy to see channel subscribers count
     * @param User $obUser
     * @param Channel $obChannel
     * @return bool
     */
    public function seeSubscribers(User $obUser, Channel $obChannel): bool
    {
        return $obChannel->user_id !== (int) config('app.user_nixon_id');
    }

    /**
     * Policy to user has subscribe to channel
     * @param User $obUser
     * @param Channel $obChannel
     * @return bool
     */
    public function hasSubscribe(User $obUser, Channel $obChannel): bool
    {
        if ($obChannel->user_id === (int) config('app.user_nixon_id')) {
            return false;
        }

        return $obChannel->isSubscribe($obUser) === false;
    }

    /**
     * Policy user has unsubscribe channel
     * @param User $obUser
     * @param Channel $obChannel
     * @return bool
     */
    public function hasUnSubscribe(User $obUser, Channel $obChannel): bool
    {
        if ($obChannel->user_id === (int) config('app.user_nixon_id')) {
            return false;
        }

        return $obChannel->isSubscribe($obUser) === true;
    }
}
