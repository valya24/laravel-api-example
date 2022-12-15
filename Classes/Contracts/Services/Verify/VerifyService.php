<?php
declare(strict_types=1);

namespace UserFeed\Classes\Contracts\Services\Verify;

use App\Models\User;

/**
 * Interface VerifyService
 * @package UserFeed\Classes\Contracts\Services\Verify
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
interface VerifyService
{

    /**
     * @param int $iUserId
     * @return User
     */
    public function verifyUser(int $iUserId): User;

    /**
     * @param int $iUserId
     * @return User
     */
    public function unVerifyUser(int $iUserId): User;

}
