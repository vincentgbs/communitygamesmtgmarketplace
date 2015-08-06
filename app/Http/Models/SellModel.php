<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SellModel extends Model {

    public function addSeller($seller)
    {
        $q = "SELECT `seller_str` FROM `site_ls_sellers`
        WHERE `seller_str`='".$seller['seller_str']."';";
        $check = \DB::select($q);
        if (isset($check[0]->seller_str)) {
            return ['duplicate'=>'set'];
        }

        $q = "INSERT INTO `site_ls_sellers` (`user_id`, `seller_str`, `method_id`, `email_str`, `cycle_id`)
        VALUES (".$seller['user_id'].", '".$seller['seller_str']."', ".$seller['method_id'].",
        '".$seller['email_str']."', ".$seller['cycle_id'].")
        ON DUPLICATE KEY UPDATE `seller_str`='".$seller['seller_str']."';";
        return \DB::insert($q);
    }

    public function selectInventory($args)
    {
        $q = "SELECT `inventory_id`, `quantity`, `price`, `market_id`, `seller_id`
        FROM `site_rel_inventory` AS `inv`
        WHERE ";
        foreach ($args as $k => $v) {
            $q .= "`$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . ";";
        return \DB::select($q);
    }

    public function selectInCart($args)
    {
        $q = "SELECT `inventory_id`, SUM(`quantity`) AS `quantity`, AVG(`price`) AS `price`
        FROM `site_rel_cart` WHERE ";
        foreach ($args as $k => $v) {
            $q .= "`$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . ";";
        return \DB::select($q);
    }

    public function updateInventory($item)
    {
        $q = "UPDATE `site_rel_inventory`
        SET `quantity`=".$item['quantity'].",
        `price`=".$item['price']."
        WHERE `inventory_id`=".$item['inventory_id']."
        AND `seller_id`=".$item['seller_id'].";";
        return \DB::update($q);
    }

    public function deleteInventory($args)
    {
        $q = "UPDATE `site_rel_inventory` AS `inv`
        SET `inv`.`quantity`=0, `timestamp`='2015-01-01 00:00:00' WHERE ";
        foreach ($args as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . ";";
        \DB::update($q);

        $q = "UPDATE `site_rel_cart` AS `cart`
        JOIN `site_rel_inventory` AS `inv` ON `cart`.`inventory_id`=`inv`.`inventory_id`
        SET `cart`.`quantity`=0 WHERE ";
        foreach ($args as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . ";";
        return \DB::update($q);
    }

    public function updateShipped($orderitem, $update)
    {
        $q = "UPDATE `ord_ls_orderitems` SET ";
        foreach ($update as $k => $v) {
            $q .= " `$k` = '$v', ";
        }
        $q = substr($q, 0, -2) . " WHERE ";
        foreach ($orderitem as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . ";";
        return \DB::update($q);
    }

    public function orderPreview($args, $group='`items`.`order_id`')
    {
        $q = $q = "SELECT `items`.`order_id`, SUM(`items`.`price`*`items`.`quantity`) AS `subtotal`
        FROM `ord_ls_orderitems` AS `items`
        JOIN `ord_ls_payment` AS `payment` ON `payment`.`order_id`=`items`.`order_id`
        JOIN `site_rel_inventory` AS `inv` ON `inv`.`inventory_id`=`items`.`inventory_id`
        WHERE ";
        foreach ($args as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . "GROUP BY $group;";
        return \DB::select($q);
    }

    public function orderDetails($order)
    {
        $order['mtg'] = $this->orderMtgDetails($order);
        return $order;
    }

    public function addTracking($track)
    {
        $q = "INSERT INTO `ord_ls_shipments` (`company_str`, `tracking_str`, `order_id`)
        VALUES ('".$track['company_str']."', '".$track['tracking_str']."', '".$track['order_id']."');";
        return \DB::insert($q);
    }

    public function createPayout($payout)
    {
        $q = "INSERT INTO `ord_ls_payouts` (`seller_id`, `payout_amt`, `approved`)
        VALUES (".$payout['seller_id'].", ".$payout['payout_amt'].", ".$payout['approved'].");";
        return \DB::insert($q);
    }

    /*
    // MARKET SPECIFIC QUERIES
    */

    public function selectMtgInventory($args, $lim=20, $ord="`inv`.`timestamp` DESC", $comp="=")
    {
        // THIS QUERY MAY STILL CAUSE PROBLEMS if it needs adjusting
        $q = "SELECT `inv`.`inventory_id`, `inv`.`seller_id`, `card`.`card_id`, `card`.`set_id`,
        `seller_str`, `name_str`, `set_str`, `mtg_inv`.`condition_id`, `condition_str`,
        `mtg_inv`.`special_id`, `special_str`, `inv`.`price`,
        (`inv`.`quantity` + (SELECT IFNULL(SUM(`quantity`), 0) FROM `site_rel_cart` WHERE `inventory_id`=`inv`.`inventory_id`)) AS `total_quantity`
        FROM `site_rel_inventory` AS `inv`
        LEFT JOIN `site_rel_cart` AS `cart` ON `inv`.`inventory_id`=`cart`.`inventory_id`
        JOIN `mtg_rel_inventory` AS `mtg_inv` ON `inv`.`item_id`=`mtg_inv`.`item_id`
            AND `inv`.`market_id`=`mtg_inv`.`market_id`
        JOIN `mtg_rel_cards` AS `card` ON `mtg_inv`.`card_id`=`card`.`card_id`
        LEFT JOIN `site_ls_sellers` AS `seller` ON `inv`.`seller_id`=`seller`.`user_id`
        LEFT JOIN `card_ls_specials` AS `special` ON `mtg_inv`.`special_id`=`special`.`special_id`
        LEFT JOIN `card_ls_conditions` AS `condition` ON `mtg_inv`.`condition_id`=`condition`.`condition_id`
        JOIN `mtg_ls_sets` AS `set` ON `set`.`set_id`=`card`.`set_id`
        JOIN `mtg_ls_names` AS `name` ON `name`.`name_id`=`card`.`name_id`
        WHERE ";
        foreach ($args as $k => $v) {
            if (is_array($v)) {
                $q .= " `$k` ".$v[0]." '".$v[1]."' AND ";
            } else {
                $q .= " `$k` $comp '$v' AND ";
            }
        }
        $q = substr($q, 0, -4) . "GROUP BY `inv`.`inventory_id` ORDER BY $ord LIMIT $lim;";
        return \DB::select($q);
    }

    public function addMtgToInventory($item)
    {
        $q = "INSERT IGNORE INTO `mtg_rel_inventory` (`condition_id`, `special_id`, `card_id`)
        VALUES (".$item['condition_id'].", ".$item['special_id'].", ".$item['card_id'].");";
        \DB::insert($q);

        $q = "SELECT `item_id` FROM `mtg_rel_inventory`
        WHERE `condition_id`=".$item['condition_id']."
        AND `special_id`=".$item['special_id']."
        AND `card_id`=".$item['card_id'].";";
        $card = \DB::select($q)[0];

        $q = "INSERT INTO `site_rel_inventory` (`market_id`, `item_id`, `seller_id`, `price`, `quantity`)
        VALUES (".$item['market_id'].", ".$card->item_id.", ".$item['seller_id'].",
        ".$item['price'].", ".$item['quantity'].")
        ON DUPLICATE KEY UPDATE `price`=".$item['price'].", `quantity`=".$item['quantity'].";";
        return \DB::statement($q);
    }

    private function orderMtgDetails($order)
    {
        $q = "SELECT `items`.`orderitem_id`, `items`.`order_id`, `items`.`inventory_id`,
        `items`.`price`, `items`.`quantity`, `shipped`, `name_str`, `set_str`, `condition_str`, `special_id`
        FROM `ord_ls_orderitems` AS `items`
        JOIN `ord_ls_payment` AS `payment` ON `payment`.`order_id`=`items`.`order_id`
        JOIN `site_rel_inventory` AS `inv` ON `inv`.`inventory_id`=`items`.`inventory_id`
        LEFT JOIN `ord_rel_shipments` AS `shipment` ON `shipment`.`orderitem_id`=`items`.`orderitem_id`
        JOIN `mtg_rel_inventory` AS `mtg_inv` ON `inv`.`item_id`=`mtg_inv`.`item_id`
        JOIN `card_ls_conditions` AS `condition` ON `mtg_inv`.`condition_id`=`condition`.`condition_id`
        JOIN `mtg_rel_cards` AS `card` ON `mtg_inv`.`card_id`=`card`.`card_id`
        LEFT JOIN `mtg_ls_names` AS `name` ON `card`.`name_id`=`name`.`name_id`
        LEFT JOIN `mtg_ls_sets` AS `set` ON `card`.`set_id`=`set`.`set_id`
        WHERE ";
        foreach ($order as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . ";";
        return \DB::select($q);
    }
}
?>
