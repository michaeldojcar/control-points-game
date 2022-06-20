<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            return 'ukončena';
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
}
