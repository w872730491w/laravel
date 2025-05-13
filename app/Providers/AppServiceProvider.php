<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->macroResponse();



        Gate::before(function (Admin $user): ?bool {
            return $user->hasRole('super admin') ? true : null;
        });

        JsonResource::withoutWrapping();
    }

    public function macroResponse()
    {
        Response::macro('success', function (int $code = 0, string $message = '获取成功', $response = null) {
            $data = compact('code', 'message', 'response');
            return Response::json($data);
        });

        Response::macro('error', function (int $code = 1, string $message = '错误', $response = null) {
            $data = compact('code', 'message', 'response');
            if ($response instanceof ValidationException) {
                $data['code'] = 422;
                $data['message'] = $response->getMessage();
                $data['response'] = $response->errors();
            }
            return Response::json($data);
        });
    }
}
