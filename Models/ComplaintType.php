<?php
declare(strict_types=1);

namespace UserFeed\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * Class ComplaintType
 * @package UserFeed\Models
 * @author Grigor Grigoryan, g.grigoryan@.......com, ....... Group
 *
 * @property string $name
 * @property boolean $has_free
 * @property Complaint $complaint
 */
class ComplaintType extends Model
{
    /**
     * @var string
     */
    public $table = 'complaint_types';

    /**
     * @var string[]
     */
    public $fillable = ['type', 'name', 'has_free'];

    /**
     * @var bool
     */
    public $timestamps = false;

    private const OTHER_TYPE_ID = 5;

    /**
     * @var string[]
     */
    protected $casts = [
        'has_free' => 'bool'
    ];

    /**
     * @return HasMany
     */
    public function complaint(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * @return bool|void|null
     * @throws \Exception
     */
    public function delete()
    {
        if ($this->id === static::OTHER_TYPE_ID) {
            throw new \Exception('You can not delete this type');
        }

        parent::delete();
    }

}
