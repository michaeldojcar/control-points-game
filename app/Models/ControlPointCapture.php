<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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
