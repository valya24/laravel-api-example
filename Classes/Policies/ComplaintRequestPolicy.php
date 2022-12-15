<?php
declare(strict_types=1);

namespace UserFeed\Classes\Policies;

use App\Models\User;

/**
 * Class ComplaintPolicy
 * @package UserFeed\Classes\Policies
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintRequestPolicy
{
    /**
     * @param User $user
     * @param string $ability
     * @return bool|void
     */
    public function before(User $user, string $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * @param User $obUser
     * @return bool
     */
    public function show(User $obUser): bool
    {
        return $obUser->is_admin;
    }

    /**
     * @param User $obUser
     * @return bool
     */
    public function update(User $obUser): bool
    {
        return $obUser->is_admin;
    }

    /**
     * @param User $obUser
     * @return bool
     */
    public function destroy(User $obUser): bool
    {
        return $obUser->is_admin;
    }
}
