<?php
declare(strict_types=1);

namespace UserFeed\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
use UserFeed\Classes\Contracts\Services\MyFavoriteChannelsService as MyFavoriteChannelsServiceContract;
use UserFeed\Models\Channel;

/**
 * Class MyFavoriteChannelsService
 * @package UserFeed\Services
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class MyFavoriteChannelsService implements MyFavoriteChannelsServiceContract
{
    /**
     * limit items in chunk
     */
    public const LIMIT_CHUNK = 10;

    /**
     * @param int $iOffset
     * @param User $obUser
     * @return Collection|null
     * @depreacted use ChannelService->userFavorite
     */
    public function getFavoriteChannels(int $iOffset, User $obUser): ?Collection
    {
        return app(\UserFeed\Classes\Contracts\Services\ChannelService::class)
            ->userFavorite($obUser, $iOffset);
    }

}
