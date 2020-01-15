<?php namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model{

    use \TraitsFunc;

    protected $table = 'invoice_details_t';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
