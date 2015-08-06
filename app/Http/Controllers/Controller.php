<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

// custom functions
use App\Http\Models\UserModel;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        session()->regenerate();
        if (null !== (\Session::get('active'))) {
            $this->session = \Session::get('active');
        } else {
            $session = str_random(99);
            \Session::put('active', $session);
            $this->session = $session;
        }

        if (\Auth::check()) {
            $this->user = \Auth::user()->id;
            $UserModel = new UserModel();
            $permissions = $UserModel->getPermissions($this->user);
            $this->permissions = [];
            foreach ($permissions as $permission) {
                $this->permissions[] = $permission->permission_str;
            }
            unset($UserModel);
        }
    }

    public function GET($var, $type=null, $len=null)
    {
        if ($type == 'i') { // integer
            $txt = filter_input(INPUT_GET, $var, FILTER_SANITIZE_NUMBER_INT);
            $txt = preg_replace("/[^0-9]/", "", $txt);
        } else if ($type == 'f') { // float
            $txt = filter_input(INPUT_GET, $var, FILTER_SANITIZE_NUMBER_FLOAT);
            $txt = preg_replace("/[^0-9\.]/", "", $txt);
        } else if ($type == 'l') { // letters
            $txt = filter_input(INPUT_GET, $var, FILTER_SANITIZE_URL);
            $txt = preg_replace("/[^a-zA-Z]/", "", $txt);
        } else if ($type == 'e') { // email
            $txt = filter_input(INPUT_GET, $var, FILTER_SANITIZE_EMAIL);
        } else if ($type == 'a') { // alphanumeric
            $txt = filter_input(INPUT_GET, $var, FILTER_SANITIZE_STRING);
            $txt = preg_replace("/[^a-zA-Z0-9]/", "", $txt);
        } else if ($type == 'w') { // words
            $txt = filter_input(INPUT_GET, $var, FILTER_SANITIZE_STRING);
            $txt = preg_replace("/[^\w ]/", "", $txt);
        } else if ($type == 'r') { // raw
            $txt = filter_input(INPUT_GET, $var, FILTER_UNSAFE_RAW);
        } else {
            $txt = filter_input(INPUT_GET, $var, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        if (isset($len)) {
            $txt = substr($txt, 0, $len);
        }
        return $txt;
    }

    public function POST($var, $type=null, $len=null)
    {
        if ($type == 'i') { // integer
            $txt = filter_input(INPUT_POST, $var, FILTER_SANITIZE_NUMBER_INT);
            $txt = preg_replace("/[^0-9]/", "", $txt);
        } else if ($type == 'f') { // float
            $txt = filter_input(INPUT_POST, $var, FILTER_SANITIZE_NUMBER_FLOAT);
            $txt = preg_replace("/[^0-9\.]/", "", $txt);
        } else if ($type == 'l') { // letters
            $txt = filter_input(INPUT_POST, $var, FILTER_SANITIZE_URL);
            $txt = preg_replace("/[^a-zA-Z]/", "", $txt);
        } else if ($type == 'e') { // email
            $txt = filter_input(INPUT_POST, $var, FILTER_SANITIZE_EMAIL);
        } else if ($type == 'a') { // alphanumeric
            $txt = filter_input(INPUT_POST, $var, FILTER_SANITIZE_STRING);
            $txt = preg_replace("/[^a-zA-Z0-9]/", "", $txt);
        } else if ($type == 'w') { // words
            $txt = filter_input(INPUT_POST, $var, FILTER_SANITIZE_STRING);
            $txt = preg_replace("/[^\w ]/", "", $txt);
        } else if ($type == 'r') { // raw
            $txt = filter_input(INPUT_POST, $var, FILTER_UNSAFE_RAW);
        } else {
            $txt = filter_input(INPUT_POST, $var, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        if (isset($len)) {
            $txt = substr($txt, 0, $len);
        }
        return $txt;
    }

}
