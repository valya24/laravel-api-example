<?php
declare(strict_types=1);

namespace UserFeed\Http\Controllers;

use App\Classes\Dto\FilterDto;
use App\Classes\Dto\OffsetDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\OffsetRequest;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use UserFeed\Classes\Contracts\Services\ChannelService;
use UserFeed\Http\Resources\ChannelResource;

/**
 * Class MyFavoriteChannelsController
 * @package UserFeed\Controllers
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class MyFavoriteChannelsController extends Controller
{
    /**
     * @var ChannelService $obChannelsService
     */
    private ChannelService $obChannelsService;

    /**
     * @param ChannelService $obChannelsService
     */
    public function __construct(ChannelService $obChannelsService)
    {
        $this->obChannelsService = $obChannelsService;
    }

    /**
     * @param OffsetRequest $obOffsetRequest
     * @param FilterRequest $obFilterRequest
     * @return JsonResource
     */
    public function index(OffsetRequest $obOffsetRequest, FilterRequest $obFilterRequest): JsonResource
    {
        /** @var User $obUser */
        $obUser = \Auth::user();
        $obOffsetDto = $obOffsetRequest->getDto();

        $obChannels = $this->obChannelsService->userFavorite(
            $obUser,
            $obOffsetDto,
            $obFilterRequest->getDto()
        );

        return ChannelResource::collection($obChannels->items)
            ->additional([
                'status' => 'success',
                'meta' => [
                    'paginate' => $obChannels->meta($obOffsetDto->offset, $obOffsetDto->limit ?? ChannelService::LIMIT_CHUNK),
                ]
            ]);
    }

}
