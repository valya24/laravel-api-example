<?php
declare(strict_types=1);

namespace UserFeed\Classes\Helper;


use App\Classes\Enums\UserMediaType;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class MediaHelper
 * @package UserFeed\Classes\Helper
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class MediaHelper
{
    /**
     * @param User $obUser
     * @return array
     */
    public static function getUserMedia(User $obUser): array
    {
        return [
            UserMediaType::AVATAR()->getValue() => static::generateMediaUrl($obUser->getFirstMedia(UserMediaType::AVATAR()->getValue())),
            UserMediaType::BANNER()->getValue() => static::generateMediaUrl($obUser->getFirstMedia(UserMediaType::BANNER()->getValue()))
        ];
    }

    /**
     * @param Media|null $obMedia
     * @return string
     */
    public static function generateMediaUrl(?Media $obMedia = null): string
    {
        if (!$obMedia) {
            return '';
        }

        $sTemplate = '%s%s%s%s';
        return sprintf($sTemplate, config('app.url'), '/storage/media/', $obMedia->id . '/', $obMedia->file_name);
    }

}
