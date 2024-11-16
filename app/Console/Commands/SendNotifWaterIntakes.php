<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WaterIntakesNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use YieldStudio\LaravelExpoNotifier\Dto\ExpoNotification;

class SendNotifWaterIntakes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-notif-water-intakes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNotNull("token")->whereHas("goalsToday")->get();

        foreach ($users as $user) {
            try {
                $user->notify(new WaterIntakesNotification($user->goalsToday->remaining_water));
                Log::error("Notiikasi send to {$user->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send notification to user {$user->id}: {$e->getMessage()}");
            }
        }
    }

}
