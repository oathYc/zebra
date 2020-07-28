<?php
/**
 * 授权基类，所有获取access_token以及验证access_token 异常都在此类中完成
 */

namespace app\api\controller;

use app\api\controller\v1\Factory;
use app\api\controller\v1\Oauth;
use app\api\controller\v1\Send;
use app\api\validate\ValidataCommon;
use think\Controller;
use think\Log;
use think\Request;

class Api extends Controller
{
    use Send;

    /*默认不验证*/
    public $apiAuth = false;
    /*当前请求类型*/
    protected $method;
    /*当前资源类型*/
    protected $type;
    /*返回的资源类的*/
    protected $restTypeList = 'json';
    /*REST允许输出的资源类型列表*/
    protected $restOutputType = [
        'json' => 'application/json',
    ];
    /*客户端信息*/
    protected $clientInfo;
    /*签名key 分类*/
    protected static $keyMap = [
        'im' => 'DwbOB2wtjitQ8r$zCcji#2W!TkgSzj7l8y1wg1*dxgNDDRs!BB%%4dDYalcirbOJ',
        # im使用
    ];
    /*绕过签名的接口map*/
    protected static $noSignApiMap = [
        'Token/token',
        'Token/refresh',
        // 调试
    ];
    // 绕过登录的接口
    protected static $noLoginApiMap = [
        'Member/login',
    ];
    /*不需要鉴权方法*/
    protected static $noAuth = [
        'Token/token',
        'Token/refresh',
    ];
    /*不验证token方法*/
    protected static $noToken = [
        'Token/token',
        'Token/refresh',
    ];
    /*不需要数据验证的方法*/
    protected static $noParams = [
        'Token/token',
        'Token/refresh',
    ];
    /*签名的分类key*/
    protected static $signKey = null;
    /*全部参数*/
    protected static $params = [];
    /*检测时间*/
    protected static $detectionTime = 3000;
    /*测试appid，正式请数据库进行相关验证 用户appId*/
    protected static $appId = '';
    /*请求时间差*/
    protected static $timeDif = 1000;
    /*允许请求的方式*/
    protected static $restMethodList = 'get|post|put|delete|patch|head|options';


    /**
     * 陌生刘：💻
     * Notes:控制器初始化操作
     * User: lyc
     * Date: 2019/6/12
     * Time: 15:20
     */
    public function _initialize ()
    {
        header("Access-Control-Allow-Origin: *");

        try {
            $request = Request::instance();
            $this->request = $request;

            /*当前请求的路由*/
            $path = $this->request->controller() . '/' . $this->request->action();

            self::$params = $this->request->param();

            /*get方式 删删除路由*/
            unset(self::$params['/' . $path]);

            /*参数校验*/
            if ( !in_array($path, self::$noParams) ) {
                self::checkParams(self::$params);
            }

            /*检测token*/
            if ( !in_array($path, self::$noToken) ) {
                $oauth = new Oauth();   //tp5.1容器，直接绑定类到容器进行实例化
                if ( !$oauth->authenticate(self::$params) ) self::errorResponse(lang('Token invalid'));
            }

            // 签名验证
            if ( !in_array($path, self::$noSignApiMap) ) {
                $this->checkSign();
            }

        } catch (\Exception $ex) {

            // 记录错误日志
            Log::error('接口异常：' . $ex->getMessage());

            $msg = lang('The server is busy please try again later');
            if ( $ex->getCode() == 100 ) {
                $msg = $ex->getMessage();
            }

            self::errorResponse($msg, 500);
        }
//        请求方法检查
//        $this->init();
//
//        // 验证登录
//        if ( !in_array($route, self::$noLoginApiMap) ) {
//            if ( empty(self::$userid) ) {
//                  self::errorResponse(lang(''), 100);
//                $this->errorResponse('您还未登录,请先登录!', REP_FAIL_NOT_LOGIN);
//            }
//        }
//        //接口权限检查 用于收权限的时候
//        $this->clientInfo = $this->checkAuth();
    }

