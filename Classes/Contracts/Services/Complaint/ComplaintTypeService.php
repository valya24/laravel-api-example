<?php
declare(strict_types=1);

namespace UserFeed\Classes\Contracts\Services\Complaint;

use Illuminate\Database\Eloquent\Collection;


/**
 * Interface ComplaintTypeService
 * @package UserFeed\Classes\Contracts\Services\Complaint
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
interface ComplaintTypeService
{

    /**
     * @return Collection|null
     */
    public function getAllTypes(): ?Collection;

}
