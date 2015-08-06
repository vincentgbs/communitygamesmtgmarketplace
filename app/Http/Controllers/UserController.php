<?php namespace App\Http\Controllers;

use App\Http\Controllers\Auth;
use App\Http\Models\UserModel;
use App\Http\Models\OrderModel;
use App\Http\Models\SellModel;
use App\Http\Models\BuyModel;

class UserController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
		parent::__construct();

        $this->UserModel = new UserModel();
    }

    public function userIds()
    {
        return json_encode($this->UserModel->userIds());
    }

    public function processSellerRegistration()
    {
        $this->SellModel = new SellModel();
        $seller['seller_str'] = $this->POST('seller', 'a');
        $seller['email_str'] = $this->POST('email', 'e');
        $seller['method_id'] = $this->POST('method', 'i');
        $seller['cycle_id'] = $this->POST('cycle', 'i');
        $seller['user_id'] = $this->user;
        $check = $this->SellModel->addSeller($seller);
        if (isset($check['duplicate'])) {
            return redirect('sell')->with('alert',
                'This seller name is already registered, please choose a new one.');
        }
        echo "<h2>Thank you for registering</h2>
        Because our seller features are currently in Beta, our seller approval process requires individual approval
        <a href='/home'>Return to site</a>";
        // $seller = 2;
        // $this->UserModel->addUserToGroup($this->user, $seller);
    }

}
?>
