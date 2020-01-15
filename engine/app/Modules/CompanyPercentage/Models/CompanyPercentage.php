<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class CompanyPercentage extends Model{

    use \TraitsFunc;

    protected $table = 'company_percentage_t';
    protected $primaryKey = 'id';
    public $timestamps = false;

    static function getOnePercentage(){
        $dateTime = DATE_TIME;
    	$percentageObj = self::where('from_date','<=',$dateTime)->where('to_date','>=',$dateTime)->first();
    	if($percentageObj != null){
    		return $percentageObj;
    	}
    	return 0;
    }

    static function updateUsed($id){
        return self::where('id',$id)->update(['is_used'=>'1']);
    }

}
