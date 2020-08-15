<?php
/**
 * 获取accesstoken
 */

namespace app\api\controller;

use app\api\controller\v1\Send;
use app\common\model\Share;
use think\Cache;

header("Access-Control-Allow-Origin:*");
class Token extends Api
{

    /*token 前缀*/
    public static $accessTokenPrefix = 'accessToken_';
    //token过期时间
    public static $expires = 7200;



    /**
     * 设置AccessToken
     * @param $clientInfo
     * @return int
     */
    public static function setAccessToken ()
    {
        $uid = session('uid');
        if(!$uid){
            Share::jsonData(0,'','请先登录');
        }
        //生成令牌
        $accessToken = self::buildAccessToken();

        $accessTokenInfo = [
            //访问令牌
            'accessToken'        => $accessToken,
            //请求过期时间时间戳
            'expiresTime'        => time() + self::$expires,
            'uid'=>$uid,
        ];
        self::saveAccessToken($accessToken, $accessTokenInfo);
        Share::jsonData(1,$accessTokenInfo,'success');
        return $accessTokenInfo;
    }
    /**
     * token验证
     */
    public static function checkAccessToken($accessToken,$uid){
        if(!$accessToken){
            Share::jsonData(-1,'','token不存在');
        }
        $data = self::getAccessToken($accessToken);
        if(!$data || !is_array($data)){
            Share::jsonData(-2,'','token错误');
        }
        if($data['uid'] != $uid){
            Share::jsonData(-3,'','token不是该用户的');
        }
        $now = time();
        if($now > $data['expiresTime']){
            Share::jsonData(999,'','token已过期！');
        }
    }

    /**
     * 生成AccessToken
     * @return string
     */
    protected static function buildAccessToken ($lenght = 32)
    {
        //生成AccessToken
        $str_pol = config('tokenKey');
        return substr(str_shuffle($str_pol), 0, $lenght);

    }

    /**
     * 存储
     * @param $accessToken
     * @param $accessTokenInfo
     */
    protected static function saveAccessToken ($accessToken, $accessTokenInfo)
    {
        //存储accessToken
        $key = self::$accessTokenPrefix . $accessToken;
        Cache::set($key, $accessTokenInfo, self::$expires);

        //存储用户与信息索引 用于比较,这里涉及到user_id，如果有需要请关掉注释
        //Cache::set(self::$accessTokenAndClientPrefix . $accessTokenInfo['client']['user_id'], $accessToken, self::$expires);
    }

    /**
     *token获取
     */
    protected  static function getAccessToken($accessToken){
        $key = self::$accessTokenPrefix.$accessToken;
        $val = Cache::get($key);
        return $val;

    }


}