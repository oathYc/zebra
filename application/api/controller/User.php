<?php

namespace app\api\controller;

use app\api\validate\ValidataCommon;
use app\common\utils\HmString;

/**
 * 所有资源类接都必须继承基类控制器
 * 基类控制器提供了基础的验证，包含app_token,请求时间，请求是否合法的一系列的验证
 * 在所有子类中可以调用$this->clientInfo对象访问请求客户端信息，返回为一个数组
 * 在具体资源方法中，不需要再依赖注入，直接调用$this->request返回为请具体信息的一个对象
 */
class User extends Api
{

    /**
     * 陌生刘：💻
     * Notes:展示列表
     * User: lyc
     * Date: 2019/6/13
     * Time: 15:17
     */
    public function addMessage ()
    {
        if ( $this->request->isPost() ) {

            ValidataCommon::validateCheck([
                'msg'     => 'require',
                'mailbox' => 'mailbox',
                //可以同时判断是否为空
            ], self::$params);

            $img = empty(self::$params['img']) ? '' : self::$params['img'];
            $msg = self::$params['msg'];
            $mailbox = empty(self::$params['mailbox']) ? '' : self::$params['mailbox'];
            // 限制十五分钟一次次数 一天几次 全部配置起来；
            $ip = getclientip();

            $config = db('config')->order('id', 'asc')->limit(1)->find();

            $map['ip'] = $ip;
            $map['created_at'] = [
                'between time',
                [
                    strtotime(date('Y-m-d 00:00:00')),
                    strtotime(date('Y-m-d 23:59:59'))
                ]
            ];

            $model = db('comment')->where($map)->order('created_at', 'DESC')->field('id,created_at,ip')->select();


            /*判断配置*/
            if ( !empty($model) ) {
                $created_at = time() - $model[0]['created_at'];
                $count = count($model);
                /*判断后台配置留言的时间间隔和 一天留言次数*/
                if ( $created_at <= (empty($config['msg_time']) ? config('MSG_TIME') : $config['msg_time'] * 60) || $count >= $config['msg_frequency'] ) self::errorResponse(lang('rest awhile'), 100);
            }

            /*这里不作为外部展示暂时不用脏字鉴别*/
            $param['mailbox'] = $mailbox;
            $param['img'] = $img;
            $param['ip'] = $ip;
            $param['created_at'] = time();
            $param['updated_at'] = time();
            $param['comment_text'] = cutstr_html(HmString::clean_space($msg));

            if ( db('comment')->insert($param) ) self::successResponse();
            self::errorResponse(lang('error'));
        }

    }

    /**
     * 陌生刘：💻
     * Notes:上传图片 bs64
     * User: lyc
     * Date: 2019/6/15
     * Time: 10:46
     */
    public function img ()
    {
        if ( $this->request->isPost() ) {

            ValidataCommon::validateCheck([
                'imgBs' => 'require',
                //可以同时判断是否为空
            ], self::$params);

            $img = self::$params['imgBs'];
            $imgBs = base64_image_content($img, './uploads');
            if ( !$imgBs ) self::errorResponse(lang('error'));
            $imgBs = substr($imgBs, 1);

            self::successResponse(['imgBs' => $imgBs]);
        }
    }
}
