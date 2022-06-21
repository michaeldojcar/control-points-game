<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Sound
 *
 * @property int $id
 * @property int $game_id
 * @property string $filename
 * @property int $played
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Sound newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sound newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sound query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sound wherePlayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sound extends Model
{
    use HasFactory;

    protected $table = 'sound_queue';
}
