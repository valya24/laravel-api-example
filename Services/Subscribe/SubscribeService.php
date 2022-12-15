<?php
declare(strict_types=1);

namespace UserFeed\Services\Subscribe;

use App\Models\User;
use \UserFeed\Classes\Contracts\Services\Subscribe\SubscribeService as SubscribeServiceContract;
use UserFeed\Http\Exceptions\NotSubscribeToChannelException;
use UserFeed\Models\Channel;


/**
 * Class SubscribeService
 * @package UserFeed\Services\Subscribe
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class SubscribeService implements SubscribeServiceContract
{

    /**
     * @inheritDoc
     */
    public function subscribe(User $obUser, int $iChannelId): ?Channel
    {
        $obChannel = Channel::findOrFail($iChannelId);

        $obChannel->subscribers()->syncWithoutDetaching($obUser);

        return $obChannel;
    }

    /**
     * @inheritDoc
     */
    public function unsubscribe(User $obUser, int $iChannelId): ?Channel
    {
        $obChannel = Channel::findOrFail($iChannelId);

        $obChannel->subscribers()->detach($obUser);

        return $obChannel;
    }

    /**
     * @inheritDoc
     */
    public function mute(User $obUser, Channel $obChannel): Channel
    {
        if (!$obChannel->isSubscribe($obUser)) {
            throw new NotSubscribeToChannelException;
        }

        $obChannel->mute($obUser, true);

        return $obChannel;
    }

    /**
     * @inheritDoc
     */
    public function unMute(User $obUser, Channel $obChannel): Channel
    {
        if (!$obChannel->isSubscribe($obUser)) {
            throw new NotSubscribeToChannelException;
        }

        $obChannel->mute($obUser, false);

        return $obChannel;
    }


}
