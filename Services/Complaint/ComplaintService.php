<?php
declare(strict_types=1);

namespace UserFeed\Services\Complaint;

use Illuminate\Support\Facades\DB;
use UserFeed\Classes\Contracts\Services\Complaint\ComplaintService as ComplaintServiceContract;
use UserFeed\Classes\Contracts\Services\Complaint\ComplaintRequestService as ComplaintRequestContract;
use UserFeed\Classes\Dto\ComplaintStoreDto;
use UserFeed\Models\ChannelPost;
use UserFeed\Models\Complaint;
use \UserFeed\Enums\Complaint as ComplaintEnum;

/**
 * Class ComplaintService
 * @package UserFeed\Services\Complaint
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintService implements ComplaintServiceContract
{
    /**
     * @var array
     */
    private array $arTotalsDefault = [
        'new' => 0,
        'in_progress' => 0,
        'resolved' => 0
    ];


    /**
     * @inheritDoc
     */
    public function getComplaintTotals(): ?array
    {
        $obChannelPostsTotal = ChannelPost::whereNotNull('complaint_status')
            ->select(
                'complaint_status',
                DB::raw('COUNT(complaint_status) as total')
            )
            ->groupBy('complaint_status')
            ->withTrashed()
            ->pluck('total', 'complaint_status')
            ->toArray();

        $arTotals = array_merge($obChannelPostsTotal, ['all' => array_sum($obChannelPostsTotal)]);

        return array_merge($this->arTotalsDefault, $arTotals);
    }

    /**
     * @inheritDoc
     */
    public function store(ComplaintStoreDto $obRequest): ?Complaint
    {
        /** @var ChannelPost $obChannelPost */
        $obChannelPost = ChannelPost::findOrFail($obRequest->channel_post_id);

        /** @var Complaint $obComplaint */
        $obComplaint = $obChannelPost->complaints()->create($obRequest->all());

        /** @var ComplaintRequestContract $obComplaintRequestService */
        $obComplaintRequestService = app(ComplaintRequestContract::class);
        $maxComplaintWithNew = $this->getChannelPostActualComplaintsCount($obChannelPost) >= (int) nova_get_setting('min_complaint');

        if (!$obChannelPost->currentComplaintRequest && $maxComplaintWithNew) {
            $obComplaintRequestService->create($obChannelPost);
        } else if ($obChannelPost->currentComplaintRequest) {
            $obComplaintRequestService->attachComplaint($obChannelPost->currentComplaintRequest, $obComplaint);
        }

        return $obComplaint;
    }


    /**
     * @param ChannelPost $obChannelPost
     * @return int
     */
    private function getChannelPostActualComplaintsCount(ChannelPost $obChannelPost): int
    {
        return $obChannelPost
            ->complaints
            ->whereIn('status', ComplaintEnum::progressStatuses())
            ->count();
    }
}
