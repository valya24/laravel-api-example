<?php
declare(strict_types=1);

namespace UserFeed\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use UserFeed\Classes\Helper\MediaHelper;
use UserFeed\Http\Requests\ChannelPost\PostImageUploadRequest;
use UserFeed\Models\TemporaryUpload;

class TemporaryUploadController extends Controller
{
    /**
     * @param PostImageUploadRequest $obRequest
     * @return JsonResponse
     */
    public function __invoke(PostImageUploadRequest $obRequest): JsonResponse
    {
        try {
            \DB::beginTransaction();
            $obTemImage = new TemporaryUpload([
                'path' => $obRequest->image->getBasename()
            ]);
            $obTemImage->user()->associate(\Auth::user());
            $obTemImage->save();

            $obUploadedImage = $obTemImage->addMedia($obRequest->image)
                ->toMediaCollection('editor');

            \DB::commit();
            return response()->json([
                'id'  => $obUploadedImage->uuid,
                'url' => MediaHelper::generateMediaUrl($obUploadedImage),
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            \DB::rollBack();
            return response()->json([
                'status' => 'failure'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
