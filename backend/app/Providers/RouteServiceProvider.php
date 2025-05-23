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
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {   
        @define('DATE_TIME', date("Y-m-d H:i:s"));
        Route::middleware(['backend'])->namespace($this->namespace)
            ->group(function () {
            require app_path('Modules/Users/routes.php');
            require app_path('Modules/Groups/routes.php');
            require app_path('Modules/Auth/routes.php');
            require app_path('Modules/Variables/routes.php');
            require app_path('Modules/University/routes.php');
            require app_path('Modules/Faculty/routes.php');
            require app_path('Modules/Courses/routes.php');
            require app_path('Modules/Pages/routes.php');
            require app_path('Modules/Dashboard/routes.php');
            require app_path('Modules/Fields/routes.php');
            require app_path('Modules/Lessons/routes.php');
            require app_path('Modules/Chat/routes.php');
            require app_path('Modules/Requests/routes.php');
            require app_path('Modules/QuizScores/routes.php');
            require app_path('Modules/Notifications/routes.php');
            require app_path('Modules/Quizes/routes.php');
            require app_path('Modules/CourseStudents/routes.php');
            require app_path('Modules/Comments/routes.php');
            require app_path('Modules/Upgrade/routes.php');
            require app_path('Modules/Certificates/routes.php');
            require app_path('Modules/Account/routes.php');
    
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
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
