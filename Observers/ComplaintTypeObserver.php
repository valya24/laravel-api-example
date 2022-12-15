<?php
declare(strict_types=1);

namespace UserFeed\Observers;

use UserFeed\Models\Complaint;
use UserFeed\Models\ComplaintType;

/**
 * Class ComplaintTypeObserver
 * @package UserFeed\Observers
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintTypeObserver
{
    /**
     *
     */
    private const OTHER_TYPE_ID = 5;

    /**
     * @param ComplaintType $obComplaintType
     * @void
     */
    public function deleted(ComplaintType $obComplaintType): void
    {
        Complaint::whereNull('type_id')->chunk(100, function ($obComplaintTypes) {
            $obComplaintTypes->each->update(['type_id' => static::OTHER_TYPE_ID]);
        });
    }

}
