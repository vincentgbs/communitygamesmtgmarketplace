<?php namespace App\Http\Controllers;

use App\Http\Controllers\Auth;
use App\Http\Models\UserModel;
use App\Http\Models\OrderModel;
use App\Http\Models\SellModel;
use App\Http\Models\BuyModel;

class BuyController extends Controller {

	public function __construct()
	{
		parent::__construct();
        $this->BuyModel = new BuyModel();

		if (isset($this->user)) {
			if (\Session::get('cart_merge') != 1) {
				$this->sessionCartToUserCart();
				\Session::put('cart_merge', 1);
			}
		}
	}

	public function index()
	{
        $data['cartPreview'] = \Session::get('cart_preview');
        return view('welcome')->withData($data);
	}

	// ajax update cart preview from session cart
	public function cartPreview()
	{
		$data['cartPreview'] = \Session::get('cart_preview');
		return view('buy/cartPreview')->withData($data);
	}

	public function shoppingCart()
	{
		if (isset($this->user)) {
			$args = ['buyer_id'=>$this->user];
		} else {
			$args = ['session_id'=>$this->session];
		}
		$data['cart'] = $this->BuyModel->cartDetails($args);

		$subtotal = $this->cartSubtotal(true);
		$subtotal['total'] = $subtotal['price'] + $subtotal['shipping'];
		$data['subs'] = $subtotal;
		return view('buy/shoppingCart')->withData($data);
	}

	public function addToCart($i=null, $q=null, $p=null, $name=true, $fromCart=false) {
        $i = isset($i)? $i : $this->POST('i', 'i');
        $q = isset($q)? $q : $this->POST('q');
        $p = isset($p)? $p : $this->POST('p');
		if ($name) {
			$n = $this->POST('n', 'w', 32);
		}

        $args = ['inv`.`inventory_id'=>$i];
        $check = $this->BuyModel->selectInventory($args, false);
        $quantity = $check[0]->quantity;
        if ($q < 0) {
            echo "Invalid Quantity"; return;
        }
        if ($quantity <= 0) {
            echo "Sold Out"; return;
        } else if ($quantity < $q) {
            $q = $quantity;
            $remains = "Not Enough";
        }
        if ($p != $check[0]->price) {
            echo "Price Change!"; return;
        }
        $args = ['inventory_id' => $i,
                'quantity' => $q,
                'price' => $p];
        if (isset($this->user)) {
            $args['buyer_id'] = $this->user;
        } else {
            $args['session_id'] = $this->session;
        }
        $this->BuyModel->addToCart($args);
        $this->BuyModel->removeFromInventory($i, $q);
		$cart = \Session::get('cart_preview');
		if (isset($cart[$i])) {
			$cart[$i]->quantity += $q;
			$cart[$i]->price = $p;
		} else {
			$item = (object) ["inventory_id" => $i,
				"name_str"=>$n,
				"price"=>$p,
				"quantity"=>$q];
			$cart[$i] = $item;
		}
		\Session::put('cart_preview', $cart);
		if ($fromCart) {
			return $cart[$i]->quantity;
		} else {
			return ($quantity - $q);
		}
    }

	public function removeFromCart($i, $q, $p, $cartObject=null) {
		if ($q < 0 || $p < 0) {
			echo "Invalid Amount"; return;
		}

		$args = ['inv`.`inventory_id' => $i];
        $check = $this->BuyModel->selectInventory($args, false);
        $price = $check[0]->price;

		$cartObject = \Session::get('cart_preview');
		$quantity = $cartObject[$i]->quantity;
		$q = min($quantity, $q); // min of (quantity in cart and quantity to remove)

		if($p != $price) {
			$remains = "Price Change!";
			$q = $quantity; // remove entire cart quantity
		} else {
			$remains = $quantity - $q;
		}
		$args = ['quantity' => $q,
				'inventory_id' => $i];
		if (isset($this->user)) {
			$args['buyer_id'] = $this->user;
		} else {
			$args['session_id'] = $this->session;
		}
		$this->BuyModel->removeFromCart($args);
		$this->BuyModel->addToInventory($i, $q);
		$cartObject[$i]->quantity -= $q;
		if ($cartObject[$i]->quantity <= 0) {
			unset($cartObject[$i]);
		}
		\Session::put('cart_preview', $cartObject);
		echo $remains;
	}

