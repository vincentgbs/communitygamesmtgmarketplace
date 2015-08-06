<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MailModel extends Model {

    public function insertMessage($message)
    {
        $q = "INSERT INTO `site_ls_messages` (";
        $keys = ''; $values = '';
        foreach ($message as $k => $v) {
            $keys .= " `$k`, ";
            $values .= " '$v', ";
        }
        $keys = substr($keys, 0, -2);
        $values = substr($values, 0, -2);
        $q .= $keys . ") VALUES (" . $values . ");";
        return \DB::insert($q);
    }

    public function selectMessages($args)
    {
        $q = "SELECT `name`, `message_str`, `date` FROM `site_ls_messages`
        LEFT JOIN `users` ON `site_ls_messages`.`to_id`=`users`.`id`
        WHERE ";
        foreach ($args as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4);
        return \DB::select($q);
    }

    public function insertContact($contact)
    {
        $q = "INSERT INTO `site_ls_contactus` (";
        $keys = ''; $values = '';
        foreach ($contact as $k => $v) {
            $keys .= " `$k`, ";
            $values .= " '$v', ";
        }
        $keys = substr($keys, 0, -2);
        $values = substr($values, 0, -2);
        $q .= $keys . ") VALUES (" . $values . ");";
        return \DB::insert($q);
    }

}
?>
