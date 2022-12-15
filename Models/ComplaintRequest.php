<?php
declare(strict_types=1);

namespace UserFeed\Models;

use App\Models\User;
use App\Traits\Scopes\GetByUserIDScope;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use UserFeed\Enums\Complaint as ComplaintEnum;

/**
 * Class ComplaintRequest
 * @package UserFeed\Models
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 *
 * @property integer $channel_post_id
 * @property ComplaintEnum $status
 * @property string $delete_reason
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Collection|null $complaints
 * @property-read ChannelPost|null $post
 * @property-read User|null $user
 * @property-read User|null $user_resolver
 */
class ComplaintRequest extends Model
{
    use GetByUserIDScope;

    /**
     * @var string
     */
    public $table = 'complaint_requests';

    /* @var array */
    protected $fillable = [
        'channel_post_id',
        'status',
        'delete_reason',
    ];

    /**
     * @var string[]
     */
    public $enums = [
        'status' => ComplaintEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(ChannelPost::class, 'channel_post_id')
            ->withTrashed();
    }

    /**
     * @return BelongsToMany
     */
    public function complaints(): BelongsToMany
    {
        return $this->belongsToMany(Complaint::class, 'complaint_request_complaints');
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
    public function user_resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolve_user_id');
    }
}
