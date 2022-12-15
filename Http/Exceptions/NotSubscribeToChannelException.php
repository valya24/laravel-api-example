<?php
declare(strict_types=1);

namespace UserFeed\Http\Exceptions;

use App\Exceptions\JsonException;
use Illuminate\Http\Response;

/**
 *
 */
class NotSubscribeToChannelException extends JsonException
{
    /**
     * @var int|null
     */
    protected ?int $statusCode = Response::HTTP_FORBIDDEN;

    /**
     * @var string
     */
    protected $message = 'You are not subscribed to this channel';
}
