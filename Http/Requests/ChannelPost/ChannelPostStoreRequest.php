<?php
declare(strict_types=1);

namespace UserFeed\Http\Requests\ChannelPost;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;
use voku\helper\AntiXSS;

/**
 * Class ChannelPostStoreRequest
 * @package UserFeed\Http\Requests\ChannelPost
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostStoreRequest extends Request
{
    /**
     * @var array $arXssClean
     */
    protected array $arXssClean = [
        'title',
        'short_description',
        'description',
        'seo.meta_title',
        'seo.meta_description',
        'seo.meta_keywords',
    ];

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
            'title' => 'required|string|min:3|max:255',
            'short_description' => 'required|string|max:255',
            'description' => 'required|string',
            'seo' => 'sometimes|array',
            'seo.meta_title' => 'string',
            'seo.meta_description' => 'string',
            'seo.meta_keywords' => 'string',
        ];
    }

    /**
     * @void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(
            $this->sanitize($this->only($this->arXssClean))
        );

        $this->merge(['channel_id' => $this->route('channel')]);
    }

}
