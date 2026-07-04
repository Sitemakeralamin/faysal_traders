<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Order;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Transactions;


class SslCommerzPaymentController extends Controller
{

    public function exampleEasyCheckout()
    {
        return view('exampleEasycheckout');
    }

    public function exampleHostedCheckout()
    {
        return view('exampleHosted');
    }

    public function index(Request $request)
    {
        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.
        //dd($request);

        $post_data = array();
        $post_data['total_amount'] = $request->total; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = $request->tran_id; // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $request->name;
        $post_data['cus_email'] = $request->email;
        $post_data['cus_add1'] = $request->shipping_address;
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $request->phone;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = $request->value_a;
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        #Before  going to initiate the payment order status need to insert or update as Pending.
        /*
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency']
            ]);
        */

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

    public function payViaAjax(Request $request)
    {

        # Here you have to receive all the order data to initate the payment.
        # Lets your oder trnsaction informations are saving in a table called "orders"
        # In orders table order uniq identity is "transaction_id","status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['store_id'] = "01766996853";
        $post_data['store_passwd'] = "Sohag66996853@&";
        
        $post_data['total_amount'] = '100'; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        $post_data['success_url'] = "your payment application success url";
        $post_data['fail_url'] = "your payment application fail url";   
        $post_data['cancel_url'] = "your payment application cancel url";

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = 'Sohag';
        $post_data['cus_email'] = 'shshohagh3@mail.com';
        $post_data['cus_add1'] = 'Agargaon';
        $post_data['cus_add2'] = "Taltala";
        $post_data['cus_city'] = "Dhaka";
        $post_data['cus_state'] = "Mirpur";
        $post_data['cus_postcode'] = "1207";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = '8801766996853';
        $post_data['cus_fax'] = "9144465";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = $request->value_a;
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        # EMI STATUS
        $post_data['emi_option'] = "0";

        # CART PARAMETERS
        $post_data['cart'] = json_encode(array(
            array("product"=>"DHK TO BRS AC A1","amount"=>"200.00"),
            array("product"=>"DHK TO BRS AC A2","amount"=>"200.00"),
            array("product"=>"DHK TO BRS AC A3","amount"=>"200.00"),
            array("product"=>"DHK TO BRS AC A4","amount"=>"200.00")
        ));

        $post_data['product_amount'] = "90";
        $post_data['vat'] = "10";
        $post_data['discount_amount'] = "10";
        $post_data['convenience_fee'] = "10";


        /* SELECT `id`, `code`, `customer_id`, `price`, `name`, `email`, `phone`, `city`, `district_id`, `area_id`, `shipping_address`, `ship_to_another_address_status`, `ship_to_another_address`, `coupon_status`, `coupon_code`, `coupon_discount_amount`, `delivery_boy_id`, `delivery_charge`, `vat`, `order_status`, `payment_status`, `payment_method`, 
        `manual_mfs_account_name`, `manual_mfs_payment_number`, `manual_mfs_transaction_id`, 
        `transaction_id`, `total_payable`, `paid`, `sender_amount`, `note`, 
        `created_at`, `updated_at` FROM `orders` WHERE 1 */
        #Before  going to initiate the payment order status need to update as Pending.
        $page_controller = new PageController;
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'code' => $page_controller->generate_order_code(),
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'price' => $post_data['total_amount'],
                'total_payable' => $post_data['total_amount'],
                'order_status' => 'Pending',
                'district_id' => '1',
                'shipping_address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'note' => $post_data['currency']
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

    public function success(Request $request)
    {
        echo "Transaction is Successful";

        $tran_id = $request->tran_id;
        $amount = $request->amount;
        $currency = $request->currency;

        if($request->value_a == 'order_payment'){

            $order_info = Order::where('code', $tran_id)->first();
            /* SELECT `id`, `customer_id`, `phone`, `tran_id`, `which_payment`, `payment_method`, `amount`, `store_amount`, `minus_amount`, `created_at`, `updated_at` FROM `transactions` WHERE 1 */
            $transaction = new Transactions;
            $transaction->customer_id = $order_info->customer_id;
            $transaction->phone = $order_info->phone;
            $transaction->tran_id = $tran_id;
            $transaction->which_payment = 'order payment';
            $transaction->payment_method = 'online payment';
            $transaction->amount = $amount;
            $transaction->store_amount = $request->store_amount;
            $transaction->minus_amount = $amount - $request->store_amount;
            $transaction->created_at = now();
            $store_transaction = $transaction->save();

            if($store_transaction) {
                $order_info->payment_method = 'online payment';
                $order_info->payment_status = 'paid';
                $order_info->paid = $amount;
                $order_info->save();
                return redirect()->route('order.complete', $order_info->code);
            }

        }
        else if($request->value_a == 'wallet_payment'){ // Wallet recharge payment

        }
        else {
            return Redirect()->route('index')->with('error', 'Network Error!');
        }



    }

    public function fail(Request $request)
    {
        if($request->value_a == 'order_payment'){
            $tran_id = $request->tran_id;
            $order_info = Order::where('code', $tran_id)->first();
            $order_product = $order_info->order_product;
            if(count($order_product) > 0) {
                foreach($order_product as $product) {
                    $product->delete();
                }
            }
            $order_info->delete();
            return Redirect()->route('checkout')->with('error', 'Failed due to network error!');
        }
        else if($request->value_a == 'wallet_payment'){ // wallet recharge payment

        }
        else {
            return Redirect()->route('index')->with('error', 'Network Error!');
        }

        /*
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Failed']);
            echo "Transaction is Falied";
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }
        */

    }

    public function cancel(Request $request)
    {
        if($request->value_a == 'order_payment'){
            //dd($request);
            $tran_id = $request->tran_id;
            $order_info = Order::where('code', $tran_id)->first();
            $order_product = $order_info->order_product;
            
            if(count($order_product) > 0) {
                foreach($order_product as $product) {
                    $product->delete();
                }
            }
            $order_info->delete();
            return Redirect()->route('checkout')->with('error', 'Payment mode is canceled!');
        }
        else if($request->value_a == 'wallet_payment'){

        }
        else {
            return Redirect()->route('index')->with('error', 'Network Error!');
        }

    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                }
            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }

}
