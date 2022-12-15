<?php
declare(strict_types=1);

namespace UserFeed\Notifications;

use App\Events\Socket\UserNotificationChannel;
use App\Http\Resources\ShortUserResource;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use UserFeed\Models\ChannelPost;

/**
 * Class PostDeleted
 *
 * "Пост был отредактирован администратором."
 * @package UserFeed\Notifications
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class PostDeleted extends Notification
{
    use Queueable;

    public $name = 'post_deleted';

    /**
     * @var array
     */
    public $roles = User::AVAILABLE_ROLES;

    /**
     * @var ChannelPost
     */
    private $obChannelPost;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ChannelPost $obChannelPost)
    {
        $this->obChannelPost = $obChannelPost;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database', UserNotificationChannel::class];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        return [
            'post_deleted' => [
                'post_title' => $this->obChannelPost->title,
                'post_slug' => $this->obChannelPost->slug,
                'action' => 'deleted',
                'deleted_by' => 'admin',
                'user'       => new ShortUserResource($this->obChannelPost->user),
            ]
        ];
    }

}
