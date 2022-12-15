<?php
declare(strict_types=1);

namespace UserFeed\Http\Requests\ChannelPost;

use App\Http\Requests\Request;
use UserFeed\Enums\Complaint;

/**
 * Class ChannelPostComplaintUpdateRequest
 * @package UserFeed\Http\Requests\ChannelPost
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostComplaintUpdateRequest extends Request
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
            'status' => ['required', 'string', Complaint::rule()]
        ];
    }
}
