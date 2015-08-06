<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

use App\Http\Models\BuyModel;

class PaypalController extends BaseController
{

    private $_api_context;

    public function __construct()
    {
        // setup PayPal api context
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);

        $this->middleware('auth');
        if (\Auth::check()) {
            $this->user = \Auth::user()->id;
        }
        $this->BuyModel = new BuyModel();
    }

    // duplicate of BuyController function, slight modification
    public function cartSubtotal()
    {
        $args['cart`.`buyer_id'] = $this->user;
        $args['category_id'] = 1; // TEMPORARY CATEGORY: CARDS ONLY
        $sellers = $this->BuyModel->cartSubtotals($args);

        $price = 0; $quantity = 0; $shipping = 0;
        foreach($sellers as $items) {
            $price += $items->total;
            $quantity += $items->seller_quantity;
            $ship = $this->BuyModel->cardShippingEstimate($items->seller_quantity);
            if(isset($ship[0]->estimate)){
                $shipping += $ship[0]->estimate;
            }
        }
        return ['price'=>$price, 'quantity'=>$quantity, 'shipping'=>$shipping];
    }

    public function postPayment()
    {
        $order['speed_id'] = (int)\Request::get('speed');
        $order['address_id'] = (int)\Request::get('address');
        $order['insurance'] = (int)\Request::get('insurance');

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_list = new ItemList();
        // add everything in cart to payment object
        $products = $this->BuyModel->cartPreview(['buyer_id'=>$this->user]);
        $subtotal = $this->cartSubtotal($products);
        // card shipping prices
        $item = new Item();
        $item->setName('Shipping')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($subtotal['shipping']/100);
        $items[] = $item;

        foreach($products as $p) {
            $item = new Item();
            $item->setName($p->name_str)
                ->setCurrency('USD')
                ->setQuantity($p->quantity)
                ->setPrice($p->price/100);
            $items[] = $item;
        }

        if(empty($items)) {
            return redirect('cart')
                ->with('alert', 'No items in cart');
        }

        $item_list->setItems($items);

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal(($subtotal['shipping']+$subtotal['price'])/100);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your custom transaction description');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(\URL::route('payment.status'))
            ->setCancelUrl(\URL::route('payment.status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        // After adding items to payment object, add to preorder
        $args = ['buyer_id'=>$this->user];
        $this->BuyModel->addToPreorder($args);
        \Session::forget('cart_preview');
        // insert order details
        $order['buyer_id'] = $this->user;
        $order['shipping_amt'] = $subtotal['shipping'];
        $order['order_id'] = $this->BuyModel->uniqueCode('ord_ls_orders', 'order_id');
        $this->BuyModel->insertOrder($order);
        // send payment curl, redirect to paypal page
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            // payment failure actions
            \Session::put('cart_preview', $this->BuyModel->cartPreview(['buyer_id'=>$this->user]));
            $this->BuyModel->removeFromPreorder(['buyer_id'=>$this->user]);
            $this->BuyModel->deleteOrderId($order_id);
            // end payment failure actions
            if (\Config::get('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $err_data = json_decode($ex->getData(), true);
                exit;
            } else {
                die('Some error occur, sorry for inconvenient');
            }
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        \Session::put('paypal_payment_id', $payment->getId());
        \Session::put('order_id', $order['order_id']);

        if(isset($redirect_url)) {
            // redirect to paypal
            return \Redirect::away($redirect_url);
        }

        return redirect('buy/checkout')
                ->with('error', 'Unknown error occurred');
    }

    public function getPaymentStatus()
    {
        // Get the payment ID before session clear
        $payment_id = \Session::get('paypal_payment_id');
        $order_id = \Session::get('order_id');
        \Session::forget('paypal_payment_id');
        \Session::forget('order_id');

        if (empty(\Input::get('PayerID')) || empty(\Input::get('token'))) {
            // payment failure actions
            \Session::put('cart_preview', $this->BuyModel->cartPreview(['buyer_id'=>$this->user]));
            $this->BuyModel->removeFromPreorder(['buyer_id'=>$this->user]);
            $this->BuyModel->deleteOrderId($order_id);
            // end payment failure actions
            return redirect('checkout')
                ->with('alert', 'Payment cancelled');
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        // PaymentExecution object includes information necessary
        // to execute a PayPal account payment.
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId(\Input::get('PayerID'));

        //Execute the payment
        $result = $payment->execute($execution, $this->_api_context);

        $paypal = ['order_id' => $order_id];
        $args = ['buyer_id'=>$this->user];
        if ($result->getState() == 'approved') { // payment made
            $paypal['payment_id'] = $result->getId();
            $paypal['status'] = $result->getState();
            $paypal['create_time'] = $result->getCreateTime();
            $paypal['email'] = $result->getPayer()->getPayerInfo()->getEmail();
            $payerAddress = $result->getPayer()->getPayerInfo()->getShippingAddress();
            $paypal['line1'] = $payerAddress->getLine1();
            $paypal['city'] = $payerAddress->getCity();
            $paypal['state'] = $payerAddress->getState();
            $paypal['postal_code'] = $payerAddress->getPostalCode();
            $paypal['country_code'] = $payerAddress->getCountryCode();
            unset($payerAddress);
            $transaction = $result->getTransactions()[0]->getAmount();
            $paypal['total'] = $transaction->getTotal() * 100;
            $paypal['currency'] = $transaction->getCurrency();
            unset($transaction);
            $this->BuyModel->insertPayment($paypal);

            $args['checkout'] = 1;
            $info = ['order_id' => $order_id];
            $this->BuyModel->insertOrderItems($info, $args);
            $this->BuyModel->deleteFromCart($args);
            //    email sellers to ship

            // $headers = "From: orders@colorcreep.com";
            $text = "Your Order " . $order_id . " has processed.";
            mail($paypal['email'], "Your Color Creep Order", $text);
            return redirect('order?id='.$order_id)->with('alert', 'Payment complete');
        } else {
            $this->BuyModel->removeFromPreorder($args);
            $this->BuyModel->deleteOrderId($order_id);
            return redirect('cart')
                ->with('alert', 'There was an error processing your payment');
        }
        return redirect('cart');
    }

}
?>