    /**
     * 初始化方法
     * 检测请求类型，数据格式等操作
     */
    public function init ()
    {
        //所有ajax请求的options预请求都会直接返回200，如果需要单独针对某个类中的方法，可以在路由规则中进行配置
        if ( $this->request->isOptions() ) {
            self::successResponse(200, lang('success'));
        }
        // 资源类型检测
        $ext = $this->request->ext();

        if ( '' == $ext ) {
            // 自动检测资源类型
            $this->type = $this->request->type();
        } else if ( !preg_match('/\(' . $this->restTypeList . '\)$/i', $ext) ) {
            // 资源类型非法 则用默认资源类型访问 
            $this->type = $this->restDefaultType;
        } else {
            $this->type = $ext;
        }

        $this->setType();
        // 请求方式检测
        $this->method = strtolower($this->request->method());
        //这里可以加入header，防止前端ajax跨域
        if ( false === stripos(self::$restMethodList, $this->method) ) {
            self::errorResponse(lang('Method Not Allowed'));
        }
    }

    /**
     * 陌生刘：💻
     * Notes:检测客户端是否有权限调用接口
     * User: lyc
     * Date: 2019/6/12
     * Time: 15:22
     * @return mixed
     */
    public function checkAuth ()
    {
        $baseAuth = Factory::getInstance(\app\api\controller\Oauth::class);
        $clientInfo = $baseAuth->authenticate();
        return $clientInfo;
    }

    /**
     * 陌生刘：💻
     * Notes:检查签名
     * User: lyc
     * Date: 2019/6/12
     * Time: 15:22
     */
    public function checkSign ()
    {
        /*生成签名*/
        $sign = self::makeSign(self::$params);
        if ( $sign !== $this->request->param('sign') ) {
            self::errorResponse(lang('Signature error'));
        }
    }

    /**
     * 陌生刘：💻
     * Notes:生成签名
     * User: lyc
     * Date: 2019/6/12
     * Time: 15:21
     * @param array $data
     * @return string
     */
    public static function makeSign ($data = [])
    {
        ksort($data);   //数组进行升序排序
        $temp = "";
        $first = true;

        $sign = strtolower(@$data['sign']);
        self::$signKey = strtolower(@$data['signKey']); //转换小写
        // 参数验证
        if ( empty($sign) || empty(self::$signKey) ) {
            self::errorResponse(lang('Wrong signature parameter'));
        }

        if ( !$signKey = self::$keyMap[self::$signKey] ) {
            self::errorResponse(lang('Invalid signature key'));
        }

        /*组装数据*/
        foreach ($data as $key => $val) {
            if ( $key == "sign" ) { // 过滤sign串
                continue;
            }
            if ( $first ) {
                $first = false;
            } else {
                $temp .= "&";
            }

            $temp .= $key . "=";
            if ( mb_strlen($val) > 30 ) {
                $temp .= preg_replace('/\r|\n/', '', $val);
            } else {
                $temp .= $val;
            }
        }
        return self::_getOrderMd5($temp, $signKey);
    }

    /**
     * 陌生刘：💻
     * Notes:计算ORDER的MD5签名
     * User: lyc
     * Date: 2019/6/12
     * Time: 15:21
     * @param array $params
     * @param string $signKey
     * @return string
     */
    private static function _getOrderMd5 ($params = '', $signKey = '')
    {
        return md5(strtolower(md5($params)) . $signKey);
    }


    /**
     * 参数检测
     */
    public static function checkParams ($params = [])
    {
        /*判断公共参数*/
        ValidataCommon::validateCheck([
            'appId'     => 'require',
            'nonce'     => 'require',
            'timeStamp' => 'require',
            'sign'      => 'require',
            'signKey'   => 'require',
        ], $params); //参数验证


        $time = self::$params['timeStamp'];
        //时间戳校验
        if ( abs($time - time()) > self::$timeDif ) {
            self::errorResponse(lang('Request timestamp and server timestamp exception'));
        }
        /*请求时间检测*/
        if ( $time > time() + self::$detectionTime || $time < time() - self::$detectionTime ) {
            self::errorResponse(lang('The requested time is incorrect'));
        }
//        //appid检测，这里是在本地进行测试，正式的应该是查找数据库或者redis进行验证
//        if ( ucAuthCode($params['appId']) !== config('appId') ) {
//            self::errorResponse(lang('Appid error'));
//        }
    }

    /**
     * 陌生刘：💻
     * Notes:空操作
     * User: lyc
     * Date: 2019/6/12
     * Time: 12:33
     */
    public function _empty ()
    {
        self::success();
    }
}