<?php
declare(strict_types=1);

namespace UserFeed\Http\Controllers;

use App\Exceptions\AccessDeniedException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use UserFeed\Classes\Contracts\Services\Complaint\ComplaintTypeService;
use UserFeed\Http\Resources\ComplaintTypeResource;
use UserFeed\Models\ComplaintType;

/**
 * Class ComplaintTypeController
 * @package UserFeed\Http\Controllers
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintTypeController extends Controller
{

    /**
     * @var ComplaintTypeService $obComplaintTypeService
     */
    private ComplaintTypeService $obComplaintTypeService;

    public function __construct(ComplaintTypeService $obComplaintTypeService)
    {
        $this->obComplaintTypeService = $obComplaintTypeService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        if (Gate::denies('index', ComplaintType::class)) {
            throw new AccessDeniedException;
        }

        return ComplaintTypeResource::collection($this->obComplaintTypeService->getAllTypes())
            ->additional(['status' => 'success']);

    }

}

