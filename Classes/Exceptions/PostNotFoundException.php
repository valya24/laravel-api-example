<?php
declare(strict_types=1);

namespace UserFeed\Classes\Exceptions;

use App\Exceptions\JsonException;
use Illuminate\Http\Response;

/**
 *
 */
class PostNotFoundException extends JsonException
{
    /**
     * @var int|null
     */
    protected ?int $statusCode = Response::HTTP_NOT_FOUND;

    /**
     * @var string
     */
    protected $message = 'This post not found';
}
