<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyGoals extends BaseModel
{
    /** @use HasFactory<\Database\Factories\DailyGoalsFactory> */
    use HasFactory;
    protected $with = ["water_intakes"];

    public function water_intakes(): HasMany
    {
        return $this->hasMany(WaterIntake::class)->orderBy("created_at","desc");
    }


    public function getTotalWaterIntakeAttribute(): int
    {
        return $this->water_intakes()->sum('amount');
    }

    public function getRemainingWaterAttribute(): int
    {
        return max(0, $this->goal_amount - $this->total_water_intake);
    }

    protected $appends = ['total_water_intake', 'remaining_water'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
