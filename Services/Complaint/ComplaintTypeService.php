<?php
declare(strict_types=1);

namespace UserFeed\Services\Complaint;

use Illuminate\Database\Eloquent\Collection;
use \UserFeed\Classes\Contracts\Services\Complaint\ComplaintTypeService as ComplaintTypeServiceContract;
use UserFeed\Models\ComplaintType;

/**
 * Class ComplaintTypeService
 * @package UserFeed\Services\Complaint
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintTypeService implements ComplaintTypeServiceContract
{

    /**
     * @inheritDoc
     */
    public function getAllTypes(): ?Collection
    {
         return ComplaintType::get();
    }

}
