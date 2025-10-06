<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Plan::all();
        return self::getViewStaff('Plans', [
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'null|text|max:65535',
            'price' => 'required|json',
            'features' => 'required|json',
            'is_free' => 'null|boolean',
            'is_demo' => 'null|boolean',
            'status' => 'required|string|in:active,inactive',
        ]);

        Plan::create($data);

        return redirect()->route('panel.plans.index')->with('success', 'Permission created successfully!');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $permission)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
        ]);

        $permission->update($data);

        return redirect()->route('panel.plans.index')->with('success', 'Permission updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $permission)
    {
        $permission->delete();

        return redirect()->route('panel.plans.index')
            ->with('success', 'Permission deleted successfully!');
    }
}
