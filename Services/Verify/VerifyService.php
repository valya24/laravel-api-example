<?php
declare(strict_types=1);

namespace UserFeed\Services\Verify;

use App\Models\User;
use \UserFeed\Classes\Contracts\Services\Verify\VerifyService as VerifyServiceContract;
use UserFeed\Models\Channel;


/**
 * Class SubscribeService
 * @package UserFeed\Services\Subscribe
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class VerifyService implements VerifyServiceContract
{

    /**
     * @inheritDoc
     */
    public function verifyUser(int $iUserId): User
    {
        $obUser = User::findOrFail($iUserId);
        $obUser->is_verified = true;
        $obUser->save();

        return $obUser;
    }


    /**
     * @inheritDoc
     */
    public function unVerifyUser(int $iUserId): User
    {
        $obUser = User::findOrFail($iUserId);
        $obUser->is_verified = false;
        $obUser->save();

        return $obUser;
    }

}
