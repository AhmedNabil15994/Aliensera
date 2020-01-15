<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Variables extends Model{

    use \TraitsFunc;

    protected $table = 'variables_t';
    protected $primaryKey = 'id';
    public $timestamps = false;

    static function getVar($var_key){
    	$varObj = self::where('var_key',$var_key)->first();
    	if($varObj != null){
    		return $varObj->var_value;
    	}
    	return 0;
    }
}
