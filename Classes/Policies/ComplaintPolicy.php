<?php
declare(strict_types=1);

namespace UserFeed\Classes\Policies;

use App\Models\User;

/**
 * Class ComplaintPolicy
 * @package UserFeed\Classes\Policies
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintPolicy
{
    /**
     * @param User $user
     * @param string $ability
     * @return bool|void
     */
    public function before(User $user, string $ability)
    {
        if ($user->isAdmin) {
            return true;
        }
    }

    /**
     * @param User $obUser
     * @return bool
     */
    public function index(User $obUser): bool
    {
        return $obUser->isAdmin;
    }

    /**
     * @param User $obUser
     * @return bool
     */
    public function getPostsHavingComplaints(User $obUser): bool
    {
        return $obUser->isAdmin;
    }

    /**
     * @param User $obUser
     * @return bool
     */
    public function update(User $obUser): bool
    {
        return $obUser->isAdmin;
    }

    /**
     * @param User $obUser
     * @param int $iUserId
     * @return bool
     */
    public function store(User $obUser, int $iUserId): bool
    {
        return $obUser->getKey() == $iUserId;
    }

    /**
     * @param User $obUser
     * @return bool
     */
    public function getComplaintTypes(User $obUser): bool
    {
        return true;
    }

    /**
     * @param User $obUser
     * @return bool
     */
    public function postComplaints(User $obUser): bool
    {
        return $obUser->isAdmin;
    }

}
