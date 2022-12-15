<?php
declare(strict_types=1);
namespace UserFeed\Enums;

use LeMaX10\Enums\Enum;

/**
 * Class Deal
 * @package UserFeed\Enums
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 *
 * @method static $this APPROVED()
 * @method static $this DONE()
 */
final class Deal extends Enum
{
    private const APPROVED = 'dispute-approved';
    private const DONE = 'done';

}
