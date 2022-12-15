<?php
declare(strict_types=1);

namespace UserFeed\Observers;

use App\Classes\Managers\NotificationManager;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use UserFeed\Models\Channel;
use UserFeed\Notifications\PostCreated;
use UserFeed\Notifications\PostDeleted;
use UserFeed\Notifications\PostUpdated;
use UserFeed\Models\ChannelPost;

/**
 * Class ChannelPostObserver
 * @package UserFeed\Observers
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostObserver
{
    /**
     *
     */
    private const CHUNK_LIMIT = 20;

    /**
     * @param ChannelPost $obChannelPost
     */
    public function created(ChannelPost $obChannelPost): void
    {
        $this->notifySubscribedUsers($obChannelPost);
    }

    /**
     * @param ChannelPost $obChannelPost
     * @void
     */
    public function saving(ChannelPost $obChannelPost): void
    {
        if ($obChannelPost->isDirty('complaint_status')) {
            return;
        }

        if ($obChannelPost->exists && \Auth::check()) {
            $obChannelPost->modifiedBy()->associate(\Auth::user());
        }
    }

    /**
     * @param ChannelPost $obChannelPost
     * @void
     */
    public function updated(ChannelPost $obChannelPost): void
    {
        if ($obChannelPost->isDirty('complaint_status')) {
            return;
        }

        if (
            (\Auth::check() && \Auth::user()->isAdmin)
            && ($obChannelPost->user_id != $obChannelPost->modifiedBy->id)
        ) {
            NotificationManager::instance()->send($obChannelPost->user, new PostUpdated($obChannelPost));
        }
    }

    /**
     * @param ChannelPost $obChannelPost
     * @void
     */
    public function deleted(ChannelPost $obChannelPost): void
    {
        if (
            (\Auth::check() && \Auth::user()->isAdmin)
            && ($obChannelPost->user_id != \Auth::user()->getKey())
        ) {
            NotificationManager::instance()->send(User::find($obChannelPost->user_id), new PostDeleted($obChannelPost));
        }
    }

    /**
     * @param ChannelPost $obChannelPost
     * @void
     */
    private function notifySubscribedUsers(ChannelPost $obChannelPost): void
    {
        /** @var BelongsToMany|Channel $obSubcriberBuilder */
        $obSubcriberBuilder = $obChannelPost->channel->subscribers();
        $obSubcriberBuilder
            ->withoutMuted()
            ->cursor()
            ->each(function (User $obUser) use ($obChannelPost) {
                NotificationManager::instance()->send($obUser, new PostCreated($obChannelPost));
            });
    }

}
