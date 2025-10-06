<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\User as Staff;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return self::getViewStaff('DashboardAdmin', [
            'stats' => [
                'staffs' => Staff::count(),
                'users' => User::count(),
                'roles' => Role::count(),
                'permissions' => Permission::count(),
                'activities' => 15,
            ]
        ]);
    }
}