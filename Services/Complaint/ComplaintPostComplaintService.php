<?php
declare(strict_types=1);

namespace UserFeed\Services\Complaint;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use \UserFeed\Classes\Contracts\Services\Complaint\ComplaintPostComplaintService as ComplaintPostComplaintServiceContract;
use UserFeed\Models\ChannelPost;

/**
 * Class ComplaintService
 * @package UserFeed\Services\Complaint
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 * @deprecated not use this
 */
class ComplaintPostComplaintService implements ComplaintPostComplaintServiceContract
{

    /**
     * @inheritDoc
     */
    public function getPostComplaints(int $iChannelPostId, User $obUser): ?Collection
    {
        return ChannelPost::when($obUser->isAdmin, function (Builder $obQuery) {
            return $obQuery->withTrashed();
        })
            ->findOrFail($iChannelPostId)
            ->complaints()
            ->with(['user' => function ($obQuery) {
                $obQuery->select('id', 'name');
            }])
            ->with('type')
            ->get();
    }

}
