<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaAdminRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'admin';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = function () use ($request) {
            /**
             * @var Admin
             */
            $user = $request->user('admin');
            if ($user) {
                $is_super_admin = $user->hasRole('super admin');
                $permissions = $is_super_admin ? Permission::getPermissions([
                    'guard_name' => 'admin'
                ]) : $user->getAllPermissions();
                return $user->only(['id', 'avatar', 'nickname']) + [
                    'permissions' => $permissions,
                    'is_super_admin' => $is_super_admin
                ];
            }
            return null;
        };
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'user' => $user,
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
