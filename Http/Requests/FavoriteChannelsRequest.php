<?php
declare(strict_types=1);

namespace UserFeed\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class FavoriteChannelsRequest
 * @package UserFeed\Http\Requests
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class FavoriteChannelsRequest extends Request
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
            'offset' => 'sometimes|numeric',
            'search' => 'sometimes|string',
        ];
    }

}
