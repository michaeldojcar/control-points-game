<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\ControlPoint
 *
 * @property int $id
 * @property string $name
 * @property string|null $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ControlPointCapture[] $controlPointCaptures
 * @property-read int|null $control_point_captures_count
 * @property-read \App\Models\ControlPointCapture|null $last_capture
 * @property-read mixed $owner_name
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPoint query()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPoint whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPoint whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPoint whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ControlPoint extends Model
{
    use HasFactory;

    public function controlPointCaptures(): HasMany
    {
        return $this->hasMany(ControlPointCapture::class);
    }


    /**
     * @return ControlPointCapture|null
     */
    public function getLastCaptureAttribute()
    {
        return $this->controlPointCaptures()
            ->where('game_id', Game::getCurrentGame()->id)
            ->latest()->first();
    }


    public function getOwnerNameAttribute()
    {

        if ($this->getLastCaptureAttribute())
        {
            return $this->getLastCaptureAttribute()->team->name;
        }

        return null;
    }
}
