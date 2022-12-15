<?php
declare(strict_types=1);

namespace UserFeed\Http\Controllers;

use App\Http\Controllers\Controller;
use UserFeed\Classes\Contracts\Services\Like\ChannelPostLikeService;
use UserFeed\Http\Requests\Like\ChannelPostLikeRequest;
use UserFeed\Http\Resources\LikeResource;

/**
 * Class LikeController
 * @package UserFeed\Http\Controllers
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class LikeController extends Controller
{

    /**
     * @var ChannelPostLikeService $obChannelPostLikeService
     */
    private ChannelPostLikeService $obChannelPostLikeService;

    public function __construct(ChannelPostLikeService $obChannelPostLikeService)
    {
        $this->obChannelPostLikeService = $obChannelPostLikeService;
    }

    /**
     * @param ChannelPostLikeRequest $obRequest
     * @return LikeResource
     */
    public function like(ChannelPostLikeRequest $obRequest): LikeResource
    {
        $obChannelPost = $this->obChannelPostLikeService->like(
            (int)$obRequest->post_id
        );

        return new LikeResource($obChannelPost);
    }

    /**
     * @param ChannelPostLikeRequest $obRequest
     * @return LikeResource
     */
    public function dislike(ChannelPostLikeRequest $obRequest): LikeResource
    {
        $obChannelPost = $this->obChannelPostLikeService->dislike(
            (int)$obRequest->post_id
        );

        return new LikeResource($obChannelPost);
    }

}
