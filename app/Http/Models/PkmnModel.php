<?php namespace App\Http\Models;


class PkmnModel extends BaseModel {

    public function test() {
        $q = "SELECT * FROM `pkmn_rel_inventory` AS `pkmn_inv`
        LEFT JOIN `pkmn_rel_cards` AS `card` ON `card`.`card_id`=`pkmn_inv`.`card_id`";
        return $this->selectAll($q);
    }


}

?>
