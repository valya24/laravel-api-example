<?php
declare(strict_types=1);

namespace UserFeed\Classes\Support;


use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use UserFeed\Classes\Exceptions\ImageConvertIsErrorException;
use UserFeed\Classes\Helper\MediaHelper;
use UserFeed\Models\ChannelPost;
use UserFeed\Models\TemporaryUpload;

/**
 * Class HtmlImageHandler
 * @package UserFeed\Classes\Support
 */
class HtmlImageHandler
{
    /**
     * @var User
     */
    private User $obUser;

    /**
     * @var ChannelPost
     */
    private ChannelPost $obChannelPost;

    /**
     * @var \Illuminate\Support\Collection
     */
    private \Illuminate\Support\Collection $images;

    /**
     * @var array
     */
    private array $toDelete = [];

    /**
     * @var \DOMDocument
     */
    private \DOMDocument $DOMDocument;

    /**
     * HtmlImageHandler constructor.
     * @param ChannelPost $obChannelPost
     */
    public function __construct(User $obUser, ChannelPost $obChannelPost)
    {
        $this->images        = new \Illuminate\Support\Collection;
        $this->obUser        = $obUser;
        $this->obChannelPost = $obChannelPost;
        $this->domDocument   = new \DOMDocument;
    }

    /**
     * @return string
     */
    public function convert(): string
    {
       try {
           $sDescription = mb_convert_encoding($this->obChannelPost->description, 'HTML-ENTITIES', "UTF-8");
           $this->domDocument->loadHTML($sDescription, \LIBXML_HTML_NODEFDTD);

           $this->prepareImages();
           $this->deleteImages();

           $html  = $this->domDocument->saveHTML($this->domDocument->documentElement);
           return Str::substr($html,12, -14);
       } catch (\Exception $e) {
           throw new ImageConvertIsErrorException;
       }
    }

    /**
     *
     */
    protected function prepareImages(): void
    {
        $obImageList = collect($this->domDocument->getElementsByTagName('img'))
            ->filter(function($obImage) {
                if ($this->isEmpty($obImage)) {
                    $this->toDelete[] = $obImage;
                    return false;
                }

                return true;
            })
            ->keyBy(fn($obImage) => $obImage->getAttribute('data-img-id'));

        /** @var Collection $inDatabase */
        $inDatabase = Media::whereHasMorph('model', [ChannelPost::class, TemporaryUpload::class], function($query) {
                // Проверяем, что пользователь загрузивший изображение владелец поста или временного блока.
                $query->whereIn('user_id', [$this->obChannelPost->user_id, $this->obUser->getKey()]);
            })
            ->whereIn('uuid', $obImageList->keys()->all())
            ->get()
            ->keyBy('uuid');

        $this->images = $obImageList->transform(function($obImage, $key) use($inDatabase) {
           if (!$this->hasSave($obImage, $inDatabase)) {
               $this->toDelete[] = $obImage;
               return null;
           }

           return $inDatabase->get($key);
        })
            ->filter()
            ->keyBy('uuid');

        unset($obImageList, $inDatabase);
    }

    /**
     * @param \DOMElement $obImage
     * @return bool
     */
    protected function isEmpty(\DOMElement $obImage): bool
    {
        return empty($obImage->getAttribute('src')) ||
            empty($obImage->getAttribute('data-img-id'));
    }

    /**
     * @param $obImage
     * @param $inDatabase
     * @return bool
     */
    protected function hasSave($obImage, $inDatabase)
    {
        /** @var Media $modelInDatabase */
        $modelInDatabase = $inDatabase->get($obImage->getAttribute('data-img-id'));
        return !empty($modelInDatabase) && MediaHelper::generateMediaUrl($modelInDatabase) == $obImage->getAttribute('src');
    }

    /**
     * @throws \Throwable
     */
    public function saveImages(): void
    {
        Media::whereIn('id', $this->images->pluck('id'))
            ->update([
                'model_type' => get_class($this->obChannelPost),
                'model_id'  => $this->obChannelPost->getKey()
            ]);
    }

    /**
     *
     */
    public function diffImages(): void
    {
        $mediaInPost = $this->obChannelPost->media()->get()->keyBy('uuid');
        $mediaWithoutPost = $mediaInPost->diffKeys($this->images);

        Media::whereIn('id', $mediaWithoutPost->pluck('id'))->delete();
        \Storage::delete($mediaWithoutPost->map(fn(Media $obMedia) => $obMedia->getPath())->all());
    }

    /**
     *
     */
    protected function deleteImages(): void
    {
        foreach($this->toDelete as $obImage) {
            $obImage->parentNode->removeChild($obImage);
        }

        unset($this->toDelete);
    }
}
