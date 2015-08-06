<?php namespace App\Http\Controllers;

use App\Http\Controllers\Auth;

use App\Http\Models\UserModel;
use App\Http\Models\BlogModel;

class BlogController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
		parent::__construct();

        $this->BlogModel = new BlogModel();
	}

	public function edit()
	{
		if (in_array('author', $this->permissions)) {
			$data['blogs'] = $this->BlogModel->selectBlogs(['author_id'=>$this->user], 50);
			return view('blogs/edit')->withData($data);
		} else {
			return redirect('blogs');
		}
	}

	public function processPost()
	{
		if (in_array('author', $this->permissions)) {
			$function = $this->POST('function');
			$blog = ['author_id' => $this->user];
			if ($function == 'add') {
				$blog['title_str'] = $this->POST('title');
				$blog['post_str'] = $this->POST('post');
				$blog['date'] = date("Y-m-d H:i:s");
				$this->BlogModel->insertBlog($blog);
			} else if ($function == 'delete') {
				$blog['blog_id'] = $this->POST('blog_id');
				$this->BlogModel->deleteBlog($blog);
			} else if ($function == 'edit') {
				$blog['blog_id'] = $this->POST('blog_id');
				$update['title_str'] = $this->POST('title');
				$update['post_str'] = $this->POST('post');
				$this->BlogModel->editBlog($blog, $update);
			}
			return redirect('blog/edit');
		}
	}

}

?>
