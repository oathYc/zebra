<?php

namespace app\api\controller\v1;

use think\Exception;
use think\Request;
use think\Db;
use think\Cache;

class Oauth
{
    use Send;

    /**
     * Token存储前缀
     *
     * @var string
     */
    public static $accessTokenPrefix = 'accessToken_';

    /**
     * TokenAndClientPrefix
     * @var string
     */
    public static $accessTokenAndClientPrefix = 'accessTokenAndClient_';

    /**
     * 过期时间秒数
     *
     * @var int
     */
    public static $expires = 72000;

    /**
     * 客户端信息
     *
     * @var
     */
    public $clientInfo;

    /**
     * 认证授权 通过用户信息和路由
     * @param Request $request
     * @return \Exception|UnauthorizedException|mixed|Exception
     * @throws UnauthorizedException
     */
    public function authenticate ($data = [])
    {
        try {
            //验证授权
            return $this->certification($data);
        } catch (Exception $e) {
            self::errorResponse(lang('Authentication credentials error'));
        }
    }


    /**
     * 获取用户信息后 验证权限
     * @return mixed
     */
    public function certification ($data = [])
    {
        //======下面注释部分是数据库验证access_token是否有效，示例为缓存中验证======
        // $time = date("Y-m-d H:i:s",time());
        // $checkclient = Db::name('tb_token')->field('end_time')->where('user_id',$data['user_id'])->where('app_key',$data['app_key'])->where('app_token',$data['access_token'])->find();
        // if(empty($checkclient)){
        //     return $this->returnmsg(402,'App_token does not match app_key');
        // }
        // if($checkclient <= $time){
        //     return $this->returnmsg(402,'Access_token expired');
        // }
        // return true;

        //获取缓存access_token
        $getCacheAccessToken = Cache::get(self::$accessTokenPrefix . $data['appId']);

        if ( !$getCacheAccessToken ) {
            return false;
        }

        /*这里是用户信息*/
//        if ( $getCacheAccessToken['client']['appKey'] != $data['appKey'] ) {
//            return false;
//        }

        return true;
    }

    /**
     * 检测当前控制器和方法是否匹配传递的数组
     *
     * @param array $arr 需要验证权限的数组
     * @return boolean
     */
    public static function match ($arr = [])
    {
        $request = Request::instance();
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if ( !$arr ) {
            return false;
        }
        $arr = array_map('strtolower', $arr);
        // 是否存在
        if ( in_array(strtolower($request->action()), $arr) || in_array('*', $arr) ) {
            return true;
        }

        // 没找到匹配
        return false;
    }

}