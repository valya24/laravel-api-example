<?php
declare(strict_types=1);

namespace UserFeed\Classes\Dto;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class ChannelPostSeoDto
 * @package UserFeed\Classes\Dto
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostSeoDto extends DataTransferObject
{
    /**
     * @var string|null
     */
    public ?string $meta_title = null;

    /**
     * @var string|null
     */
    public ?string $meta_description = null;

    /**
     * @var string|null
     */
    public ?string $meta_keywords = null;

}
