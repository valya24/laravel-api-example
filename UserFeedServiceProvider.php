<?php
declare(strict_types=1);

namespace UserFeed;

use App\Http\Middleware\UseApiGuard;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as BaseServiceProvider;
use UserFeed\Classes\Contracts\Services\Like\ChannelPostLikeService as ChannelPostLikeServiceContract;
use UserFeed\Classes\Policies\ChannelPolicy;
use UserFeed\Classes\Policies\ChannelPostPolicy;
use UserFeed\Classes\Policies\ComplaintPolicy;
use UserFeed\Classes\Policies\ComplaintRequestPolicy;
use UserFeed\Classes\Policies\ComplaintTypePolicy;
use UserFeed\Console\Commands\RemoveUnusedTempUploadsCommand;
use UserFeed\Models\Channel;
use UserFeed\Models\ChannelPost;
use UserFeed\Models\Complaint;
use UserFeed\Models\ComplaintRequest;
use UserFeed\Models\ComplaintType;
use UserFeed\Observers\ChannelPostObserver;
use UserFeed\Observers\ComplaintTypeObserver;
use UserFeed\Services\ChannelPostService;
use UserFeed\Services\ChannelService;
use UserFeed\Classes\Contracts\Services\ChannelService as ChannelServiceContract;
use UserFeed\Classes\Contracts\Services\MyFavoriteChannelsService as MyFavoriteChannelsServiceContract;
use UserFeed\Classes\Contracts\Services\ChannelPostService as ChannelPostServiceContract;
use UserFeed\Classes\Contracts\Services\Subscribe\SubscribeService as SubscribeServiceContract;
use UserFeed\Classes\Contracts\Services\Complaint\ComplaintService as ComplaintServiceContract;
use \UserFeed\Classes\Contracts\Services\Complaint\ComplaintPostComplaintService as ComplaintPostComplaintServiceContract;
use \UserFeed\Classes\Contracts\Services\Complaint\ComplaintTypeService as ComplaintTypeServiceContract;
use \UserFeed\Classes\Contracts\Services\Complaint\ComplaintRequestService as ComplaintRequestServiceContract;
use \UserFeed\Classes\Contracts\Services\Verify\VerifyService as VerifyServiceContract;
use UserFeed\Services\Complaint\ComplaintPostComplaintService;
use UserFeed\Services\Complaint\ComplaintRequestService;
use UserFeed\Services\Complaint\ComplaintService;
use UserFeed\Services\Complaint\ComplaintTypeService;
use UserFeed\Services\Like\LikeService;
use Illuminate\Support\Facades\Gate;
use UserFeed\Services\MyFavoriteChannelsService;
use UserFeed\Services\Subscribe\SubscribeService;
use UserFeed\Services\Verify\VerifyService;

class UserFeedServiceProvider extends BaseServiceProvider
{
    /**
     * Users should not unsubscribe from this user
     * @deprecated see config('app.user_nixon_id')
     */
    private const R_Nixon_Id = 1;

    /**
     * @var string $namespace
     */
    protected $namespace = 'UserFeed\Http\Controllers';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        $this->mapApiRoutes();

        $this->commands([RemoveUnusedTempUploadsCommand::class]);

        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        $this->defineGates();

        $this->registerObservers();

        $this->registerMiddlewares();
    }

    /**
     * @var array
     */
    protected $policies = [
        ChannelPost::class => ChannelPostPolicy::class,
        Channel::class => ChannelPolicy::class,
        Complaint::class => ComplaintPolicy::class,
        ComplaintType::class => ComplaintTypePolicy::class,
        ComplaintRequest::class => ComplaintRequestPolicy::class,
    ];


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ChannelServiceContract::class, ChannelService::class);
        $this->app->bind(ChannelPostServiceContract::class, ChannelPostService::class);
        $this->app->bind(ChannelPostLikeServiceContract::class, LikeService::class);
        $this->app->bind(SubscribeServiceContract::class, SubscribeService::class);
        $this->app->bind(ComplaintServiceContract::class, ComplaintService::class);
        $this->app->bind(VerifyServiceContract::class, VerifyService::class);
        $this->app->bind(ComplaintPostComplaintServiceContract::class, ComplaintPostComplaintService::class);
        $this->app->bind(ComplaintTypeServiceContract::class, ComplaintTypeService::class);
        $this->app->bind(ComplaintRequestServiceContract::class, ComplaintRequestService::class);
        $this->app->bind(MyFavoriteChannelsServiceContract::class, MyFavoriteChannelsService::class);
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('userfeed/routes/api.php'));
    }

    /**
     * @void
     */
    private function defineGates(): void
    {
        Gate::define('subscribe', function (User $obUser, int $iChannelId) {
            return $obUser->getKey() !== $iChannelId;
        });

        Gate::define('unsubscribe', function (User $obUser, int $iChannelId) {
            if ($iChannelId == static::R_Nixon_Id) {
                return false;
            }
            return $obUser->getKey() !== $iChannelId;
        });

        Gate::define('verify', function (User $obUser) {
            return $obUser->isAdmin;
        });

        Gate::define('unverify', function (User $obUser) {
            return $obUser->isAdmin;
        });

        Gate::define('getFavoriteChannels', function (User $obUser) {
            return true;
        });

    }

    /**
     * @void
     */
    private function registerObservers(): void
    {
        ChannelPost::observe(ChannelPostObserver::class);
        ComplaintType::observe(ComplaintTypeObserver::class);
    }

    /**
     * @void
     */
    private function registerMiddlewares(): void
    {
        app('router')->aliasMiddleware('useApiGuard', UseApiGuard::class);
    }

}
