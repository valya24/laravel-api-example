<?php
declare(strict_types=1);

namespace UserFeed\Http\Controllers;

use App\Exceptions\AccessDeniedException;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use UserFeed\Classes\Contracts\Services\ChannelPostService;
use UserFeed\Classes\Dto\ChannelPostShowDto;
use UserFeed\Classes\Dto\ChannelPostsShowDto;
use UserFeed\Classes\Dto\ChannelPostStoreDto;
use UserFeed\Classes\Dto\ChannelPostUpdateDto;
use UserFeed\Http\Requests\ChannelPost\ChannelPostGetFeedsRequest;
use UserFeed\Http\Requests\ChannelPost\ChannelPostStoreRequest;
use Illuminate\Support\Facades\Gate;
use UserFeed\Http\Requests\ChannelPost\ChannelPostUpdateRequest;
use UserFeed\Http\Resources\ChannelPostResource;
use UserFeed\Models\Channel;
use UserFeed\Models\ChannelPost;
use Illuminate\Http\JsonResponse;
use UserFeed\Classes\Exceptions\PostNotFoundException;
use UserFeed\Http\Requests\ChannelPost\ChannelPostShowRequest;
use UserFeed\Http\Requests\ChannelPost\ChannelPostIndexRequest;

/**
 * Class ChannelPostController
 * @package UserFeed\Http\Controllers
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostController extends Controller
{
    /**
     * @var ChannelPostService $obChannelPostService
     */
    private ChannelPostService $obChannelPostService;

    public function __construct(ChannelPostService $obChannelPostService)
    {
        $this->obChannelPostService = $obChannelPostService;
    }

    /**
     * @param ChannelPostIndexRequest $obRequest
     * @param int $iChannelId
     * @return AnonymousResourceCollection
     */
    public function index(ChannelPostIndexRequest $obRequest, int $iChannelId): AnonymousResourceCollection
    {
        if (Gate::denies('index', ChannelPost::class)) {
            throw new AccessDeniedException;
        }

        $obChannelPosts = $this->obChannelPostService->getChannelPosts(
            new ChannelPostsShowDto([
                'channel_id' => $iChannelId,
                'offset' => (int)$obRequest->offset,
            ])
        );

        return ChannelPostResource::collection($obChannelPosts)->additional(['status' => 'success']);

    }

    /**
     * @param ChannelPostGetFeedsRequest $obRequest
     * @return AnonymousResourceCollection
     */
    public function getFeeds(ChannelPostGetFeedsRequest $obRequest): AnonymousResourceCollection
    {
        if (Gate::denies('getFeeds', ChannelPost::class)) {
            throw new AccessDeniedException;
        }

        $obUser = Auth::user();
        $obChannelPosts = $this->obChannelPostService->getFeeds(
            (int)$obRequest->offset,
            $obUser
        );

        return ChannelPostResource::collection($obChannelPosts)->additional(['status' => 'success']);

    }


    /**
     * @param int $channelId
     * @param ChannelPostStoreRequest $obRequest
     * @return ChannelPostResource
     */
    public function store(int $channelId, ChannelPostStoreRequest $obRequest): ChannelPostResource
    {
        $obRequestData = ChannelPostStoreDto::fromRequest($obRequest);

        if (Gate::denies('store', [ChannelPost::class, $channelId])) {
            throw new AccessDeniedException;
        }

        $obUser = Auth::user();
        $obChannelPost = $this->obChannelPostService->store($obUser, $obRequestData);

        return new ChannelPostResource($obChannelPost);
    }

    /**
     * @param ChannelPostShowRequest $obRequest
     * @return ChannelPostResource
     */
    public function show(ChannelPostShowRequest $obRequest): ChannelPostResource
    {
        try {
            if (Gate::denies('show', ChannelPost::class)) {
                throw new AccessDeniedException;
            }

            $obChannelPost = $this->obChannelPostService->show($obRequest->getDto());
            $obChannelPost->addView();

            return new ChannelPostResource($obChannelPost);
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException();
        }

    }

    /**
     * @param int $iChannelId
     * @param int $iChannelPostId
     * @param ChannelPostUpdateRequest $obRequest
     * @return ChannelPostResource
     */
    public function update(int $iChannelId, int $iChannelPostId, ChannelPostUpdateRequest $obRequest): ChannelPostResource
    {
        $obRequestData = ChannelPostUpdateDto::fromRequest($obRequest);

        if (Gate::denies('update', [ChannelPost::class, $iChannelId])) {
            throw new AccessDeniedException;
        }

        $obUser = Auth::user();

        $obChannelPost = $this->obChannelPostService->update($obUser, $obRequestData, $iChannelPostId);

        return new ChannelPostResource($obChannelPost);

    }

    /**
     * @param int $iChannelId
     * @param int $iChannelPostId
     * @return JsonResponse
     */
    public function destroy(int $iChannelId, int $iChannelPostId): JsonResponse
    {
        $obChannel = Channel::isEnabled()->findOrFail($iChannelId);
        if (Gate::denies('update', $obChannel->getKey())) {
            throw new AccessDeniedException;
        }

        /** @var ChannelPost $obChannelPost */
        $obChannelPost = $obChannel->channelPosts()->findOrFail($iChannelPostId);
        if (Gate::denies('destroy', $obChannelPost)) {
            throw new AccessDeniedException;
        }

        $this->obChannelPostService->destroy($obChannelPost, Auth::user());
        return response()->json([
            'status' => 'success'
        ], Response::HTTP_OK);
    }

    /**
     * @param string $sIdOrSlug
     * @return ChannelPostResource
     */
    public function postByIdOrSlug(string $sIdOrSlug): ChannelPostResource
    {
        try {

            if (Gate::denies('postByIdOrSlug', ChannelPost::class)) {
                throw new AccessDeniedException;
            }

            $obUser = Auth::user();
            $obChannelPost = $this->obChannelPostService->getByIdOrSlug(
                $sIdOrSlug,
                $obUser
            );

            return new ChannelPostResource($obChannelPost);
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException();
        }

    }

}
