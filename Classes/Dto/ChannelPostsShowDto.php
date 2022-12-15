<?php
declare(strict_types=1);

namespace UserFeed\Classes\Dto;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class ChannelPostsShowDto
 * @package UserFeed\Classes\Dto
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostsShowDto extends DataTransferObject
{
    /**
     * @var int
     */
    public int $channel_id;

    /**
     * @var int
     * @deprecated use OffsetRequest
     */
    public int $offset;

}
