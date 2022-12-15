<?php
declare(strict_types=1);

namespace UserFeed\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Qirolab\Laravel\Reactions\Contracts\ReactableInterface;
use Qirolab\Laravel\Reactions\Traits\Reactable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use UserFeed\Classes\Dto\ChannelPostSeoDto;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ChannelPost
 * @package UserFeed\Models
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 *
 * @property Channel $channel_id
 * @property User $user_id
 * @property User $modified_by
 * @property string $title
 * @property string $slug
 * @property string $short_description
 * @property string $description
 * @property string $seo
 * @property integer $viewed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Collection $complaints
 * @property-read Channel $channel
 * @property-read Collection $complaintRequests
 * @property-read ComplaintRequest $currentComplaintRequest
 * @property-read User $user
 *
 */
class ChannelPost extends Model implements HasMedia, ReactableInterface
{
    use InteractsWithMedia;
    use Reactable;
    use SoftDeletes;

    /**
     * @var string
     */
    public $table = 'channel_posts';

    /* @var array */
    protected $fillable = [
        'channel_id',
        'user_id',
        'modified_by',
        'title',
        'slug',
        'short_description',
        'description',
        'seo'
    ];

    /**
     * @param ChannelPostSeoDto|null $obChannelPostSeoDto
     */
    public function setSeoAttribute(ChannelPostSeoDto $obChannelPostSeoDto = null)
    {
        $this->attributes['seo'] = $obChannelPostSeoDto ? json_encode($obChannelPostSeoDto->toArray()) : $obChannelPostSeoDto;
    }

    /**
     * @param $value
     * @return ChannelPostSeoDto|null
     */
    public function getSeoAttribute($value): ?ChannelPostSeoDto
    {
        $value = is_string($value) ? json_decode($value, true) : $value;

        return $value ? new ChannelPostSeoDto($value) : $value;
    }

    /**
     * @param string $sValue
     */
    public function setSlugAttribute(string $sValue)
    {
        if ($this->whereSlug($sSlug = Str::slug($sValue))->exists()) {

            $sSlug = $this->incrementSlug($sSlug);
        }

        $this->attributes['slug'] = $sSlug;
    }

    /**
     * @param string $sSlug
     * @return string
     */
    public function incrementSlug(string $sSlug): string
    {
        $sOriginal = $sSlug;

        $iCount = 2;

        while ($this->whereSlug($sSlug)->exists()) {

            $sSlug = "{$sOriginal}-" . $iCount++;
        }

        return $sSlug;

    }

    /**
     * @return BelongsTo
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class, 'channel_id', 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function modifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    /**
     * @return HasMany
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }


    /**
     * @return $this
     */
    public function addView(): self
    {
        $this->increment('viewed');
        return $this;
    }

    /**
     * @param Builder $obQuery
     * @param Channel $channel
     */
    public function scopeFromChannel(Builder $obQuery, Channel $channel)
    {
        $obQuery->where('channel_id', $channel->getKey());
    }

    /**
     * @return HasMany
     */
    public function complaintRequests(): HasMany
    {
        return $this->hasMany(ComplaintRequest::class, 'channel_post_id');
    }

    /**
     * @return HasOne
     */
    public function currentComplaintRequest(): HasOne
    {
        return $this->hasOne(ComplaintRequest::class, 'channel_post_id')
            ->whereIn('status', \UserFeed\Enums\Complaint::progressStatuses());
    }
}
