<?php
declare(strict_types=1);

namespace UserFeed\Classes\Contracts\Services\Complaint;

use App\Classes\Dto\FilterDto;
use App\Classes\Dto\OffsetDto;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use UserFeed\Enums\Complaint;
use UserFeed\Models\ChannelPost;
use UserFeed\Models\Complaint as ComplaintModel;
use UserFeed\Models\ComplaintRequest;


/**
 * Interface ComplaintPostService
 * @package UserFeed\Classes\Contracts\Services\Complaint
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
interface ComplaintRequestService
{
    /**
     * limit items in chunk
     */
    public const LIMIT_CHUNK = 10;

    /**
     * @param ChannelPost $obChannelPost
     * @return ComplaintRequest
     */
    public function create(ChannelPost $obChannelPost): ComplaintRequest;

    /**
     * @param ComplaintRequest $obComplaintRequest
     * @param ComplaintModel $obComplaint
     * @return ComplaintRequest
     */
    public function attachComplaint(ComplaintRequest $obComplaintRequest, ComplaintModel $obComplaint): ComplaintRequest;

    /**
     * @param OffsetDto $obOffsetDto
     * @param FilterDto $obFilterDto
     * @return Collection|null
     */
    public function getPostsHavingComplaints(OffsetDto $obOffsetDto, FilterDto $obFilterDto): ?Collection;

    /**
     * @param User|null $obUser
     * @return array|null
     */
    public function getComplaintTotals(?User $obUser = null): ?array;

    /**
     * @param User $obUser
     * @param ComplaintRequest $obComplaintRequest
     * @param Complaint $obComplaintStatus
     * @return ComplaintRequest
     */
    public function updateComplaintPost(User $obUser, ComplaintRequest $obComplaintRequest, Complaint $obComplaintStatus): ComplaintRequest;

    /**
     * @param ComplaintRequest $obComplaintRequest
     * @param string $sDeleteReason
     * @return ComplaintRequest
     */
    public function deleteComplaintPost(ComplaintRequest $obComplaintRequest, string $sDeleteReason): ComplaintRequest;

    /**
     * @param User $obUser
     * @param ComplaintRequest $obComplaintRequest
     * @return bool
     */
    public function deleteComplaintPostByUser(User $obUser, ComplaintRequest $obComplaintRequest): bool;
}
