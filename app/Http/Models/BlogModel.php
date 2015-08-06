<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class BlogModel extends Model {

    public function selectBlogs($args=false, $lim=10)
    {
        $q = "SELECT `blog_id`, `author_str`, `title_str`, `post_str`, `date`, `timestamp`
        FROM `blog_ls_posts` AS `posts`
        JOIN `blog_ls_authors` AS `authors` ON `authors`.`user_id`=`posts`.`author_id` ";
        if ($args) {
            $q .= " WHERE ";
            foreach ($args as $k => $v) {
                $q .= " `$k` = '$v' AND ";
            }
            $q = substr($q, 0, -4);
        }
        $q .= " LIMIT $lim;";
        return \DB::select($q);
    }

    public function insertBlog($blog)
    {
        $q = "INSERT INTO `blog_ls_posts` (`author_id`, `title_str`, `post_str`, `date`)
        VALUES (".$blog['author_id'].", '".$blog['title_str']."', '".$blog['post_str']."', '".$blog['date']."');";
        return \DB::insert($q);
    }

    public function deleteBlog($blog)
    {
        $q = "DELETE FROM `blog_ls_posts` WHERE ";
        foreach ($blog as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . ";";
        return \DB::delete($q);
    }

    public function editBlog($blog, $update)
    {
        $q = "UPDATE `blog_ls_posts` SET ";
        foreach ($update as $k => $v) {
            $q .= " `$k` = '$v', ";
        }
        $q = substr($q, 0, -2) . " WHERE ";
        foreach ($blog as $k => $v) {
            $q .= " `$k` = '$v' AND ";
        }
        $q = substr($q, 0, -4) . ";";
        return \DB::update($q);
    }

}

?>
