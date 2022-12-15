<?php
declare(strict_types=1);

namespace UserFeed\Classes\Dto;

use App\Models\User;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class ChannelPostShowDto
 * @package UserFeed\Classes\Dto
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostShowDto extends DataTransferObject
{
    /**
     * @var int
     */
    public int $channel_id;

    /**
     * @var int
     */
    public int $post_id;

    /**
     *
     */
    public ?User $obUser = null;

}
