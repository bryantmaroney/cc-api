<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\PaymentGatewayMerchantPortal;
use Illuminate\Support\Facades\DB;
use App\Customer;

class PaymentGatewayMerchantController extends BaseController
{
    public  function saveCustomer(Request $request){

        $data = null;
        $gw = new PaymentGatewayMerchantPortal();

        //Billing info from request
        $data['first_name'] = $request->input('first_name');
        $data['last_name'] = $request->input('last_name');
        $data['email'] = $request->input('email');
        $data['address1'] = $request->input('address1');
        $data['city'] = $request->input('city');
        $data['state'] = $request->input('state');
        $data['country'] = $request->input('country');
        $data['amount'] = $request->input('amount');
        $data['zip'] = $request->input('zip');
        $data['phone'] = $request->input('phone');
        $data['cc'] = $request->input('cc');
        $data['ccexp'] = $request->input('ccexp');
        $data['stored_credential_indicator'] = $request->input('stored_credential_indicator');
        $data['order_id'] = '0123456';

        //Login Info
        $gw->setLogin("lostempire", "F2R9I%n9hD6&");

        $getCustomer = DB::table('customers')->select('id','vault_id')->where('email', $data['email'])->get();
        if(count($getCustomer) > 0 ){
            //return $getCustomer[0]->vault_id;
            $r = $gw->payCustomer($data['amount'],$getCustomer[0]->vault_id,"used");
            return json_encode(array("customer_id" => $getCustomer[0]->vault_id ,"transactionResponse" => $r ));
        }
        else{
            
            //Billing Info
            $gw->setCustomer($data['first_name'],$data['last_name'],$data['address1'], $data['city'],
                $data['state'],$data['country'],$data['zip'],'add_customer');

            $vault_data = $gw->saveCustomer($data['cc'],$data['ccexp']);

            //Billing Info
            $gw->setBilling($data['first_name'],$data['last_name'],'',$data['address1'], '',$data['city'],
                $data['state'],$data['zip'],$data['country'],$data['phone'],'','','');

            //Shipping Info
            $gw->setShipping($data['first_name'],$data['last_name'],"",$data['address1'],"", $data['city'],
            $data['state'],$data['zip'],$data['country'],"");

            //Order Info
            $gw->setOrder($data['order_id'], "LostEmpireOrderDescription", 0, 2, "", "");
        
            DB::table('customers')->insert([
                "firstname" => $data['first_name'],
                "lastname" => $data['last_name'],
                "email" => $data['email'],
                "vault_id" => $vault_data['customer_vault_id']
            ]);
            $max_id = DB::table('customers')->max('id');

            //Do Sale
            $r = $gw->doSale($data['amount'],$data['cc'],$data['ccexp']);

            return json_encode(array("customer_id" =>$max_id , "transactionResponse" => $r));
        }
        return false;
    }


    public  function payByCustomerId(Request $request){

        $data = null;
        $gw = new PaymentGatewayMerchantPortal();

        $data['customer_id'] = $request->input('customer_id');
        $data['amount'] = $request->input('amount');
        $data['stored_credential_indicator'] = $request->input('stored_credential_indicator');

        //Login Info
        $gw->setLogin("lostempire", "F2R9I%n9hD6&");
        $getCustomer = DB::table('customers')->select('id','vault_id')->where('id', $data['customer_id'])->get();
        
        if(count($getCustomer) > 0 ){
            $r = $gw->payCustomer($data['amount'],$getCustomer[0]->vault_id,$data['stored_credential_indicator']);
            return json_encode(array("msg" => "SUCCESS","customer_id" => $getCustomer[0]->id ,"transactionResponse" => $r ));
        }
        else{
            return json_encode(array("msg" => "FAILED"));
        }
        return false;
    }
}
