<?php
declare(strict_types=1);
namespace UserFeed\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ChannelActivateRequest
 * @package UserFeed\Http\Requests
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ChannelActivateRequest extends Request
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
            'user_id' => 'required|integer|exists:App\Models\User,id',
            'is_activate' => 'required|boolean',
        ];
    }

    /**
     * @void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(
            ['user_id' => $this->route('channel')]
        );
    }

}
