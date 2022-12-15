<?php
declare(strict_types=1);

namespace UserFeed\Http\Controllers;

use App\Exceptions\AccessDeniedException;
use App\Http\Controllers\Controller;
use UserFeed\Classes\Contracts\Services\Complaint\ComplaintService;
use UserFeed\Classes\Dto\ComplaintStoreDto;
use UserFeed\Http\Requests\Complaint\ComplaintStoreRequest;
use UserFeed\Http\Resources\ComplaintResource;
use Illuminate\Support\Facades\Gate;
use UserFeed\Models\Complaint;

/**
 * Class ComplaintController
 * @package UserFeed\Http\Controllers
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintController extends Controller
{

    /**
     * @var ComplaintService $obComplaintService
     */
    private ComplaintService $obComplaintService;

    public function __construct(ComplaintService $obComplaintService)
    {
        $this->obComplaintService = $obComplaintService;
    }

    /**
     * @param ComplaintStoreRequest $obRequest
     * @return ComplaintResource
     */
    public function store(ComplaintStoreRequest $obRequest): ComplaintResource
    {
        $obRequestData = new ComplaintStoreDto($obRequest->validated());

        if (Gate::denies('store', [Complaint::class, $obRequest->user_id])) {
            throw new AccessDeniedException;
        }

        $obComplaint = $this->obComplaintService->store($obRequestData);

        return new ComplaintResource($obComplaint);
    }
}

