<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model {

    public function orderPreview($args, $ord="`date` ASC", $lim=50, $comp="=")
    {
        $q = "SELECT `order`.`order_id`, `date`,
        `total`, `currency`,
        `email`, `line1`, `city`, `state`
        FROM `ord_ls_orders` AS `order`
        JOIN `ord_ls_payment` AS `payment` ON `order`.`order_id`=`payment`.`order_id`
        WHERE ";
        foreach ($args as $k => $v) {
            if (is_array($v)) {
                $q .= " `$k` ".$v[0]." '".$v[1]."' AND ";
            } else {
                $q .= " `$k` $comp '$v' AND ";
            }
        }
        $q = substr($q, 0, -4) . " ORDER BY $ord LIMIT $lim;";
        return \DB::select($q);
    }

    public function orderDetails($args)
    {
        $select = "`market_str`, `name_str`,
        `mtg_inv`.`condition_id`, `condition_str`,
        `mtg_inv`.`special_id`, `special_str`,
        `mtg_inv`.`card_id`, `inv`.`inventory_id`,
        `mtg_card`.`set_id`, `set_str`,
        `seller_str`, `seller_id`,
        `items`.`quantity`, `items`.`price`,
        `order`.`order_id`, `shipping_amt`,
        `tracking_str`";
        $cart['mtg_card'] = $this->mtg_cardOrder($select, $args);
        // $cart['pkmn_card'] = $this->pkmn_cardOrder($select, $args);
        return $cart;
    }

    public function selectFeedback($args, $seller=false, $comp="=")
    {
        $q = "SELECT `order`.`order_id`, `seller_id`, `seller_str`, `feedback_str`, `feedback_amt`
        FROM `ord_ls_orders` AS `order`
        LEFT JOIN `ord_ls_feedback` AS `feedback` ON `order`.`order_id`=`feedback`.`order_id`
        LEFT JOIN `site_ls_sellers` AS `seller` ON `feedback`.`seller_id`=`seller`.`user_id`
        WHERE ";
        foreach ($args as $k => $v) {
            if (is_array($v)) {
                $q .= " `$k` ".$v[0]." '".$v[1]."' AND ";
            } else {
                $q .= " `$k` $comp '$v' AND ";
            }
        }
        if ($seller) {
            $q .= "(`seller_id` = $seller OR `seller_id` IS NULL);";
        } else {
            $q = substr($q, 0, -4) . ";";
        }
        return \DB::select($q);
    }

    public function insertFeedback($feedback)
    {
        $q = "INSERT INTO `ord_ls_feedback`
        (`buyer_id`, `seller_id`, `order_id`, `feedback_amt`, `feedback_str`, `issue`)
        VALUES (".$feedback['buyer_id'].",
        (SELECT `seller_id` FROM `ord_ls_orderitems` AS `items`
            JOIN `site_rel_inventory` AS `inv` ON `items`.`inventory_id`=`inv`.`inventory_id`
            WHERE `inv`.`seller_id`=".$feedback['seller_id']."
            AND `items`.`order_id`='".$feedback['order_id']."' LIMIT 1),
        '".$feedback['order_id']."', ".$feedback['feedback_amt'].", '".$feedback['feedback_str']."', ".$feedback['issue'].")
        ON DUPLICATE KEY UPDATE `feedback_amt`=".$feedback['feedback_amt'].",
        `feedback_str`='".$feedback['feedback_str']."';";
        return \DB::insert($q);
    }

    public function feedbackScore($args)
    {
        $q = "SELECT `seller_str`, `feedback`.`seller_id`, AVG(`feedback_amt`) AS `feedback_score`
        FROM `ord_ls_feedback` AS `feedback`
        JOIN `site_ls_sellers` AS `seller` ON `seller`.`user_id`=`feedback`.`seller_id`
        WHERE ";
        foreach ($args as $k => $v) {
            if (is_array($v)) {
                $q .= " `$k` ".$v[0]." '".$v[1]."' AND ";
            } else {
                $q .= " `$k` = '$v' AND ";
            }
        }
        $q = substr($q, 0, -4) . ";";
        return \DB::select($q);
    }

    /*
    // MARKET SPECIFIC QUERIES
    */

    private function mtg_cardOrder($select, $args, $ord="`name_str` ASC", $lim=50, $comp="=", $hide=true)
    {
        $q = "SELECT $select FROM `ord_ls_orderitems` AS `items`
        JOIN `ord_ls_orders` AS `order` ON `items`.`order_id`=`order`.`order_id`
        LEFT JOIN `ord_ls_shipments` AS `shipment` ON `order`.`order_id`=`shipment`.`order_id`
        JOIN `site_rel_inventory` AS `inv` ON `items`.`inventory_id`=`inv`.`inventory_id`
        JOIN `site_ls_markets` AS `market` ON `inv`.`market_id`=`market`.`market_id`
        JOIN `site_ls_sellers` AS `seller` ON `inv`.`seller_id`=`seller`.`user_id`
        JOIN `mtg_rel_inventory` AS `mtg_inv`
            ON `inv`.`item_id`=`mtg_inv`.`item_id`
            AND `inv`.`market_id`=`mtg_inv`.`market_id`
        JOIN `mtg_rel_cards` AS `mtg_card` ON `mtg_card`.`card_id`=`mtg_inv`.`card_id`
        JOIN `mtg_ls_names` AS `mtg_name` ON `mtg_name`.`name_id`=`mtg_card`.`name_id`
        JOIN `mtg_ls_sets` AS `mtg_set` ON `mtg_set`.`set_id`=`mtg_card`.`set_id`
        JOIN `card_ls_conditions` AS `condition` ON `condition`.`condition_id`=`mtg_inv`.`condition_id`
        JOIN `card_ls_specials` AS `special` ON `special`.`special_id`=`mtg_inv`.`special_id`
        WHERE ";
        foreach ($args as $k => $v) {
            if (is_array($v)) {
                $q .= " `$k` ".$v[0]." '".$v[1]."' AND ";
            } else {
                $q .= " `$k` $comp '$v' AND ";
            }
        }
        $q = substr($q, 0, -4) . " ORDER BY $ord LIMIT $lim;";
        return \DB::select($q);
    }

    private function pkmn_cardOrder($select, $args)
    {
        //
    }

}
?>
