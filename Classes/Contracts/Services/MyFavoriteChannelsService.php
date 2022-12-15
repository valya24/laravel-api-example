<?php
declare(strict_types=1);

namespace UserFeed\Classes\Contracts\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use UserFeed\Classes\Dto\ChannelPostsShowDto;
use UserFeed\Models\Channel;

/**
 * Interface MyFavoriteChannelsService
 * @package UserFeed\Classes\Contracts\Services
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
interface MyFavoriteChannelsService
{
    /**
     * @param int $iOffset
     * @param User $obUser
     * @return Collection|null
     */
    public function getFavoriteChannels(int $iOffset, User $obUser): ?Collection;

}

