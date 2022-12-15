<?php
declare(strict_types=1);

namespace UserFeed\Classes\Dto;

use Spatie\DataTransferObject\DataTransferObject;
use UserFeed\Http\Requests\ChannelPost\ChannelPostUpdateRequest;

/**
 * Class ChannelPostUpdateDto
 * @package UserFeed\Classes\Dto
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostUpdateDto extends DataTransferObject
{
    /**
     * @var string|null
     */
    public ?string $title = null;

    /**
     * @var string|null
     */
    public ?string $channel_id = null;

    /**
     * @var string|null
     */
    public ?string $short_description = null;

    /**
     * @var string|null
     */
    public ?string $description = null;

    /**
     * @var ChannelPostSeoDto|null
     */
    public ?ChannelPostSeoDto $seo = null;

    /**
     * @param ChannelPostUpdateRequest $obRequest
     * @return ChannelPostUpdateDto
     */
    public static function fromRequest(ChannelPostUpdateRequest $obRequest): ChannelPostUpdateDto
    {
        $arParams = $obRequest->validated();
        $obDto = new static(\Arr::except($arParams, 'seo'));
        if (array_key_exists('seo', $arParams)) {
            $obDto->seo = new ChannelPostSeoDto($arParams['seo']);
        }

        return $obDto;
    }


}
