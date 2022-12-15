<?php
declare(strict_types=1);
namespace UserFeed\Http\Requests\ChannelPost;

use App\Http\Requests\Request;
use Illuminate\Http\UploadedFile;

/**
 * Class PostImageUploadRequest
 * @package UserFeed\Http\Requests
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 * @property UploadedFile $image
 */
class PostImageUploadRequest extends Request
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
            'image' => 'mimes:jpeg,jpg,png|max:1000'
        ];
    }

}
