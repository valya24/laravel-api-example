<?php
declare(strict_types=1);

namespace UserFeed\Http\Requests\ChannelPost;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use voku\helper\AntiXSS;

/**
 * Class ChannelPostGetFeedsRequest
 * @package UserFeed\Http\Requests\ChannelPost
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostGetFeedsRequest extends Request
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
            'offset' => 'required|integer',
        ];
    }

}
