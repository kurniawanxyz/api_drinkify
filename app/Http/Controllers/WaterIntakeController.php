<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWaterIntakeStore;
use App\Http\Requests\UpdateWaterIntakeStore;
use App\Models\WaterIntake;
use Illuminate\Http\Request;

class WaterIntakeController extends Controller
{
    public function index(Request $request)
    {

        $goals = auth()->user()->water_intakes()->paginate(5);
        return $this->response(200, "Get data goals successfully", $goals);
    }

    public function show($id)
    {
        $goal = auth()->user()->water_intakes()->findOrFail($id);
        return $this->response(200, "Get data goals successfully", $goal);
    }

    public function store(StoreWaterIntakeStore $request)
    {
        auth()->user()->water_intakes()->create($request->validated());
        return $this->response(201,"Create daily goals successfully");
    }

    public function update(UpdateWaterIntakeStore $request, $id)
    {
        auth()->user()->water_intakes()->findOrFail($id)->update($request->validated());
        return $this->response(200,"Update daily goals successfully");
    }

    public function destroy($id)
    {
        auth()->user()->water_intakes()->findOrFail($id)->delete();
        return $this->response(201,"Delete daily goals successfully");
    }
}
