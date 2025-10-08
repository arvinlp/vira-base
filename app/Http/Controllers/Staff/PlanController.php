<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use \App\Http\Requests\PlanAddRequest;
use \App\Http\Requests\PlanEditRequest;

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
    public function store(PlanAddRequest $request)
    {

        $data = $request->validated();
        try {
            Plan::create($data);
            return redirect()->route('panel.plans.index')->with('success', 'Plan created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('panel.plans.index')->with('error', 'Plan not created!');
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PlanEditRequest $request, Plan $permission)
    {
        $data = $request->validated();

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
