<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MtgModel extends Model {

    public function selectNames($name, $lim=15)
    {
        $q = "SELECT `name_str` FROM `mtg_ls_names` WHERE `name_str` LIKE '$name' LIMIT $lim;";
        return \DB::select($q);
    }

    public function selectCards($args, $date=false,
        $ord="`name_str` ASC", $lim=50, $group="`card`.`card_id`", $comp="=")
    {
        $q = "SELECT `card`.`card_id`, `name_str`, `card`.`name_id`, `set_str`, `card`.`set_id`,
        `type_str`, `color_str`, `inv`.`quantity`, `inv`.`inventory_id`,
        MIN(`inv`.`price`) AS `price`, MIN(`prices`.`price`) AS `price_est`
        FROM `mtg_rel_cards` AS `card`
        JOIN `mtg_ls_names` AS `name` ON `name`.`name_id`=`card`.`name_id`
        JOIN `mtg_ls_sets` AS `set` ON `set`.`set_id`=`card`.`set_id`
        LEFT JOIN `mtg_ls_types` AS `type` ON `type`.`type_id`=`card`.`type_id`
        LEFT JOIN `mtg_ls_colors` AS `color` ON `color`.`color_id`=`card`.`color_id`
        LEFT JOIN `mtg_card_prices` AS `prices` ON `prices`.`card_id`=`card`.`card_id`
        LEFT JOIN `mtg_rel_inventory` AS `mtg_inv` ON `mtg_inv`.`card_id`=`card`.`card_id`
        LEFT JOIN `site_rel_inventory` AS `inv` ON `inv`.`item_id`=`mtg_inv`.`item_id` AND `inv`.`market_id`=`mtg_inv`.`market_id`
        WHERE ";
        foreach ($args as $k => $v) {
            if (is_array($v)) {
                $q .= " `$k` ".$v[0]." '".$v[1]."' AND ";
            } else {
                $q .= " `$k` $comp '$v' AND ";
            }
        }
        if ($date) {
            $q .= " (`prices`.`date` >= '$date' OR `prices`.`date` IS NULL) ";
        } else {
            $q = substr($q, 0, -4);
        }
        $q .= " GROUP BY $group ORDER BY $ord LIMIT $lim;";
        return \DB::select($q);
    }

    public function viewSets($ord='`date` DESC')
    {
        $q = "SELECT * FROM `mtg_ls_sets`
        ORDER BY $ord;";
        return \DB::select($q);
    }

    public function selectInventory($args, $ord="`inv`.`timestamp` ASC", $lim=25, $comp="=", $hide=false)
    {
        $q = "SELECT `inv`.`inventory_id`, `inv`.`seller_id`, `card`.`card_id`, `card`.`set_id`,
        `seller_str`, `name_str`, `set_str`, `mtg_inv`.`condition_id`, `condition_str`,
        `mtg_inv`.`special_id`, `special_str`, `inv`.`quantity`, `inv`.`price`, `mana_str`, `cmc_amt`,
        `type_str`, `subtype_str`, `text_str`, `flavor_str`, `artist_str`, `img_src`, `green_amt`
        FROM `mtg_rel_cards` AS `card`
        LEFT JOIN `mtg_rel_inventory` AS `mtg_inv` ON `card`.`card_id`=`mtg_inv`.`card_id`
        LEFT JOIN `site_rel_inventory` AS `inv` ON `inv`.`item_id`=`mtg_inv`.`item_id` AND `inv`.`market_id`=`mtg_inv`.`market_id`
        LEFT JOIN `site_ls_sellers` AS `seller` ON `inv`.`seller_id`=`seller`.`user_id`
        LEFT JOIN `card_ls_specials` AS `special` ON `mtg_inv`.`special_id`=`special`.`special_id`
        LEFT JOIN `card_ls_conditions` AS `condition` ON `mtg_inv`.`condition_id`=`condition`.`condition_id`
        JOIN `mtg_ls_sets` AS `set` ON `set`.`set_id`=`card`.`set_id`
        JOIN `mtg_ls_names` AS `name` ON `name`.`name_id`=`card`.`name_id`
        LEFT JOIN `mtg_ls_manas` AS `mana` ON `mana`.`mana_id`=`card`.`mana_id`
        LEFT JOIN `mtg_ls_colors` AS `color` ON `color`.`color_id`=`card`.`color_id`
        LEFT JOIN `mtg_ls_types` AS `type` ON `type`.`type_id`=`card`.`type_id`
        LEFT JOIN `mtg_ls_subtypes` AS `subtype` ON `subtype`.`subtype_id`=`card`.`subtype_id`
        LEFT JOIN `mtg_ls_rares` AS `rare` ON `rare`.`rare_id`=`card`.`rare_id`
        LEFT JOIN `mtg_ls_texts` AS `text` ON `text`.`text_id`=`card`.`text_id`
        LEFT JOIN `mtg_ls_flavors` AS `flavor` ON `flavor`.`flavor_id`=`card`.`flavor_id`
        LEFT JOIN `mtg_ls_artists` AS `artist` ON `artist`.`artist_id`=`card`.`artist_id`
        LEFT JOIN `mtg_ls_images` AS `image` ON `image`.`image_id`=`card`.`image_id`
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

    public function addPrice($price)
    {
        $q = "INSERT INTO `mtg_card_prices` (`card_id`, `source_id`, `price`)
            VALUES (".$price['card_id'].", ".$price['source_id'].", ".$price['price'].");";
        return \DB::insert($q);
    }

}
?>
