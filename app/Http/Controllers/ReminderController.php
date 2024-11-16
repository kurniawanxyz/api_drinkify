<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReminderRequest;
use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reminders = auth()->user()->reminders;
        return $this->response(200, "Sukses", $reminders);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreReminderRequest $request)
    {
        $reminders = auth()->user()->reminders()->create($request->validated());
        return $this->response(200, "Sukses", $reminders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReminderRequest $request)
    {
        $reminders = auth()->user()->reminders()->create($request->validated());
        return $this->response(200, "Sukses", $reminders);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reminder $reminder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reminder $reminder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $reminder)
    {
        $reminders = auth()->user()->reminders()->find($reminder)->update($request->validated());
        return $this->response(200, "Sukses", $reminders);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($reminder)
    {
        Reminder::find($reminder)->delete();
        return $this->response(200, "Sukses");
    }
}
