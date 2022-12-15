<?php
declare(strict_types=1);

namespace UserFeed\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use LeMaX10\Enums\Traits\ModelEnums;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Complaint
 * @package UserFeed\Models
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 *
 * @property int $type_id
 * @property string $description
 * @property string $status
 * @property string $seo
 * @property int $user_id
 * @property ChannelPost $channel_post_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read ComplaintType $type
 * @property-read User $user
 */
class Complaint extends Model
{
    use ModelEnums;

    /**
     * @var string
     */
    public $table = 'complaints';

    /**
     * @var string[]
     */
    public $fillable = ['type_id', 'description', 'status', 'user_id', 'channel_post_id'];

    /**
     * @var string[]
     */
    public $enums = [
        'status' => \UserFeed\Enums\Complaint::class,
    ];

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
    public function post(): BelongsTo
    {
        return $this->belongsTo(ChannelPost::class, 'channel_post_id');
    }

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ComplaintType::class);
    }

    /**
     * @return HasOne
     */
    public function complaintRequest(): HasOne
    {
        return $this->hasOne(ComplaintRequest::class, 'channel_post_id', 'channel_post_id');
    }

}
