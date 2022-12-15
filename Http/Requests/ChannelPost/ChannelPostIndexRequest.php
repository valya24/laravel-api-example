<?php
declare(strict_types=1);
namespace UserFeed\Http\Requests\ChannelPost;

use App\Http\Requests\Request;

/**
 * Class ChannelPostIndexRequest
 * @package UserFeed\Http\Requests
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostIndexRequest extends Request
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
            'offset' => 'required|integer'
        ];
    }

}
