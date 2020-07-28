<?php

namespace app\api\controller;

use think\Request;

class Tests extends Api
{
	public function index()
	{
		dd(Request::instance()->ext());
	}
}