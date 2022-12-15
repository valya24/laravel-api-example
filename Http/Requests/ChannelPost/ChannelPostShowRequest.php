<?php
declare(strict_types=1);
namespace UserFeed\Http\Requests\ChannelPost;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use UserFeed\Classes\Dto\ChannelPostShowDto;

/**
 * Class ChannelPostShowRequest
 * @package UserFeed\Http\Requests
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostShowRequest extends Request
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
            'channel_id' => 'required|integer|exists:UserFeed\Models\Channel,user_id',
            'post_id' => 'required|integer|exists:UserFeed\Models\ChannelPost,id'
        ];
    }

    /**
     * @void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(
           ['channel_id' => $this->route('channel')]
        );

        $this->merge(
            ['post_id' => $this->route('post')]
        );
    }

    /**
     * @return ChannelPostShowDto
     */
    public function getDto(): ChannelPostShowDto
    {
        return new ChannelPostShowDto([
            'channel_id' => (int) $this->channel_id,
            'post_id' => (int) $this->post_id,
            'obUser' => Auth::user()
        ]);
    }
}
