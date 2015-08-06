<?php namespace App\Http\Controllers;

use App\Http\Controllers\Auth;

use App\Http\Models\PkmnModel;

class PkmnController extends Controller {

    public function __construct()
    {
        $this->PkmnModel = new PkmnModel();
    }

    public function test()
    {
        $x = $this->PkmnModel->test();
        var_dump($x);
    }

}
?>
