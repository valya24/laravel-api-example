<?php
declare(strict_types=1);
namespace UserFeed\Classes\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use UserFeed\Models\Channel;

/**
 * Trait UserFeedTrait
 * @package UserFeed\Classes\Traits
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 *
 * @property \Illuminate\Database\Eloquent\Collection|Channel|null $feedChannel
 */
trait ChannelTrait
{
    /**
     * This method is called upon instantiation of the Eloquent Model.
     * It adds the "active_channel" field to the "$fillable" array of the model.
     * @return void
     */
    public function initializeUserFeedTrait()
    {
        $this->fillable[] = 'active_channel';
        $this->casts['active_channel'] = 'boolean';
    }

    /**
     * @return HasOne
     */
    public function feedChannel(): HasOne
    {
        return $this->hasOne(Channel::class, 'user_id');
    }
}
