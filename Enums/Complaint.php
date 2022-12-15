<?php
declare(strict_types=1);
namespace UserFeed\Enums;

use LeMaX10\Enums\Enum;

/**
 * Class Complaint
 * @package UserFeed\Enums
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 *
 * @method static $this NEW()
 * @method static $this IN_PROGRESS()
 * @method static $this RESOLVED()
 */
final class Complaint extends Enum
{
    /**
     * New Requests
     */
    private const NEW = 'new';
    /**
     * In Progress Requests
     */
    private const IN_PROGRESS = 'in_progress';
    /**
     * Resolved Requests
     */
    private const RESOLVED = 'resolved';

    /**
     * @return array
     */
    public static function sortingPriority(): array
    {
        return [
            Complaint::NEW => 1,
            Complaint::IN_PROGRESS => 2,
            Complaint::RESOLVED => 3,
        ];
    }

    /**
     * @return int[]
     */
    public static function defaultTotal(): array
    {
        return [
            Complaint::NEW => 0,
            Complaint::IN_PROGRESS => 0,
            Complaint::RESOLVED => 0,
        ];
    }

    /**
     * @return array
     */
    public static function progressStatuses(): array
    {
        return [
            static::NEW(),
            static::IN_PROGRESS(),
        ];
    }
}
