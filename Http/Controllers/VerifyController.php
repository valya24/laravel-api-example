<?php
declare(strict_types=1);

namespace UserFeed\Http\Controllers;

use App\Exceptions\AccessDeniedException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use UserFeed\Classes\Contracts\Services\Verify\VerifyService;
use \Illuminate\Http\JsonResponse;

/**
 * Class VerifyController
 * @package UserFeed\Http\Controllers
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class VerifyController extends Controller
{

    /**
     * @var VerifyService $obVerifyService
     */
    private VerifyService $obVerifyService;

    public function __construct(VerifyService $obVerifyService)
    {
        $this->obVerifyService = $obVerifyService;
    }

    /**
     * @param int $iUserId
     * @return JsonResponse
     */
    public function verify(int $iUserId): JsonResponse
    {
        if (Gate::denies('verify')) {
            throw new AccessDeniedException;
        }

        $this->obVerifyService->verifyUser(
            $iUserId,
        );

        return response()->json([
            'status' => 'success'
        ], Response::HTTP_OK);

    }

    /**
     * @param int $iUserId
     * @return JsonResponse
     */
    public function unverify(int $iUserId): JsonResponse
    {
        if (Gate::denies('unverify')) {
            throw new AccessDeniedException;
        }

        $this->obVerifyService->unVerifyUser(
            $iUserId,
        );

        return response()->json([
            'status' => 'success'
        ], Response::HTTP_OK);
    }

}
