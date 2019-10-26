<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SinglePageController extends Controller
{
  const APP_NAME = "OCD Thoughts";

  public function index() {
    return view('app', ["appName" => self::APP_NAME]);
  }
}
