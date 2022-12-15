<?php
declare(strict_types=1);

namespace UserFeed\Http\Requests\Like;

use App\Http\Requests\Request;

/**
 * Class ChannelPostLikeRequest
 * @package UserFeed\Http\Requests\Like
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostLikeRequest extends Request
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
            'post_id' => 'required|integer|exists:UserFeed\Models\ChannelPost,id'
        ];
    }


}
