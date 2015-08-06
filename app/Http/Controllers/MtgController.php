<?php namespace App\Http\Controllers;

use App\Http\Controllers\Auth;

use App\Http\Models\UserModel;
use App\Http\Models\OrderModel;
use App\Http\Models\SellModel;
use App\Http\Models\BuyModel;
use App\Http\Models\BlogModel;
use App\Http\Models\MtgModel;

class MtgController extends Controller {

    public function __construct()
    {
        parent::__construct();

        $this->MtgModel = new MtgModel();
        $this->market = 1; // hard coded
    }

    public function index()
    {
        return view('mtg/index');
    }

    public function names()
    {
        $search = $this->POST('search');
        $names = $this->MtgModel->selectNames($search.'%');
        if (!isset($names[0]->name_str)) {
            return json_encode(['No cards found']);
        } else {
            foreach ($names as $name) {
                $response[] = [$name->name_str][0]; // what's with the [0] index?
            }
            echo json_encode($response);
        }
    }

    public function cardIds()
    {
        $search = $this->POST('search');
        $cards = $this->MtgModel->selectCards(['name_str'=>['LIKE', $search.'%']]);

        if (!isset($cards[0]->card_id)) {
            return json_encode([['name'=>'No cards found',
                    'set'=>'',
                    'id'=>0]]);
        } else {
            foreach ($cards as $card) {
                $response[] = ['name'=>$card->name_str,
                            'set'=>$card->set_str,
                            'card_id'=>$card->card_id];
            }
            echo json_encode($response);
        }
    }

    public function cards($id=false)
    {
        if ($id) {
            $data['cards'] = $this->MtgModel->selectInventory(['card`.`card_id'=>$id], '`price` ASC');
            $data['card'] = $data['cards'][0];
            foreach ($data['cards'] as $i => $card) {
                if ($card->quantity == 0) {
                    unset($data['cards'][$i]);
                }
            }
            $this->ebayPrice($id);
            return view('mtg/buy/page')->withData($data);
        }
        $search = $this->POST('search');
        $data['search'] = ['key'=>'card', 'value'=>$search];
        $date = date("Y-m-d H:i:s", strtotime("-1 week"));
        $data['cards'] = $this->MtgModel
            ->selectCards(['name_str'=>['LIKE', $search.'%']], $date,
            '`set`.`date` DESC, `name_str` ASC');
        return view('mtg/list/page')->withData($data);
    }

    public function sets($id=false)
    {
        if ($id === false) {
            $sort = $this->GET('sort');
            $orders = ['nameup'=>'`set_str` ASC', 'namedown'=>'`set_str` DESC',
                    'dateup'=>'`date` ASC', 'datedown'=>'`date` DESC'];
            if (array_key_exists($sort, $orders)) {
                $data['sets'] = $this->MtgModel->viewSets($orders[$sort]);
            } else {
                $data['sets'] = $this->MtgModel->viewSets();
            }
            return view('mtg/sets/page')->withData($data);
        }
        $data['search'] = ['key'=>'set', 'value'=>$id];
        $date = date("Y-m-d H:i:s", strtotime("-1 week"));
        $data['cards'] = $this->MtgModel->selectCards(['card`.`set_id'=>$id], $date);
        return view('mtg/list/page')->withData($data);
    }

    public function sellers($id=false)
    {
        $this->OrderModel = new OrderModel();
        if ($id === false) {
            $id = 1; // default id
        }
        $data['search'] = ['key'=>'seller', 'value'=>$id];
        $data['cards'] = $this->MtgModel->selectInventory(['inv`.`market_id'=>$this->market,
            'seller_id'=>$id], '`set`.`date` DESC, `name_str` ASC', 25, '=', true);
        $data['seller'] = $this->OrderModel->feedbackScore(['seller_id'=>$id])[0];
        return view('mtg/list/seller/page')->withData($data);
    }

