<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyGoalsStore;
use App\Http\Requests\UpdateDailyGoalsStore;
use App\Models\DailyGoals;
use Illuminate\Http\Request;

class DailyGoalsController extends Controller
{
    public function index(Request $request)
    {
        $goals = auth()->user()->daily_goals()->paginate(5);
        return $this->response(200, "Get data goals successfully", $goals);
    }

    public function today(Request $request)
    {
        // $goal = auth()->user()->daily_goals()->whereDate('created_at', now())->first();
        $goal = auth()->user()->goals_today;
        return $this->response(200, "Get data goals successfully", $goal);
    }

    public function show($id)
    {
        $goal = auth()->user()->daily_goals()->findOrFail($id);
        return $this->response(200, "Get data goals successfully", $goal);
    }

    public function store(StoreDailyGoalsStore $request)
    {
        $user = auth()->user();
        $today = now()->format('Y-m-d'); // Mengambil tanggal hari ini dalam format yang sesuai (YYYY-MM-DD)

        // Cek apakah sudah ada daily goal untuk hari ini
        $dailyGoal = $user->daily_goals()->whereDate('created_at', $today)->first();

        if ($dailyGoal) {
            // Jika sudah ada, lakukan update
            $dailyGoal->update($request->validated());
            return $this->response(200, "Daily goals updated successfully");
        } else {
            // Jika belum ada, buat data baru
            $user->daily_goals()->create($request->validated());
            return $this->response(201, "Daily goals created successfully");
        }
    }


    public function update(UpdateDailyGoalsStore $request, $id)
    {
        auth()->user()->daily_goals()->findOrFail($id)->update($request->validated());
        return $this->response(200,"Update daily goals successfully");
    }

    public function destroy($id)
    {
        auth()->user()->daily_goals()->findOrFail($id)->delete();
        return $this->response(201,"Delete daily goals successfully");
    }

}