	public function updateCart() {
		$i = $this->POST('i', 'i');
        $q = $this->POST('q', 'i');
        $p = $this->POST('p', 'i');

		$cart = \Session::get('cart_preview');
		$quantity = $cart[$i]->quantity;
		$dQ = abs($q - $quantity);
		if($q > $quantity) {
			return $this->addToCart($i, $dQ, $p, false, true);
		} else if ($quantity > $q) {
			return $this->removeFromCart($i, $dQ, $p);
		} else {
			echo $q;
		}
	}

	public function cartSubtotal($function=false)
    {
        if (isset($this->user)) {
			$args['cart`.`buyer_id'] = $this->user;
		} else {
			$args['cart`.`session_id'] = $this->session;
		}

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
		if ($function) {
			return ['price'=>$price, 'quantity'=>$quantity, 'shipping'=>$shipping];
		} // else
        return $price ."|". $quantity ."|". $shipping;
    }

	public function previewOrder()
	{
		if (isset($this->user)) {
			$args = ['user_id'=>$this->user];
			$data['addresses'] = $this->BuyModel->selectAddresses($args);
			if (empty($data['addresses'])) {
				return view('order/addressForm');
			}
		} else {
			return redirect('cart')->with('alert', 'Please login before proceeding to checkout');
		}
		$data['cart'] = $this->BuyModel->cartDetails(['buyer_id'=>$this->user]);

		$data['addresses'][0]->active = 1;
		$subtotal = $this->cartSubtotal(true);
		$subtotal['total'] = $subtotal['price'] + $subtotal['shipping'];
		$data['subs'] = $subtotal;
		return view('buy/checkout')->withData($data);
	}

	public function processAddress()
	{
		if (!isset($this->user)) {
			\Session::flash('alert', 'Please login before adding an address');
			return view('order/addressForm');
		}
		if ($this->POST('agreement', 'i') != 1) {
			\Session::flash('alert', 'You must accept the user agreement to continue');
			return view('order/addressForm');
		}
		$address['title_str'] = $this->POST('title', 'w');
		$address['street1_str'] = $this->POST('street1', 'w');
		$address['street2_str'] = $this->POST('street2', 'w');
		$address['city_str'] = $this->POST('city', 'w');
		$address['state_str'] = $this->POST('state', 'w');
		$address['country_str'] = $this->POST('country', 'w');
		$address['zipcode_int'] = $this->POST('zipcode', 'i');
		$address['phone_int'] = $this->POST('phone', 'i');
		$this->BuyModel->insertAddress($address, $this->user);
		return redirect('home');
	}

	private function sessionCartToUserCart()
	{
		$cart = $this->BuyModel->cartPreview(['cart`.`session_id'=>$this->session]);
		foreach ($cart as $item) {
			// not efficient with sql
			$item->session_id = $this->session;
			$this->BuyModel->removeFromCart((array)$item);
			$item->buyer_id = $this->user;
			unset($item->session_id);
			$this->BuyModel->addToCart((array)$item);
		}
		// update session cart
		$shoppingCart = $this->BuyModel->cartPreview(['buyer_id'=>$this->user]);
		$cart = array();
		foreach ($shoppingCart as $item) {
			$cart[$item->inventory_id] = $item;
		}
		\Session::put('cart_preview', $cart);
	}

	private function clearSessions()
	{
		// cron task
		// remove users & sessions > 1 day old from cart
		// add back to inventory
		// delete cart items with quantity = 0
	}

}

?>
