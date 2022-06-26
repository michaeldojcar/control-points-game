<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * App\Models\ControlPointCapture
 *
 * @property int                             $id
 * @property int                             $user_id
 * @property int                             $team_id
 * @property int                             $game_id
 * @property int                             $control_point_id
 * @property \Illuminate\Support\Carbon      $date_from
 * @property \Illuminate\Support\Carbon|null $date_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Team|null      $team
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPointCapture newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPointCapture newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPointCapture query()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPointCapture whereControlPointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPointCapture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPointCapture whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPointCapture whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPointCapture whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPointCapture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPointCapture whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPointCapture whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlPointCapture whereUserId($value)
 * @mixin \Eloquent
 */
class ControlPointCapture extends Model
{
    protected $fillable = ['user_id', 'group_id', 'game_id', 'point'];
    protected $dates = ['date_from', 'date_to'];


    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }


    public function setEndOfCapture(): void
    {
        $this->date_to = Carbon::now();
        $this->seconds = $this->date_to->diffInSeconds($this->date_from);
        $this->save();
    }


    public function getLength()
    {
        if (isset($this->end_at))
        {
            return strtotime($this->end_at) - strtotime($this->created_at);
        }
        else
        {
            return strtotime('now') - strtotime($this->created_at);
        }
    }
}
