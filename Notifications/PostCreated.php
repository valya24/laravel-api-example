<?php
declare(strict_types=1);

namespace UserFeed\Notifications;

use App\Events\Socket\UserNotificationChannel;
use App\Http\Resources\ShortUserResource;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use UserFeed\Models\ChannelPost;

/**
 * Class PostCreated
 *
 * "Новый пост."
 * @package UserFeed\Notifications
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class PostCreated extends Notification
{
    use Queueable;

    public $name = 'post_created';

    /**
     * @var array
     */
    public $roles = User::AVAILABLE_ROLES;

    /**
     * @var ChannelPost
     */
    private $obChannelPost;

    /**
     *
     */
    private const NOTIFICATION_TEXT_LIMIT = 30;

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
            'post' => [
                'post_title' => $this->obChannelPost->title,
                'post_slug'  => $this->obChannelPost->slug,
                'post_short_description' => Str::limit($this->obChannelPost->short_description, static::NOTIFICATION_TEXT_LIMIT,'...'),
                'user'       => new ShortUserResource($this->obChannelPost->user),
                'action' => 'created',
            ]
        ];
    }

}
