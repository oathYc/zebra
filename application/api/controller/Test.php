<?php
/**
 * 授权基类，所有获取access_token以及验证access_token 异常都在此类中完成
 */

namespace app\api\controller;

use app\api\model\Identity;
use app\api\model\Member;
use app\common\model\Share;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;



header("Access-Control-Allow-Origin:*");
class Test extends Controller
{
   public function test(){
       $date = date('Y-m-d H:i');
       var_dump(strtotime($date));

   }


}