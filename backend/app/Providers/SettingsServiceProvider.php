<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Variable;
use App\Models\Account;

class SettingsServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $accounts = Account::NotDeleted()->get();
        $connections = [];
        $first = 'main';
        foreach ($accounts as $key => $account) {
            if($key == 0){
                $first = $account->name;
            }
            $connections[$account->name] = [
                'client_id' => env('VIMEO_CLIENT', $account->client_id),
                'client_secret' => env('VIMEO_SECRET', $account->client_secret),
                'access_token' => env('VIMEO_ACCESS', $account->access_token),
            ];
        }

        $PUSHER_APP_ID = Variable::getVar('PUSHER_APP_ID');
        $PUSHER_APP_KEY = Variable::getVar('PUSHER_APP_KEY');
        $PUSHER_APP_SECRET = Variable::getVar('PUSHER_APP_SECRET');
        $CLUSTER = Variable::getVar('CLUSTER');


        $this->app['config']['vimeo'] = [
            'default' => $first,
            'connections' => $connections,
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