    public function nextCards()
    {
        $start = $this->POST('start', 'i');
        if ($start < 0 || $start >= 900) {
            return; // arbitrary 900 card limit
        }
        $term = $this->POST('key');
        $search = $this->POST('value');
        $keys = ['seller'=>['inv`.`seller_id'=>$search],
                'card'=>['name_str'=>['LIKE', $search.'%']],
                'set'=>['card`.`set_id'=>$search]];
        if (array_key_exists($term, $keys)) {
            $args = $keys[$term];
        } else {
            return; // invalid key
        }
        $date = date("Y-m-d H:i:s", strtotime("-1 week"));
        $cards = $this->MtgModel
            ->selectCards($args, $date,
            '`set`.`date` DESC, `name_str` ASC', $start.', 50');

        if (!isset($cards[0]->card_id)) {
            return; // no more results
        }
        foreach($cards as $card) {
            echo view('mtg/list/item')->withCard($card);
            echo "<hr>";
        }
        echo '<div id="last" start="'.($start+50).'" key="'.$term.'" value="'.$search.'"></div>';
        // return true;
    }

    public function buyCardPreview()
    {
        $option = $this->POST('option');
        if ($option == 'low_price') {
            $order = '`inv`.`price` ASC';
        } else if ($option == 'best_condition') {
            $order = '`mtg_inv`.`condition_id` ASC';
        } else { // default option
            $order = '`inv`.`price` ASC';
        }
        $args = ['inv`.`market_id'=>$this->market];
        $args['card`.`card_id'] = $this->POST('card');
        $cards = $this->MtgModel->selectInventory($args, $order, 5, '=', true);
        if (isset($cards[0]->inventory_id)) {
            echo "info";
            echo "<div class='row'><label class='col-md-2 name' id='name'>".$cards[0]->name_str."</label>
            <div class='col-md-2'>Condition</div>
            <div class='col-md-1'>Foil</div>
            <div class='col-md-1'>Price</div>
            <div class='col-md-1'>Quantity</div>
            <div class='col-md-1'>Green</div>
            </div>";
            foreach ($cards as $card) {
                echo view('mtg/buy/item')->withCard($card);
            }
        }
    }

    private function ebayPrice($cardId)
    {
        $args = ["card`.`card_id"=>$cardId];
        $cards = $this->MtgModel->selectCards($args,
            date("Y-m-d H:i:s", strtotime("-1 week")), "`prices`.`date` DESC");
        if (isset($cards[0]->price_est)) {
            return $cards[0]->price_est;
        } else if (isset($cards[0]->price)) {
            return $cards[0]->price;
        } else if (!isset($cards[0]->name_str)) {
            return false; // card name doesn't exist
        } // else
        $searchTerm = urlencode($cards[0]->name_str. " x4");
        $url = "http://open.api.ebay.com/shopping?version=713&appid=Communit-4310-4bf9-ba9f-c0b43022e676&callname=FindPopularItems&QueryKeywords=".$searchTerm."&ResponseEncodingType=JSON";
        $response = json_decode(file_get_contents($url));
        // echo "<pre>" . $url;
        // var_dump($response);
        // echo "</pre>"; return;
        if ($response->Ack != 'Success') {
            return 0; // no results found
        }
        $date = [31, 24, 60, 60];
        foreach($response->ItemArray->Item as $item) {
            if ($item->BidCount > 0) {
                preg_match_all('/P(\d*)DT(\d*)H(\d*)M(\d*)S/', $item->TimeLeft, $matches);
                if ((int)$matches[1] < $date[0] && (int)$matches[2] < $date[1]
                    && (int)$matches[3] < $date[2] && (int)$matches[4] < $date[3]) {
                    $date[0] = (int)$matches[1]; // days
                    $date[1] = (int)$matches[2]; // hours
                    $date[2] = (int)$matches[3]; // minutes
                    $date[3] = (int)$matches[4]; // seconds
                    $price = $item->ConvertedCurrentPrice->Value;
                    // echo "Price replaced with more recent auction: " . $price . "<br>";
                }
            }
        }
        if (isset($price)) {
            $ebay['card_id'] = $cardId;
            $ebay['source_id'] = 2;
            $ebay['price'] = $price * 25; // (100/4)
            $this->MtgModel->addPrice($ebay);
            return $price;
        } // else { return null; }
    }

    public function blogs($id=null)
	{
        $BlogModel = new BlogModel();
		if (isset($id)) {
			$data['blogs'] = $BlogModel->selectBlogs(['blog_id'=>$id]);
		} else {
			$data['blogs'] = $BlogModel->selectBlogs();
		}
		return view('blogs/blogs')->withData($data);
	}

}
?>
