<?php namespace App\Http\Controllers;

use App\Http\Controllers\Auth;
use App\Http\Models\UserModel;
use App\Http\Models\OrderModel;
use App\Http\Models\SellModel;
use App\Http\Models\BuyModel;

class SellController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
		parent::__construct();

        $this->SellModel = new SellModel();
    }

    public function allInventory()
    {
        if (in_array('seller', $this->permissions)) {
            return redirect('mtg/sell'); // temporary
        } else {
            return view('sell/registerForm');
        }
    }

    public function updateInventory($item, $update)
    {
        $check = $this->SellModel->selectInventory($item)[0];
        if (isset($check->inventory_id)) {
            unset($item['seller_id']);
            $check = $this->SellModel->selectInCart($item)[0];
            if ($check->quantity > 0) {
                $update['quantity'] -= $check->quantity;
                if ($update['quantity'] < 0) {
                    echo "Cannot update quantity in buyer carts<hr>";
                }
            }
            $this->SellModel->updateInventory($update);
            return true;
        }
        return false;
    }

    public function deleteInventory()
    {
        $item['inv`.`inventory_id'] = $this->POST('inventory', 'i');
        $item['seller_id'] = $this->user;
        $this->SellModel->deleteInventory($item);
    }

    public function mtgInventory()
    {
        if (in_array('seller', $this->permissions)) {
            $search = $this->GET('search', 'w');
            if (isset($search) && $search != '') {
                $args = ['name_str'=>['LIKE', $search.'%']];
            }
            $args['inv`.`seller_id'] =$this->user;
            $data['inventory'] = $this->SellModel->selectMtgInventory($args);
            return view('mtg/sell/page')->withData($data);
        } else {
            return view('sell/registerForm');
        }
    }

    public function updateMtgInventory()
    {
        if (in_array('seller', $this->permissions)) {
            $item['inventory_id'] = $args['inventory_id'] = $this->POST('inventory', 'i');
            $item['seller_id'] = $args['seller_id'] = $this->user;
            $item['quantity'] = $this->POST('quantity', 'i');
            $item['price'] = $this->POST('price', 'i');
            if (preg_match('/\d+/', $item['inventory_id'])) {
                $this->updateInventory($args, $item);
            } else {
                unset($item['inventory_id']); // shouldn't matter
                $item['market_id'] = 1;
                $item['special_id'] = $this->POST('special', 'i');
                $item['condition_id'] = $this->POST('condition', 'i');
                $item['card_id'] = $this->POST('card', 'i');
                $item['seller_id'] = $this->user;
                $this->SellModel->addMtgToInventory($item);
            }
            $inventory = $this->SellModel->selectMtgInventory(['inv`.`seller_id'=>$this->user]);
            foreach ($inventory as $card) {
                echo view('mtg/sell/item')->withCard($card);
            }
        }
    }

    public function orderDetails()
    {
        if (in_array('seller', $this->permissions)) {
            if (isset($_GET['id'])) {
                $order['items`.`order_id'] = $this->GET('id');
                $order['seller_id'] = $this->user;
                // $order['status'] = 'approved';
                $data['orders'] = $this->SellModel->orderDetails($order);
                return view('sell/order/page')->withData($data);
            } else {
                $orders = ['inv`.`seller_id' => $this->user];
                $data['orders'] = $this->SellModel->orderPreview($orders);
                return view('sell/order/preview/page')->withData($data);
            }
        } else {
            return view('sell/registerForm');
        }
    }

    public function itemShipped()
    {
        if (in_array('seller', $this->permissions)) {
            $item['inventory_id'] = $this->POST('inventory');
            $item['seller_id'] = $this->user;
            $check = $this->SellModel->selectInventory($item)[0];
            unset($item['seller_id']);
            if ($check->seller_id == $this->user) {
                $item['order_id'] = $this->POST('order');
                $update['shipped'] = $this->POST('shipped');
                $this->SellModel->updateShipped($item, $update);
                $view['status'] = 'updated';
                if ($update['shipped'] == 0) {
                    $view['update'] = "Not Shipped";
                    $view['time'] = 1000;
                } else if ($update['shipped'] == 1) {
                    $view['update'] = "Shipped";
                    $view['time'] = 5000;
                }
                echo json_encode($view);
                if (false) { // if order status is 'complete', set payment approval date
                    $payout = ['seller_id'=>$this->user, 'approved'=>1, 'payout_amt'=>0];
                    $this->SellModel->createPayout($payout);
                }
            }
        }
    }

    private function paySellers()
    {
        // cron task
        // select sum based on "approval dates" for sellers - delayed by 2 wks
    }


}
?>
