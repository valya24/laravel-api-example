<?php
declare(strict_types=1);

namespace UserFeed\Classes\Policies;

use App\Models\User;
use UserFeed\Models\ChannelPost;

/**
 * Class ChannelPostPolicy
 * @package UserFeed\Classes\Policies
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostPolicy
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
     * @param User|null $obUser
     * @return bool
     */
    public function index(?User $obUser = null): bool
    {
        return true;
    }

    /**
     * @param User|null $obUser
     * @return bool
     */
    public function getFeeds(?User $obUser = null): bool
    {
        return true;
    }

    /**
     * @param User $obUser
     * @param int $iChannelId
     * @return bool
     */
    public function store(User $obUser, int $iChannelId): bool
    {
        return $obUser->getKey() == $iChannelId;
    }

    /**
     * @param User $obUser
     * @param int $iChannelId
     * @return bool
     */
    public function update(User $obUser, int $iChannelId): bool
    {
        return $obUser->getKey() == $iChannelId;
    }

    /**
     * @param User|null $obUser
     * @return bool
     */
    public function show(?User $obUser = null): bool
    {
        return true;
    }

    /**
     * @param User $obUser
     * @param $iChannelId
     * @return bool
     */
    public function destroy(User $obUser, $iChannelId): bool
    {
        return $iChannelId === $obUser->getKey();
    }

    /**
     * @param User $obUser
     * @return bool
     */
    public function deleteComplaintPost(User $obUser): bool
    {
        return $obUser->isAdmin;
    }

    /**
     * @param User $obUser
     * @return bool
     */
    public function updateComplaintPost(User $obUser): bool
    {
        return $obUser->isAdmin;
    }

    /**
     * @param User|null $obUser
     * @return bool
     */
    public function postByIdOrSlug(?User $obUser = null): bool
    {
        return true;
    }

}
