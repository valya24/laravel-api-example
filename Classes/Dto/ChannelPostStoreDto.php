<?php
declare(strict_types=1);

namespace UserFeed\Classes\Dto;

use Spatie\DataTransferObject\DataTransferObject;
use UserFeed\Http\Requests\ChannelPost\ChannelPostStoreRequest;

/**
 * Class ChannelPostStoreDto
 * @package UserFeed\Classes\Dto
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostStoreDto extends DataTransferObject
{
    /**
     * @var string
     */
    public string $title;

    /**
     * @var string
     */
    public string $channel_id;

    /**
     * @var string
     */
    public string $short_description;

    /**
     * @var string
     */
    public string $description;

    /**
     * @var ChannelPostSeoDto|null
     */
    public ?ChannelPostSeoDto $seo = null;

    /**
     * @param ChannelPostStoreRequest $obRequest
     * @return ChannelPostStoreDto
     */
    public static function fromRequest(ChannelPostStoreRequest $obRequest): ChannelPostStoreDto
    {
        $arParams = $obRequest->validated();
        $obDto = new static(\Arr::except($arParams, 'seo'));
        if (array_key_exists('seo', $arParams)) {
            $obDto->seo = new ChannelPostSeoDto($arParams['seo']);
        }

        return $obDto;
    }


}
