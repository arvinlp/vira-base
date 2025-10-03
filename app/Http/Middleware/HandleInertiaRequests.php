<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $permission = $request->user()?->getAllPermissions()->pluck('name')->toArray();
        $languages = json_decode(Setting::pull('languages'));
        if ($user = $request->user()) {
            $roleName = null;
            if ($role = Role::find($user->role_id))
                $roleName = $role->name;
            $auth = [
                'user' => $user,
                'role' => $roleName,
                'abilities' => $user->getAllPermissions()->pluck('name'),
            ];
        } else {
            $auth = null;
        }

        return [
            ...parent::share($request),
            'auth' => $auth,

            // Share app name and url
            'appName' => config('app.name'),
            'appUrl' => config('app.url'),
            'appDebug' => env('APP_DEBUG'),
            'appDemo' => env('APP_DEMO'),
            'env' => [
                'APP_ENV' => env('APP_ENV'),
            ],
            // اطلاعات tenant (در صورتی که multi-tenant فعال باشه)
            'tenant' => function () {
                $tenant = tenant();
                if (!$tenant) return null;
                return [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'owner_id' => $tenant->owner_id,
                ];
            },
        ];
    }
}
