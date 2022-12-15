<?php
declare(strict_types=1);

namespace UserFeed\Classes\Contracts\Services\Complaint;

use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Interface ComplaintPostComplaintService
 * @package UserFeed\Classes\Contracts\Services\Complaint
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 * @deprecated not use this
 */
interface ComplaintPostComplaintService
{
    /**
     * @param int $iChannelPostId
     * @param User $obUser
     * @return Collection|null
     */
     public function getPostComplaints(int $iChannelPostId, User $obUser): ?Collection;

}
