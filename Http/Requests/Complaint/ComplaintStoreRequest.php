<?php
declare(strict_types=1);

namespace UserFeed\Http\Requests\Complaint;

use App\Http\Requests\Request;
use UserFeed\Models\ComplaintType;

/**
 * Class ComplaintStoreRequest
 * @package UserFeed\Http\Requests\Complaint
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintStoreRequest extends Request
{
    private const OTHER_ID = 5;

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
            'type_id' => 'required|string|max:20|exists:UserFeed\Models\ComplaintType,id',
            'description' => 'required_if:type_id,=,' . static::OTHER_ID . '|max:120',
            'user_id' => 'required|integer|exists:App\Models\User,id',
            'channel_post_id' => 'required|integer|exists:UserFeed\Models\ChannelPost,id',
        ];
    }

}
