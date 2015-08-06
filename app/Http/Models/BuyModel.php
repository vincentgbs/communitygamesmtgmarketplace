<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class BuyModel extends Model {

    public function cartPreview($args)
    {
        $select = "`inv`.`inventory_id`, `name_str`, `cart`.`price`, `cart`.`quantity`";
        $mtgCart = $this->mtg_cardCart($select, $args);
        $pkmnCart = $this->pkmn_cardCart($select, $args);
        $cart = array_merge($mtgCart, $pkmnCart);
        return $cart;
    }

    public function cartDetails($args)
    {
        $select = "`market_str`, `name_str`,
        `mtg_inv`.`condition_id`, `condition_str`,
        `mtg_inv`.`special_id`, `special_str`,
        `mtg_inv`.`card_id`, `inv`.`inventory_id`,
        `mtg_card`.`set_id`, `set_str`, `seller_str`,
        `cart`.`quantity`, `cart`.`price`";
        $cart['mtg_card'] = $this->mtg_cardCart($select, $args);
        return $cart;
    }

    public function selectInventory($args, $hide=true, $comp="=", $ord="`inv`.`price` ASC", $lim=50)
    {
        $q = "SELECT * FROM `site_rel_inventory` AS `inv`
        LEFT JOIN `site_ls_sellers` AS `seller` ON `inv`.`seller_id`=`seller`.`user_id`
        LEFT JOIN `site_ls_markets` AS `market` ON `inv`.`market_id`=`market`.`market_id`
        WHERE ";
        foreach ($args as $k => $v) {
            if (is_array($v)) {
                $q .= " `$k` ".$v[0]." '".$v[1]."' AND ";
            } else {
                $q .= " `$k` $comp '$v' AND ";
            }
        }
        if ($hide) {
            $q .= " (`inv`.`quantity` > 0 OR `inv`.`quantity` IS NULL);";
        } else {
            $q = substr($q, 0, -4);
        }
        return \DB::select($q);
    }

    public function addToCart($item)
    {
        $q = "INSERT INTO `site_rel_cart` (`inventory_id`, `buyer_id`, `session_id`, `price`, `quantity`)
            VALUES (".$item['inventory_id'].", ".(isset($item['buyer_id'])?"'".$item['buyer_id']."'":'NULL').",
                ".(isset($item['session_id'])?"'".$item['session_id']."'":'NULL').", ".$item['price'].", ".$item['quantity'].")
            ON DUPLICATE KEY UPDATE `quantity`=(`quantity`+".$item['quantity']."), `price`=".$item['price'].";";
        return \DB::statement($q);
    }

    public function removeFromInventory($inventoryId, $quantity)
    {
        $q = "UPDATE `site_rel_inventory`
        SET `quantity`=(`quantity`-".$quantity.")
        WHERE `inventory_id`=".$inventoryId.";";
        return \DB::update($q);
    }

    public function removeFromCart($item)
    {
        $q = "UPDATE `site_rel_cart`
        SET `quantity`=(`quantity`-".$item['quantity'].")
        WHERE `inventory_id`=".$item['inventory_id']."
        AND (`buyer_id`=".(isset($item['buyer_id'])?"'".$item['buyer_id']."'":'0')."
        OR `session_id`=".(isset($item['session_id'])?"'".$item['session_id']."'":'NULL').");";
        return \DB::update($q);
    }

    public function addToInventory($inventoryId, $quantity)
    {
        $q = "UPDATE `site_rel_inventory`
        SET `quantity`=(`quantity`+".$quantity.")
        WHERE `inventory_id`=".$inventoryId.";";
        return \DB::update($q);
    }

    public function selectAddresses($args, $ord='timestamp ASC', $lim=3)
    {
        $q = "SELECT * FROM `site_rel_addresses` AS `site`
        JOIN `ord_ls_addresses` AS `ord` ON `site`.`address_id`=`ord`.`address_id`
        WHERE ";
        foreach ($args as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4);
        $q .= " ORDER BY $ord LIMIT $lim";
        return \DB::select($q);
    }

    public function insertAddress($address, $user)
    {
        $q = "INSERT INTO `ord_ls_addresses` (`title_str`, `street1_str`, `street2_str`,
            `city_str`, `state_str`, `country_str`, `zipcode_int`, `phone_int`)
            VALUES ('".$address['title_str']."', '".$address['street1_str']."', '".$address['street2_str']."',
                '".$address['city_str']."', '".$address['state_str']."', '".$address['country_str']."', '".$address['zipcode_int']."', '".$address['phone_int']."');";
        \DB::insert($q);
        $q = "INSERT INTO `site_rel_addresses` (`user_id`, `address_id`)
            VALUES ($user, (SELECT LAST_INSERT_ID()));";
        return \DB::insert($q);
    }

    public function addToPreorder($args)
    {
        $q = "UPDATE `site_rel_cart`
        SET `checkout`=1
        WHERE ";
        foreach ($args as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . ";";
        return \DB::update($q);
    }

    public function removeFromPreorder($args)
    {
        $q = "UPDATE `site_rel_cart`
        SET `checkout`=0
        WHERE ";
        foreach ($args as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . ";";
        return \DB::update($q);
    }

    public function insertOrder($order)
    {
        $q = "INSERT INTO `ord_ls_orders` (`order_id`, `buyer_id`, `address_id`, `speed_id`, `insurance`, `shipping_amt`)
        VALUES ('".$order['order_id']."', '".$order['buyer_id']."', '".$order['address_id']."',
            '".$order['speed_id']."', '".$order['insurance']."', '".$order['shipping_amt']."')
            ON DUPLICATE KEY UPDATE `address_id`='".$order['address_id']."',
                `speed_id`='".$order['speed_id']."', `insurance`='".$order['insurance']."'";
        return \DB::statement($q);
    }

    public function cartSubtotals($args)
    {
        $q = "SELECT `seller_id`, `category_id`,
        SUM(`cart`.`quantity`) AS `seller_quantity`,
        SUM(`cart`.`price`*`cart`.`quantity`) AS `total`
        FROM `site_rel_cart` AS `cart`
        JOIN `site_rel_inventory` AS `inv` ON `cart`.`inventory_id`=`inv`.`inventory_id`
        JOIN `site_ls_markets` AS `market` ON `inv`.`market_id`=`market`.`market_id`
        WHERE ";
        foreach ($args as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . " GROUP BY `seller_id`, `category_id`;";
        return \DB::select($q);
    }

    public function cardShippingEstimate($quantity)
    {
        $q = "SELECT * FROM `card_ls_shipping` WHERE
        `min_quantity`<= $quantity AND `max_quantity` >= $quantity;";
        return \DB::select($q);
    }

    public function insertPayment($payment)
    {
        $q = "INSERT INTO `ord_ls_payment` (`payment_id`, `status`, `create_time`, `email`, `total`, `currency`,
             `line1`, `city`, `state`, `postal_code`, `country_code`, `order_id`)
        VALUES ('".$payment['payment_id']."', '".$payment['status']."', '".$payment['create_time']."',
            '".$payment['email']."', '".$payment['total']."', '".$payment['currency']."',
            '".$payment['line1']."', '".$payment['city']."', '".$payment['state']."',
            '".$payment['postal_code']."', '".$payment['country_code']."', '".$payment['order_id']."');";
        return \DB::insert($q);
    }

    public function deleteOrderId($orderId)
    {
        $q = "DELETE FROM `ord_ls_orders` WHERE `order_id`='$orderId';";
        return \DB::select($q);
    }

    public function insertOrderItems($update, $args)
    {
        $q = "INSERT INTO `ord_ls_orderitems` (`order_id`, `inventory_id`, `price`, `quantity`)
        SELECT '".$update['order_id']."', `inventory_id`, `price`, `quantity` FROM `site_rel_cart` WHERE ";
        foreach ($args as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . ";";
        return \DB::insert($q);
    }

    public function deleteFromCart($args)
    {
        $q = "DELETE FROM `site_rel_cart` WHERE ";
        foreach ($args as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . ";";
        return \DB::delete($q);
    }

    public function uniqueCode($table, $column)
    {
        do { $code = uniqid() . rand(1000,9999);
            $q = "SELECT * FROM `$table`
                WHERE `$column` = '" . $code ."';";
            $row = \DB::select($q);
        } while (count($row) != 0);
        return $code;
    }

    /*
    // MARKET SPECIFIC QUERIES
    */

    private function mtg_cardCart($select, $args, $ord="`name_str` ASC", $lim=50, $comp="=", $hide=true)
    {
        $q = "SELECT $select
        FROM `site_rel_cart` AS `cart`
        JOIN `site_rel_inventory` AS `inv` ON `cart`.`inventory_id`=`inv`.`inventory_id`
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
        if ($hide) {
            $q .= " `cart`.`quantity` > 0";
        } else {
            $q = substr($q, 0, -4);
        }
        $q .= " ORDER BY $ord LIMIT $lim;";
        return \DB::select($q);
    }

    private function pkmn_cardCart($select, $args)
    {
        return [];
    }

}
?>
