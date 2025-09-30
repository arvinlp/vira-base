<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * نام root view برای همه‌ی رندرهای Inertia
     */
    protected $rootView = 'app';

    /**
     * تعیین نسخه‌ی assetها (برای cache busting)
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * دیتاهای سراسری که به همه‌ی صفحات پاس داده میشه
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            // اطلاعات کاربر لاگین‌شده
            'auth' => [
                'user' => $request->user() ? [
                    'id'    => $request->user()->id,
                    'name'  => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null,
            ],

            // فلش‌مسج‌ها (برای success یا error)
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
            ],

            // دیتاهای عمومی (مثلاً زبان یا تنظیمات)
            'app' => [
                'locale' => app()->getLocale(),
                'name'   => config('app.name'),
            ],
        ]);
    }
}
