<?php

namespace Diglabby\Doika\Providers;

use App\Providers\RouteServiceProvider as BasicRouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RouteServiceProvider extends BasicRouteServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Diglabby\Doika\Http\Controllers';

    /** @var string */
    protected $laravelNamespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWidgetRoutes();
        $this->mapDashboardRoutes();
    }

    protected function mapWidgetRoutes()
    {
        Route::middleware('web')
            ->prefix('doika')
            ->namespace($this->namespace)
            ->group(base_path('routes/widget.php'));
    }

    protected function mapDashboardRoutes()
    {
        Route::middleware(['web'])
            ->namespace($this->namespace)
            ->prefix('doika/dashboard')
            ->group(base_path('routes/dashboard.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->laravelNamespace)
            ->group(base_path('routes/web.php'));

        Route::middleware(['web', 'locale'])
            ->prefix(LaravelLocalization::setLocale())
            ->namespace($this->laravelNamespace)
            ->group(base_path('routes/auth.php'));

        Route::middleware(['web', 'locale', 'auth', 'can:access backend'])
            ->prefix(LaravelLocalization::setLocale().'/'.config('app.admin_path'))
            ->namespace($this->laravelNamespace.'\Dashboard')
            ->as('admin.')
            ->group(base_path('routes/admin.php'));

    }
}
