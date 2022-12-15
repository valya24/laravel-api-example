<?php
declare(strict_types=1);

namespace UserFeed\Http\Requests\Complaint;

use App\Http\Requests\Request;
use UserFeed\Enums\Complaint;

/**
 * Class ComplaintGetAllRequest
 * @package UserFeed\Http\Requests\Complaint
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintGetAllRequest extends Request
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
            "filter" => "array",
            'filter.status' => ['string', Complaint::rule()]
        ];
    }

}
