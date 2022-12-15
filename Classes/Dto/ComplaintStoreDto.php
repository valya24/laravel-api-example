<?php
declare(strict_types=1);

namespace UserFeed\Classes\Dto;

use Spatie\DataTransferObject\DataTransferObject;
use UserFeed\Http\Requests\ChannelPost\ChannelPostUpdateRequest;
use UserFeed\Http\Requests\Complaint\ComplaintStoreRequest;

/**
 * Class ComplaintStoreDto
 * @package UserFeed\Classes\Dto
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintStoreDto extends DataTransferObject
{
    /**
     * @var string
     */
    public string $type_id;

    /**
     * @var string|null
     */
    public ?string $description = null;

    /**
     * @var string
     */
    public string $user_id;

    /**
     * @var string
     */
    public string $channel_post_id;


}
