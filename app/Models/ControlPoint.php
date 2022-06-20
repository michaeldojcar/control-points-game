<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
