<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $finished_at
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ControlPointCapture[] $controlPointCaptures
 * @property-read int|null $control_point_captures_count
 * @property-read mixed $seconds_elapsed
 * @property-read string $status_string
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Game extends Model
{
    use HasFactory;

    public const STATUS_INIT = 'init';
    public const STATUS_PREPARING = 'preparing';
    public const STATUS_PLAYING = 'playing';
    public const STATUS_FINISH_COUNTDOWN = 'countdown';
    public const STATUS_FINISHED = 'finished';
    public const STATUS_FORCE_EXITED = 'force_ended';

    protected $dates
        = [
            'started_at',
            'finished_at',
        ];


    protected $appends
        = [
            'status_string',
            'seconds_elapsed',
        ];


    public function controlPointCaptures()
    {
        return $this->hasMany(ControlPointCapture::class);
    }


    public function getStatusStringAttribute(): string
    {
        if ($this->status == self::STATUS_INIT)
        {
            return 'čeká na spuštění';
        }
        if ($this->status == self::STATUS_PREPARING)
        {
            return 'odpočet do startu';
        }
        if ($this->status == self::STATUS_PLAYING)
        {
            return 'probíhá hra';
        }
        if ($this->status == self::STATUS_FINISH_COUNTDOWN)
        {
            return 'odpočet do konce';
        }
        if ($this->status == self::STATUS_FINISHED)
        {
            return 'ukončeno';
        }
        if ($this->status == self::STATUS_FORCE_EXITED)
        {
            return 'vynuceně ukončeno';
        }

        return 'neznámý';
    }


    public function getSecondsElapsedAttribute()
    {
        if ($this->started_at)
        {
            return $this->started_at->diffInSeconds(Carbon::now());
        }
    }


    public static function getCurrentGame()
    {
        return self::where('status', '!=', self::STATUS_FORCE_EXITED)
            ->where('status', '!=', self::STATUS_FINISHED)
            ->first();
    }
}
