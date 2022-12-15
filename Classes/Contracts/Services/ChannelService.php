<?php
declare(strict_types=1);

namespace UserFeed\Classes\Contracts\Services;

use App\Classes\Dto\FilterDto;
use App\Classes\Dto\OffsetDto;
use App\Classes\Dto\OffsetPaginationDTO;
use App\Models\User;
use UserFeed\Models\Channel;

/**
 * Interface ChannelService
 * @package UserFeed\Classes\Contracts\Services
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
interface ChannelService
{
    /**
     * limit items in chunk
     */
    public const LIMIT_CHUNK = 10;

    /**
     * @param User $obUser
     * @param bool $isActivate
     * @return Channel
     */
    public function toggleActive(User $obUser, bool $isActivate): Channel;

    /**
     * @param int $userId
     * @param bool $isActivate
     * @return Channel
     */
    public function toggleActiveByUserId(int $userId, bool $isActivate): Channel;

    /**
     * @param User $obUser
     * @param OffsetDto $obOffsetDto
     * @param FilterDto|null $obFilterDto
     * @return OffsetPaginationDTO
     */
    public function userFavorite(User $obUser, OffsetDto $obOffsetDto, ?FilterDto $obFilterDto = null): OffsetPaginationDTO;
}

