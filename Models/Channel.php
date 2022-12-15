<?php
declare(strict_types=1);

namespace UserFeed\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\JoinClause;
use App\Traits\HasMuted;

/**
 * Class Channel
 * @package UserFeed\Models
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 *
 * @property int                                                        $user_id
 * @property boolean                                                    $is_enabled
 * @property string                                                     $seo
 * @property \Carbon\Carbon                                             $created_at
 * @property \Carbon\Carbon                                             $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection|ChannelPost|null  $channelPosts
 * @property \Illuminate\Database\Eloquent\Collection|User|null         $subscribers
 * @method static Builder|$this subscribeUser(User $obUser)
 * @method static Builder|$this isEnabled()
 */
class Channel extends Model
{
    use HasMuted;
    /**
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * @var string
     */
    public $table = 'feed_channels';

    /* @var array */
    protected $fillable = [
        'user_id',
        'subscribers',
        'is_enabled',
        'seo'
    ];

    /* @var array */
    protected $casts = [
        'is_enabled' => 'boolean',
        'seo' => 'array',
    ];

    /**
     * @var bool|array
     */
    private array $arCheckSubscribers = [];


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function channelPosts(): HasMany
    {
        return $this->hasMany(ChannelPost::class, 'channel_id', 'user_id');
    }

    /**
     * @return BelongsToMany
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'channel_subscribers', 'channel_id', 'subscriber_id');
    }

    /**
     * @param Builder $obQuery
     * @param User $obUser
     */
    public function scopeSubscribeUser(Builder $obQuery, User $obUser): void
    {
        $obQuery->join('channel_subscribers', static function (JoinClause $obQuery) use ($obUser) {
            $obQuery->on('feed_channels.user_id', '=', 'channel_subscribers.channel_id')
                ->where('channel_subscribers.subscriber_id', $obUser->getKey());
        });
    }

    /**
     * @param Builder $obQuery
     */
    public function scopeIsEnabled(Builder $obQuery): void
    {
        $obQuery->where('is_enabled', true);
    }

    /**
     * @param User|null $obUser
     * @return bool
     */
    public function isSubscribe(?User $obUser): bool
    {
        if (null === $obUser) {
            return false;
        }

        if (!isset($this->arCheckSubscribers[$obUser->getKey()])) {
            $this->arCheckSubscribers[$obUser->getKey()] = $this->subscribers()
                ->where('subscriber_id', $obUser->getKey())
                ->exists();
        }

        return $this->arCheckSubscribers[$obUser->getKey()];
    }

    /**
     * @param User $obUser
     * @param bool $bState
     * @return $this
     */
    public function makeMute(User $obUser, bool $bState): self
    {
        $this->subscribers()
            ->updateExistingPivot(
                $obUser->getKey(),
                [$this->getMutedColumnName() => $bState],
                false
            );

        return $this;
    }


}
