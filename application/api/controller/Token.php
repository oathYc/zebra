<?php
/**
 * 获取accesstoken
 */

namespace app\api\controller;

use app\api\controller\v1\Send;
use think\Cache;

class Token extends Api
{
    use Send;

    /*token 前缀*/
    public static $accessTokenPrefix = 'accessToken_';
    /*刷新token 前缀*/
    public static $refreshAccessTokenPrefix = 'refreshAccessToken_';
    //token过期时间
    public static $expires = 7200;
    //刷新token过期时间 30天
    public static $refreshExpires = 2592000;


    /**
     * 陌生刘：💻
     * Notes:生成token
     * User: lyc
     * Date: 2019/6/11
     * Time: 18:10
     */
    public function token ()
    {

        $validate = new \app\api\validate\Token;
        //参数验证
        if ( !$validate->scene('token')->check(self::$params) ) {
            self::errorResponse($validate->getError());
        }
        $appId = getclientip(); /*有用户就用用户里面的 没有就直接用ip代替*/

        try {
            $accessTokenInfo = $this->setAccessToken($appId);
            self::successResponse($accessTokenInfo);
        } catch (\Exception $e) {
            self::errorResponse(lang('server error'), 500);
        }
    }


    /**
     * 刷新token
     */
    public function refresh ()
    {
        $validate = new \app\api\validate\Token;
        //参数验证
        if ( !$validate->scene('refresh')->check(self::$params) ) {
            self::errorResponse($validate->getError());
        }



        $refresh_token = self::$params['refreshToken'];
        $appId = getclientip(); /*有用户就用用户里面的*/

        $cache_refresh_token = Cache::get(self::$refreshAccessTokenPrefix . $appId);  //查看刷新token是否存在
        if ( !$cache_refresh_token ) {
            self::errorResponse(lang('refresh_token is null'));
        } else {
            if ( $cache_refresh_token !== $refresh_token ) {
                self::errorResponse(lang('refresh_token is error'));
            } else {    //重新给用户生成调用token

                $accessToken = self::setAccessToken($appId);
                self::successResponse($accessToken);
            }
        }
    }

    /**
     * 设置AccessToken
     * @param $clientInfo
     * @return int
     */
    protected function setAccessToken ($ip)
    {

        //生成令牌
        $accessToken = self::buildAccessToken();
        $refresh_token = self::getRefreshToken($ip);

        $accessTokenInfo = [
            //访问令牌
            'accessToken'        => $accessToken,
            //刷新的token
            'refreshToken'       => $refresh_token,
            //请求过期时间时间戳
            'expiresTime'        => time() + self::$expires,
            //刷新token过期时间
            'refreshExpiresTime' => time() + self::$refreshExpires,
            //            'client'       => $clientInfo,//用户信息
        ];
        self::saveAccessToken($accessToken, $accessTokenInfo);
        self::saveRefreshToken($refresh_token, getclientip());
        return $accessTokenInfo;
    }

    /**
     * 生成AccessToken
     * @return string
     */
    protected static function buildAccessToken ($lenght = 16)
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
        Cache::set(self::$accessTokenPrefix . $accessToken, $accessTokenInfo, self::$expires);

        //存储用户与信息索引 用于比较,这里涉及到user_id，如果有需要请关掉注释
        //Cache::set(self::$accessTokenAndClientPrefix . $accessTokenInfo['client']['user_id'], $accessToken, self::$expires);
    }


    /**
     * 刷新用的token检测是否还有效
     */
    public static function getRefreshToken ($appid = '')
    {
        return Cache::get(self::$refreshAccessTokenPrefix . $appid) ? Cache::get(self::$refreshAccessTokenPrefix . $appid) : self::buildAccessToken();
    }

    /**
     * 刷新token存储
     * @param $accessToken
     * @param $accessTokenInfo
     */
    protected static function saveRefreshToken ($refresh_token, $appid)
    {
        //存储RefreshToken
        cache(self::$refreshAccessTokenPrefix . $appid, $refresh_token, self::$refreshExpires);
    }
}