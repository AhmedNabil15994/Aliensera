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

        $PUSHER_APP_ID = Variable::getVar('PUSHER_APP_ID');
        $PUSHER_APP_KEY = Variable::getVar('PUSHER_APP_KEY');
        $PUSHER_APP_SECRET = Variable::getVar('PUSHER_APP_SECRET');
        $CLUSTER = Variable::getVar('CLUSTER');


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

        $this->app['config']['broadcasting'] = [
            'connections' => [
                'pusher' => [
                    'driver' => 'pusher',
                    'key' => env('PUSHER_APP_KEY',$PUSHER_APP_KEY),
                    'secret' => env('PUSHER_APP_SECRET',$PUSHER_APP_SECRET),
                    'app_id' => env('PUSHER_APP_ID',$PUSHER_APP_ID),
                    'options' => [
                        'cluster' => $CLUSTER,
                        'encrypted' => true,
                        'useTLS' => true
                    ],
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