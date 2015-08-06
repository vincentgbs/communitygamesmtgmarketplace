<?php namespace App\Http\Controllers;

use App\Http\Controllers\Auth;

use App\Http\Models\UserModel;
use App\Http\Models\OrderModel;
use App\Http\Models\SellModel;
use App\Http\Models\BuyModel;

class OrderController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
		parent::__construct();
		
        $this->OrderModel = new OrderModel();
	}

    public function orders()
    {
        $args = ['buyer_id' => $this->user,
				'status'=>'approved']; // payment status approved
        $data['orders'] = $this->OrderModel->orderPreview($args);
        return view('order/orders')->withData($data);
    }

    public function orderDetails()
    {
        $order_id = $this->GET('id');
        $args = ['items`.`order_id' => $order_id,
                'buyer_id' => $this->user];
        $data['order'] = $this->OrderModel->OrderDetails($args);
        return view('order/orderdetail')->withData($data);
    }

	public function feedback()
	{
		$order = ['order`.`buyer_id' => $this->user];
		$order['order`.`order_id'] = $this->GET('oid', 'a');
		$data['seller_id'] = $this->GET('sid', 'i');
		$order = $this->OrderModel->selectFeedback($order, $data['seller_id']);
		if (isset($order[0]->order_id)) {
			$data['order'] = $order[0];
			if (isset($order[0]->seller_id)) {
				return view('order/feedback')->withData($data);
			}
			return view('order/feedbackForm')->withData($data);
		}
		return redirect('orders')->with('alert', 'Invalid order number');
	}

	public function addFeedback()
	{
		$feedback = ['buyer_id' => $this->user];
		$feedback['seller_id'] = $this->POST('seller_id', 'i');
		$feedback['order_id'] = $this->POST('order_id', 'a');
		$feedback['feedback_amt'] = $this->POST('feedback_amt', 'i');
		$feedback['feedback_str'] = $this->POST('feedback_str');
		$feedback['issue'] = $this->POST('issue', 'i');
		// insert checks for valid seller and order combination
		$this->OrderModel->insertFeedback($feedback);
		return redirect('orders');
	}

}
?>
