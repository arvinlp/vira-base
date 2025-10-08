<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use \App\Http\Requests\TenantAddRequest;
use \App\Http\Requests\TenantEditRequest;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Tenant::all();
        return self::getViewStaff('Tenants', [
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TenantAddRequest $request)
    {

        $data = $request->validated();
        try {
            Tenant::create($data);
            return redirect()->route('panel.tenants.index')->with('success', 'Tenant created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('panel.tenants.index')->with('error', 'Tenant not created!');
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TenantEditRequest $request, Tenant $permission)
    {
        $data = $request->validated();

        $permission->update($data);

        return redirect()->route('panel.tenants.index')->with('success', 'Permission updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $permission)
    {
        $permission->delete();

        return redirect()->route('panel.tenants.index')
            ->with('success', 'Permission deleted successfully!');
    }
}
