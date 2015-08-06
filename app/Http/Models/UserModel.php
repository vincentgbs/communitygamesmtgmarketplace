<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model {

    public function getPermissions($userId) {
        $q = "SELECT `permission_str` FROM `sys_rel_groups` AS `groups`
        JOIN `sys_rel_permissions` ON `groups`.`group_id`=`sys_rel_permissions`.`group_id`
        JOIN `sys_ls_permissions` ON `sys_rel_permissions`.`permission_id`=`sys_ls_permissions`.`permission_id`
        WHERE `groups`.`user_id` = '$userId';";
        return \DB::select($q);
    }

    public function userIds($args=null, $lim=100) {
        $q = "SELECT `name`, `id` FROM `users` ";
        if (isset($args)) {
            $q .= " WHERE ";
            foreach ($args as $k => $v) {
                $q .= " `$k` = '$v' AND ";
            }
            $q = substr($q, 0, -4);
        }
        $q .= "LIMIT $lim";
        return \DB::select($q);
    }

    public function addUserToGroup($userId, $groupId)
    {
        $q = "";
        return \DB::insert($q);
    }

    public function addPermissionToGroup($groupId, $permissionId)
    {
        $q = "INSERT INTO";
    }

    public function addPermission($permission)
    {
        $q = "INSERT INTO ";
    }

}
?>
