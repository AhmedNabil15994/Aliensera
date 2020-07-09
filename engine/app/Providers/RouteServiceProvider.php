<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

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
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "general" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
         Route::middleware(['general'])->namespace($this->namespace)
            ->group(function () {
                
            require app_path('Modules/Auth/routes.php');
            require app_path('Modules/Faculty/routes.php');
            require app_path('Modules/University/routes.php');
            require app_path('Modules/Field/routes.php');
            require app_path('Modules/Page/routes.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::middleware(['api'])->namespace($this->namespace)
             ->group(function () {
                require app_path('Modules/User/routes.php');
                require app_path('Modules/Home/routes.php');
                require app_path('Modules/Course/routes.php');
                require app_path('Modules/Lesson/routes.php');
                require app_path('Modules/Video/routes.php');
                require app_path('Modules/Question/routes.php');
                require app_path('Modules/Favourites/routes.php');
                require app_path('Modules/Cart/routes.php');
                require app_path('Modules/Chat/routes.php');
                require app_path('Modules/Feedback/routes.php');
                require app_path('Modules/InstructorRate/routes.php');
                require app_path('Modules/Certificate/routes.php');

        });
    }
}
