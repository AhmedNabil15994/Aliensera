<?php namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Item;
use App\Models\Variables;
use App\Models\CompanyPercentage;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller {

    use \TraitsFunc;

    public function index(){
        $input = \Input::all();
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function selectProduct($id) {
        $productObj = Product::getOne($id);
        $input = \Input::all();

        if ($productObj == null) {
            return \TraitsFunc::ErrorMessage("This Product not found", 400);
        }

        $newItems = $productObj->Item()->whereIn('status',[0,4])->count();
        $quantity = isset($input['quantity']) ? $input['quantity'] : 1 ;

        if($quantity > $newItems){
            return \TraitsFunc::ErrorMessage("There Are ". $newItems ." Available Items!", 400);
        }

        $items = Item::where('product_id',$id)->where('status',0)->orderBy('id','ASC')->get()->take($quantity);
        $total = 0;
        $merchants = [];
        $itemCodes = [];
        foreach ($items as $value) {
            $itemObj = Item::where('item_id',$value->item_id)->first();
            $total+= $itemObj->item_price;
            $merchants[] = $itemObj->sold_by;
            $itemCodes[] = $itemObj->item_id;
        }

        Item::where('product_id',$id)->whereIn('item_id',$itemCodes)->update(['status'=>'4']);

        $vat = Variables::getVar('VAT');
        $vat_value = round(($total * $vat) / 100,2);
        $priceValue = round($total + $vat_value,2);

        $percentageObj = CompanyPercentage::getOnePercentage();
        $invoice_percentage = round(($percentageObj->percentage * $priceValue) / 100 ,2);
        $merchant_percentage = round($priceValue - $invoice_percentage - $vat_value ,2); 

        $invoiceObj = new Invoice;
        $invoiceObj->customer_id = USER_ID;
        $invoiceObj->item_ids = serialize($itemCodes);
        $invoiceObj->number_of_items = count($itemCodes);
        $invoiceObj->merchant_ids = serialize($merchants);
        $invoiceObj->status = 1;
        $invoiceObj->total = $total;
        $invoiceObj->vat = $vat;
        $invoiceObj->vat_value = $vat_value;
        $invoiceObj->payment = $input['payment'];
        $invoiceObj->percentage_id = $percentageObj->id;
        $invoiceObj->percentage_value = $percentageObj->percentage;
        $invoiceObj->invoice_percentage = $invoice_percentage;
        $invoiceObj->merchant_percentage = $merchant_percentage;
        $invoiceObj->created_at = DATE_TIME;
        $invoiceObj->created_by = USER_ID;
        $invoiceObj->save();

        foreach ($itemCodes as $value) {
            $itemObj = Item::where('item_id',$value)->first();
            $item_price = $itemObj->item_price;
            $vat_value = round(($item_price * $vat) / 100 ,2);
            $priceValue = round($item_price + $vat_value ,2);
            $item_percentage = round(($percentageObj->percentage * $priceValue) / 100 ,2);
            $merchant_percentage = round($priceValue - $item_percentage - $vat_value ,2); 

            $InvoiceItemObj = new InvoiceItem;
            $InvoiceItemObj->invoice_id = $invoiceObj->id;
            $InvoiceItemObj->item_id = $value;
            $InvoiceItemObj->item_price = $item_price;
            $InvoiceItemObj->merchant_id = $itemObj->sold_by;
            $InvoiceItemObj->customer_id = USER_ID;
            $InvoiceItemObj->percentage_id = $percentageObj->id;
            $InvoiceItemObj->percentage_value = $percentageObj->percentage;
            $InvoiceItemObj->item_percentage = $item_percentage;
            $InvoiceItemObj->merchant_percentage = $merchant_percentage;
            $InvoiceItemObj->status = 1;
            $InvoiceItemObj->created_at = DATE_TIME;
            $InvoiceItemObj->save();
        }
        CompanyPercentage::updateUsed($percentageObj->id);

        $statusObj['data'] = Product::getData($productObj,$invoiceObj->id);
        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

    public function confirmInvoice($id){
        $invoiceObj = Invoice::find($id);
        if ($invoiceObj == null || $invoiceObj->customer_id != USER_ID) {
            return \TraitsFunc::ErrorMessage("This Invoice not found", 400);
        }

        $cancel_items = [];
        $items = unserialize($invoiceObj->item_ids);
        if($invoiceObj->cancel_items != null){
            $cancel_items = unserialize($invoiceObj->cancel_items);
        }

        foreach ($items as $value) {
            if(!in_array($value, $cancel_items)){
                Item::where('item_id',$value)->update(['status'=>'1']);
            }
        }

        $statusObj['status'] = \TraitsFunc::SuccessResponse();
        return \Response::json((object) $statusObj);
    }

}
