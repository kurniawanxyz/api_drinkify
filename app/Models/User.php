<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $with = ["daily_goals", "reminders", "goalsToday"];


    public function daily_goals(): HasMany
    {
        return $this->hasMany(DailyGoals::class)->orderBy("created_at","desc");
    }

    public function goalsToday()
    {
        return $this->hasOne(DailyGoals::class)->whereDate("created_at", now());
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function getGoalsSuccessAttribute(): int
    {
        return $this->daily_goals->filter(function ($goal) {
            return $goal->remaining_water === 0;
        })->count();
    }

    public function getGoalsFailedAttribute(): int
    {
        return $this->daily_goals->filter(function ($goal) {
            return $goal->remaining_water != 0;
        })->count();
    }

    public function getAverageWaterIntakesAttribute(): string
    {
        $totalWaterIntake = $this->daily_goals->sum(function ($goal) {
            return $goal->total_water_intake;
        });

        $daysCount = $this->daily_goals->count();

        $average = $daysCount > 0 ? $totalWaterIntake / $daysCount : 0;

        // Format nilai rata-rata menjadi float dengan dua desimal
        return number_format($average, 2, '.', '');
    }



    // public function getRemainingWaterAttribute(): int
    // {
    //     return max(0, $this->goal_amount - $this->total_water_intake);
    // }

    protected $appends = ["goals_success","goals_failed", "average_water_intakes"];

}
