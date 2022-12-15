<?php
declare(strict_types=1);

namespace UserFeed\Http\Controllers;

use App\Exceptions\AccessDeniedException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use UserFeed\Classes\Contracts\Services\Complaint\ComplaintPostComplaintService;
use UserFeed\Http\Resources\ChannelPostComplaintsResource;
use UserFeed\Http\Resources\ComplaintResource;
use UserFeed\Models\Complaint;
use UserFeed\Models\ComplaintRequest;

/**
 * Class ComplaintController
 * @package UserFeed\Http\Controllers
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintPostComplaintController extends Controller
{
    /**
     * @param int $iComplaintRequestId
     * @return AnonymousResourceCollection
     */
    public function index(int $iComplaintRequestId): AnonymousResourceCollection
    {
        /** @var ComplaintRequest $obComplaintRequest */
        $obComplaintRequest = ComplaintRequest::findOrFail($iComplaintRequestId);
        if (Gate::denies('show', $obComplaintRequest)) {
            throw new AccessDeniedException;
        }

        return ComplaintResource::collection($obComplaintRequest->complaints)
            ->additional(['status' => 'success']);

    }


}

