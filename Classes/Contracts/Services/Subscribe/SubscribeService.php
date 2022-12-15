<?php
declare(strict_types=1);

namespace UserFeed\Classes\Contracts\Services\Subscribe;

use App\Models\User;
use UserFeed\Models\Channel;

/**
 * Interface SubscribeService
 * @package UserFeed\Classes\Contracts\Services\Subscribe
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
interface SubscribeService
{

    /**
     * @param User $obUser
     * @param int $iChannelId
     * @return Channel|null
     */
    public function subscribe(User $obUser, int $iChannelId): ?Channel;


    /**
     * @param User $obUser
     * @param int $iChannelId
     * @return Channel|null
     */
    public function unsubscribe(User $obUser, int $iChannelId): ?Channel;

    /**
     * @param User $obUser
     * @param Channel $obChannel
     * @return Channel
     */
    public function mute(User $obUser, Channel $obChannel): Channel;

    /**
     * @param User $obUser
     * @param Channel $obChannel
     * @return Channel
     */
    public function unMute(User $obUser, Channel $obChannel): Channel;

}
