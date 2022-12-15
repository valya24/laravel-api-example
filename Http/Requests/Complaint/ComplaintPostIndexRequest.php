<?php
declare(strict_types=1);

namespace UserFeed\Http\Requests\Complaint;

use App\Http\Requests\Request;
use UserFeed\Enums\Complaint;
use UserFeed\Models\ComplaintType;

/**
 * Class ComplaintStoreRequest
 * @package UserFeed\Http\Requests\Complaint
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 */
class ComplaintPostIndexRequest extends Request
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
        $arStatuses = implode(',', Complaint::toArray());

        return [
            'offset' => 'required|integer',
            "filter" => "array",
            'filter.status' => ['string', Complaint::rule()]
        ];

    }

}
