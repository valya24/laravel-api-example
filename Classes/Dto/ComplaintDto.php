<?php
declare(strict_types=1);

namespace UserFeed\Classes\Dto;

use App\Classes\Dto\DataTransferObject;
use Illuminate\Database\Eloquent\Collection;
use UserFeed\Models\Complaint;

/**
 *
 */
class ComplaintDto extends DataTransferObject
{
    /**
     * @var Complaint
     */
    public Complaint $complaint;

    /**
     * @var Collection
     */
    public Collection $types;
}
