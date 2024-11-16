<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterIntake extends BaseModel
{
    /** @use HasFactory<\Database\Factories\WaterIntakeFactory> */
    use HasFactory;

    public function daily_goal():BelongsTo
    {
        return $this->belongsTo(DailyGoals::class);
    }
}
