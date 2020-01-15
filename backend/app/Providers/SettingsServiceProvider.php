<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Variable;

class SettingsServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $client_id = Variable::getVar('CLIENT_ID');
        $client_secret = Variable::getVar('CLIENT_SECRET');
        $access_token = Variable::getVar('ACCESS_TOKEN');
        $this->app['config']['vimeo'] = [
            'default' => 'main',
            'connections' => [
                'main' => [
                    'client_id' => env('VIMEO_CLIENT', $client_id),
                    'client_secret' => env('VIMEO_SECRET', $client_secret),
                    'access_token' => env('VIMEO_ACCESS', $access_token),
                ],
            ],
        ];
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

}