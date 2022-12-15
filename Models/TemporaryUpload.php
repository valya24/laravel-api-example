<?php
declare(strict_types=1);

namespace UserFeed\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class TemporaryUpload
 * @package UserFeed\Models
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 *
 * @property string $path
 * @property int $user_id
 * @property-read User $user
 */
class TemporaryUpload extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * @var string
     */
    public $table = 'temporary_uploads';
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string[]
     */
    protected $fillable = ['path'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
