<?php
namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WaterIntakesNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
    protected $description = 'Send water intake reminder notifications to users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereHas("goalsToday")
                     ->get();

        Log::info($users);
        foreach ($users as $user) {
            if (!$user->goalsToday) {
                continue;
            }

            try {
                if($user->goalsToday->remaining_water > 0){
                    $user->notifications()->create([
                        "title" => "Reminder to drink water",
                        "content" => "{$user->goalsToday->remaining_water}ml to achieve goals today"
                    ]);
                    $user->notify(new WaterIntakesNotification($user->goalsToday->remaining_water));
                }
                Log::info("Notification sent to {$user->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send notification to user {$user->id}: {$e->getMessage()}");
            }
        }
    }
}
