<?php
declare(strict_types=1);

namespace UserFeed\Services;

use App\Classes\Dto\FilterDto;
use App\Classes\Dto\OffsetDto;
use App\Classes\Dto\OffsetPaginationDTO;
use App\Traits\OffsetPaginationHelper;
use Illuminate\Database\Eloquent\Builder;
use UserFeed\Classes\Contracts\Services\ChannelService as ChannelServiceContract;
use \App\Models\User;
use UserFeed\Models\Channel;

/**
 * Class UserFeedService
 * @package UserFeed\Services
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelService implements ChannelServiceContract
{
    use OffsetPaginationHelper;

    /**
     * @inheritDoc
     */
    public function toggleActiveByUserId(int $userId, bool $isActivate): Channel
    {
        $obUser = User::findOrFail($userId);

        return $this->toggleActive($obUser, $isActivate);
    }

    /**
     * @inheritDoc
     */
    public function toggleActive(User $obUser, bool $isActivate): Channel
    {
        return Channel::updateOrCreate(
            ['user_id' => $obUser->getKey()],
            ['is_enabled' => $isActivate]
        );

    }

    /**
     * @inheritDoc
     */
    public function userFavorite(User $obUser, OffsetDto $obOffsetDto, ?FilterDto $obFilterDto = null): OffsetPaginationDTO
    {
        if (!$obFilterDto) {
            $obFilterDto = new FilterDto;
        }

        $obBuilder = Channel::subscribeUser($obUser)
            ->orderBy('channel_subscribers.created_at', 'DESC')
            ->offset($obOffsetDto->offset)
            ->limit($obOffsetDto->limit ?? ChannelServiceContract::LIMIT_CHUNK)
            ->when($obFilterDto->search, static function(Builder $obQuery, string $sSearch): void {
                $obQuery->whereHas('user', static function($obQuery) use($sSearch): void {
                    $obQuery->findByName($sSearch);
                });
            });

        return $this->offsetPaginate($obBuilder);
    }
}
