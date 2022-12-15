<?php
declare(strict_types=1);

namespace UserFeed\Http\Requests\ChannelPost;

use App\Http\Requests\Request;

/**
 * Class ChannelPostComplaintDeleteRequest
 * @package UserFeed\Http\Requests\ChannelPost
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostComplaintDeleteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'delete_reason' => 'required|string'
        ];
    }
}
