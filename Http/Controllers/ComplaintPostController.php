<?php
declare(strict_types=1);

namespace UserFeed\Http\Controllers;

use App\Classes\Dto\OffsetPaginationDTO;
use App\Exceptions\AccessDeniedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\OffsetRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use UserFeed\Classes\Contracts\Services\Complaint\ComplaintRequestService;
use UserFeed\Http\Requests\ChannelPost\ChannelPostComplaintDeleteRequest;
use UserFeed\Http\Requests\ChannelPost\ChannelPostComplaintUpdateRequest;
use UserFeed\Http\Resources\ComplaintChannelPostResource;
use UserFeed\Models\ChannelPost;
use UserFeed\Models\Complaint;
use UserFeed\Enums\Complaint as ComplaintEnum;
use UserFeed\Models\ComplaintRequest;

/**
 * Class ComplaintPostController
 * @package UserFeed\Http\Controllers
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintPostController extends Controller
{

    /**
     * @var ComplaintRequestService $obComplaintPostService
     */
    private ComplaintRequestService $obComplaintPostService;

    /**
     * @param ComplaintRequestService $obComplaintPostService
     */
    public function __construct(ComplaintRequestService $obComplaintPostService)
    {
        $this->obComplaintPostService = $obComplaintPostService;
    }

    /**
     * @param OffsetRequest $obOffsetRequest
     * @param FilterRequest $obFilterRequest
     * @return AnonymousResourceCollection
     */
    public function index(OffsetRequest $obOffsetRequest, FilterRequest $obFilterRequest): AnonymousResourceCollection
    {
        if (Gate::denies('getPostsHavingComplaints', Complaint::class)) {
            throw new AccessDeniedException;
        }

        $obOffsetDto = $obOffsetRequest->getDto();
        $obComplaints = $this->obComplaintPostService->getPostsHavingComplaints(
            $obOffsetDto,
            $obFilterRequest->getDto()
        );

        $arComplaintsTotals = $this->obComplaintPostService->getComplaintTotals(\Auth::user());
        $sFilter_Status = $obFilterRequest->get('filter.status', 'all');
        $sFilter_UserId = $obFilterRequest->get('filter.user_id');

        $offsetChannelDto = new OffsetPaginationDTO([
            'items' => $obComplaints,
            'total' => (int) \Arr::get($arComplaintsTotals, $sFilter_Status && $sFilter_UserId ? 'user' : $sFilter_Status)
        ]);

        return ComplaintChannelPostResource::collection($offsetChannelDto->items)->additional(
            [
                'meta' => [
                    'totals' => $arComplaintsTotals,
                    'paginate' => $offsetChannelDto->meta($obOffsetDto->offset, $obOffsetDto->limit ?? ComplaintRequestService::LIMIT_CHUNK),
                ]
            ]
        );
    }

    /**
     * @param int $iComplaintRequestId
     */
    public function show(int $iComplaintRequestId)
    {
        $obComplaintRequest = ComplaintRequest::findOrFail($iComplaintRequestId);

        if (Gate::denies('show', $obComplaintRequest)) {
            throw new AccessDeniedException;
        }

        return new ComplaintChannelPostResource($obComplaintRequest);
    }

    /**
     * @param int $iComplaintRequestId
     * @param ChannelPostComplaintUpdateRequest $obRequest
     * @return JsonResponse
     */
    public function update(int $iComplaintRequestId, ChannelPostComplaintUpdateRequest $obRequest): JsonResponse
    {
        $obComplaintRequest = ComplaintRequest::findOrFail($iComplaintRequestId);
        if (Gate::denies('update', $obComplaintRequest)) {
            throw new AccessDeniedException;
        }

        $this->obComplaintPostService->updateComplaintPost(
            \Auth::user(),
            $obComplaintRequest,
            new ComplaintEnum($obRequest->status)
        );

        return response()->json([
            'status' => 'success'
        ], Response::HTTP_OK);

    }

    /**
     * @param int $iComplaintRequestId
     * @param ChannelPostComplaintDeleteRequest $obRequest
     * @return JsonResponse
     */
    public function destroy(int $iComplaintRequestId, ChannelPostComplaintDeleteRequest $obRequest): JsonResponse
    {
        $obComplaintRequest = ComplaintRequest::findOrFail($iComplaintRequestId);
        if (Gate::denies('destroy', $obComplaintRequest)) {
            throw new AccessDeniedException;
        }

        $this->obComplaintPostService->deleteComplaintPost($obComplaintRequest, $obRequest->delete_reason);

        return response()->json([
            'status' => 'success'
        ], Response::HTTP_OK);
    }

}

