<?php
declare(strict_types=1);

namespace UserFeed\Services\Complaint;

use App\Classes\Dto\FilterDto;
use App\Classes\Dto\OffsetDto;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use UserFeed\Classes\Contracts\Services\ChannelPostService;
use \UserFeed\Classes\Contracts\Services\Complaint\ComplaintRequestService as ComplaintRequestServiceContract;
use UserFeed\Enums\Complaint;
use UserFeed\Models\ChannelPost;
use UserFeed\Models\Complaint as ComplaintModel;
use UserFeed\Enums\Complaint as ComplaintEnum;
use UserFeed\Models\ComplaintRequest;

/**
 * Class ComplaintService
 * @package UserFeed\Services\Complaint
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintRequestService implements ComplaintRequestServiceContract
{
    /**
     * @inheritDoc
     */
    public function create(ChannelPost $obChannelPost): ComplaintRequest
    {
        /** @var ComplaintRequest $complaintRequest */
        $complaintRequest = ComplaintRequest::create([
            'status' => ComplaintEnum::NEW(),
            'channel_post_id' => $obChannelPost->getKey(),
        ]);

        $complaintInProgress = $obChannelPost->complaints()
            ->whereIn('status', ComplaintEnum::progressStatuses())
            ->get(['id']);
        $complaintRequest->complaints()->sync($complaintInProgress);

        return $complaintRequest;
    }

    /**
     * @inheritDoc
     */
    public function attachComplaint(ComplaintRequest $obComplaintRequest, ComplaintModel $obComplaint): ComplaintRequest
    {
        $obComplaintRequest->complaints()->attach($obComplaint);

        return $obComplaintRequest;
    }

    /**
     * @inheritDoc
     */
    public function getPostsHavingComplaints(OffsetDto $obOffsetDto, FilterDto $obFilterDto): ?Collection
    {
        return ComplaintRequest::with('post')
            ->offset($obOffsetDto->offset)
            ->limit($obOffsetDto->limit ?? ComplaintRequestServiceContract::LIMIT_CHUNK)
            ->orderBy('created_at', 'desc')
            ->when(
                \Arr::get($obFilterDto->filter, 'status'),
                static function (Builder $obQuery, string $sFilterStatus) {
                    $obQuery->where('status', $sFilterStatus);
                }
            )
            ->when(
                \Arr::get($obFilterDto->filter, 'user_id'),
                static function (Builder $obQuery, int $iUserId) {
                    /** @var ComplaintRequest $obQuery */
                    $obQuery->getByUserID($iUserId);
                }
            )
            ->get();
    }


    /**
     * @inheritDoc
     */
    public function getComplaintTotals(?User $obUser = null): ?array
    {
        $obComplaintRequestsTotal = ComplaintRequest::select(
                'status',
                DB::raw('COUNT(status) as total'),
            )
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $obComplaintRequestsTotal['all'] = array_sum($obComplaintRequestsTotal);
        $obComplaintRequestsTotal['user'] = 0;
        if ($obUser) {
            $obComplaintRequestsTotal['user'] = ComplaintRequest::getByUserID($obUser->getKey())
                ->where('status', Complaint::IN_PROGRESS())
                ->count();
        }

        return array_merge(Complaint::defaultTotal(), $obComplaintRequestsTotal);
    }


    /**
     * @inheritDoc
     */
    public function updateComplaintPost(User $obUser, ComplaintRequest $obComplaintRequest, ComplaintEnum $obComplaintStatus): ComplaintRequest
    {
        return DB::transaction(function() use($obUser, $obComplaintRequest, $obComplaintStatus) {
           $obComplaintRequest->status = $obComplaintStatus;
           $obComplaintRequest->user()->associate($obUser);
           $obComplaintRequest->save();

           $obComplaintRequest->complaints()->update(['status' => $obComplaintStatus]);
           return $obComplaintRequest;
        });
    }

    /**
     * @inheritDoc
     */
    public function deleteComplaintPost(ComplaintRequest $obComplaintRequest, string $sDeleteReason): ComplaintRequest
    {
        return DB::transaction(function () use ($obComplaintRequest, $sDeleteReason) {
            $obComplaintRequest->update([
                'status' => ComplaintEnum::RESOLVED(),
                'delete_reason' => $sDeleteReason
            ]);
            $obComplaintRequest->complaints()
                ->update([
                    'status' => ComplaintEnum::RESOLVED()
                ]);

            if ($obComplaintRequest->post) {
                /** @var ChannelPostService $channelPostService */
                $channelPostService = app(ChannelPostService::class);
                $channelPostService->destroy($obComplaintRequest->post);
            }

            return $obComplaintRequest;
        });
    }

    /**
     * @inheritDoc
     */
    public function deleteComplaintPostByUser(User $obUser, ComplaintRequest $obComplaintRequest): bool
    {
        try {
            $bIsAuthor = $obComplaintRequest->post->user->getKey() === $obUser->getKey();
            if (!$bIsAuthor && !$obUser->is_admin) {
                return false;
            }

            $bIsAuthorAndInProgress = $bIsAuthor && $obComplaintRequest->status->equals(ComplaintEnum::IN_PROGRESS());
            if ($bIsAuthorAndInProgress || $obUser->is_admin) {
                $sReason = $bIsAuthor ? 'Удалено автором' : 'Удалено администратором';
                $this->deleteComplaintPost($obComplaintRequest, $sReason);
                return true;
            }

            return (booL) $obComplaintRequest->delete();
        } catch(\Exception $e) {
            return false;
        }
    }

}
