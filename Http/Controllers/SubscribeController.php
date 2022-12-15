<?php
declare(strict_types=1);

namespace UserFeed\Http\Controllers;

use App\Exceptions\AccessDeniedException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShortUserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use UserFeed\Classes\Contracts\Services\Subscribe\SubscribeService;
use \Illuminate\Http\JsonResponse;
use UserFeed\Classes\Exceptions\ChannelNotFoundException;
use UserFeed\Http\Resources\ChannelResource;
use UserFeed\Models\Channel;

/**
 * Class LikeController
 * @package UserFeed\Http\Controllers
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class SubscribeController extends Controller
{

    /**
     * @var SubscribeService $obSubscribeService
     */
    private SubscribeService $obSubscribeService;

    public function __construct(SubscribeService $obSubscribeService)
    {
        $this->obSubscribeService = $obSubscribeService;
    }

    /**
     * @param int $channelId
     * @return AnonymousResourceCollection
     */
    public function subscribe(int $channelId): AnonymousResourceCollection
    {
        if (Gate::denies('subscribe', $channelId)) {
            throw new AccessDeniedException;
        }

        $obUser = Auth::user();

        $obChannel = $this->obSubscribeService->subscribe(
            $obUser,
            $channelId
        );
        $obSubscribers = $obChannel->subscribers();

        return ShortUserResource::collection($obSubscribers->get())
            ->additional(['meta' => ['subscribers_count' => $obSubscribers->count()], 'status' => 'success']);

    }

    /**
     * @param int $channelId
     * @return AnonymousResourceCollection
     */
    public function unsubscribe(int $channelId): AnonymousResourceCollection
    {
        if (Gate::denies('unsubscribe', $channelId)) {
            throw new AccessDeniedException;
        }

        $obUser = Auth::user();

        $obChannel = $this->obSubscribeService->unsubscribe(
            $obUser,
            $channelId
        );

        $obSubscribers = $obChannel->subscribers();

        return ShortUserResource::collection($obSubscribers->get())
            ->additional(['meta' => ['subscribers_count' => $obSubscribers->count()], 'status' => 'success']);
    }

    /**
     * @param int $iChannelId
     * @return ChannelResource
     */
    public function mute(int $iChannelId): ChannelResource
    {
        try {

            $obUser = Auth::user();
            $obChannel = Channel::findOrFail($iChannelId);

            $obChannelMuted = $this->obSubscribeService->mute(
                $obUser,
                $obChannel
            );

            return new ChannelResource($obChannelMuted);

        } catch (ModelNotFoundException $e) {
            throw new ChannelNotFoundException();
        }

    }

    /**
     * @param int $iChannelId
     * @return ChannelResource
     */
    public function unMute(int $iChannelId): ChannelResource
    {
        try {

            $obUser = Auth::user();
            $obChannel = Channel::findOrFail($iChannelId);

            $obChannelMuted = $this->obSubscribeService->unMute(
                $obUser,
                $obChannel
            );

            return new ChannelResource($obChannelMuted);

        } catch (ModelNotFoundException $e) {
            throw new ChannelNotFoundException();
        }
    }

}
