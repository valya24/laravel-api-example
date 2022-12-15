<?php
declare(strict_types=1);

namespace UserFeed\Http\Requests\ChannelPost;

use App\Http\Requests\Request;

/**
 * Class ChannelPostUpdateRequest
 * @package UserFeed\Http\Requests
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelPostUpdateRequest extends Request
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
            'title' => 'sometimes|string|min:3|max:255',
            'short_description' => 'sometimes|string|min:3|max:255',
            'description' => 'sometimes|string|min:3|',
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
    }

}
