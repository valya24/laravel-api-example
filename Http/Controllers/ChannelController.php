<?php
declare(strict_types=1);

namespace UserFeed\Http\Controllers;

use App\Exceptions\AccessDeniedException;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use UserFeed\Classes\Contracts\Services\ChannelService;
use UserFeed\Classes\Exceptions\ChannelNotFoundException;
use UserFeed\Http\Requests\ChannelActivateRequest;
use UserFeed\Http\Requests\FavoriteChannelsRequest;
use UserFeed\Http\Resources\ChannelFavoritesResource;
use UserFeed\Http\Resources\ChannelResource;
use UserFeed\Models\Channel;

/**
 * Class ChannelController
 * @package UserFeed\Controllers
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelController extends Controller
{
    /**
     * @var ChannelService
     */
    private ChannelService $obChannelService;

    public function __construct(ChannelService $obChannelService)
    {
        $this->obChannelService = $obChannelService;
    }

    /**
     * @param ChannelActivateRequest $obRequest
     * @return JsonResponse
     */
    public function update(ChannelActivateRequest $obRequest): JsonResponse
    {
        if (Gate::denies('update', [Channel::class, $obRequest->user_id])) {
            throw new AccessDeniedException;
        }

        $this->obChannelService->toggleActiveByUserId(
            (int)$obRequest->user_id,
            (bool)$obRequest->is_activate
        );

        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    /**
     * @param int $iChannelId
     * @return ChannelResource
     */
    public function show(int $iChannelId): ChannelResource
    {
        try {
            $obChannel = Channel::isEnabled()->findOrFail($iChannelId);
            if (Gate::denies('show', $obChannel)) {
                throw new AccessDeniedException;
            }

            return new ChannelResource($obChannel);

        } catch (ModelNotFoundException $e) {
            throw new ChannelNotFoundException;
        }
    }

}
