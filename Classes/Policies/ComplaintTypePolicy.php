<?php
declare(strict_types=1);

namespace UserFeed\Classes\Policies;

use App\Models\BackendUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ComplaintTypePolicy
 * @package UserFeed\Classes\Policies
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintTypePolicy
{
    /**
     * @param User $obUser
     * @return bool
     */
    public function index(User $obUser): bool
    {
        return true;
    }

    /**
     * @param BackendUser $obUser
     * @return bool
     */
    public function create(BackendUser $obUser): bool
    {
        return true;
    }

    /**
     * @param BackendUser $obUser
     * @return bool
     */
    public function update(BackendUser $obUser): bool
    {
        return true;
    }

    /**
     * @param BackendUser $obUser
     * @return bool
     */
    public function delete(BackendUser $obUser): bool
    {
        return true;
    }

    /**
     * @param BackendUser $obUser
     * @return bool
     */
    public function view(BackendUser $obUser): bool
    {
        return true;
    }


}
