<?php namespace App\Http\Middleware;

use App\Models\ApiAuth;
use App\Models\ApiKeys;
use App\Models\Users;
use App\Models\Variable;
use Closure;
use Illuminate\Support\Facades\Session;

class GeneralAuthEngine
{

    public function handle($request, Closure $next){
        if(last(request()->segments()) == 'downloadCertificate'){
            return $next($request);
        }

        if (!isset($_SERVER['HTTP_APIKEY'])) {
            return \TraitsFunc::ErrorMessage("API key is invalid", 401);
        }

        if (!isset($_SERVER['HTTP_APPVER'])) {
            return \TraitsFunc::ErrorMessage("APP Version is invalid", 401);
        }

        $apiKey = $_SERVER['HTTP_APIKEY'];
        $studentVer = $_SERVER['HTTP_APPVER'];

        $getAPIKey = ApiKeys::checkApiKey();
        if ($getAPIKey == null) {
            return \TraitsFunc::ErrorMessage("Invalid API Key, Please Check Kernel Authentication", 401);
        }

        $appVer = Variable::getVar('APP_VER');
        if($appVer != $studentVer){
            return \TraitsFunc::ErrorMessage("Invalid APP Version, Please Update Application", 401);
        }

        define('API_KEY', $apiKey);

        return $next($request);
    }
}
