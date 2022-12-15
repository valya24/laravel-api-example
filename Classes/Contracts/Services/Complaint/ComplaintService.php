<?php
declare(strict_types=1);

namespace UserFeed\Classes\Contracts\Services\Complaint;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use UserFeed\Classes\Dto\ComplaintStoreDto;
use UserFeed\Models\Complaint;

/**
 * Interface ComplaintService
 * @package UserFeed\Classes\Contracts\Services\Complaint
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
interface ComplaintService
{

    /**
     * @param ComplaintStoreDto $obRequest
     * @return Complaint|null
     */
    public function store(ComplaintStoreDto $obRequest): ?Complaint;

    /**
     * @return array|null
     */
    public function getComplaintTotals(): ?array;

}
