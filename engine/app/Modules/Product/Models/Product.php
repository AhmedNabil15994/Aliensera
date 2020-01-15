<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class Product extends Model{

    use \TraitsFunc;

    protected $table = 'products_t';
    protected $primaryKey = 'id';
    public $timestamps = false;

    function Item(){
    	return $this->hasMany('App\Models\Item','product_id');
    }

    static function getOne($id){
        return self::with('Item')
            ->where('id', $id)
            ->first();
    }

    static function getData($source,$invoice_id){
        $dataObj = new \stdClass();
        $dataObj->id = $source->id;
        $dataObj->invoice_id = $invoice_id;

        return $dataObj;
    }
}
