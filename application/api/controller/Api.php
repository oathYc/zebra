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
class Api extends Controller
{
    public  $noCheck = [
        'register','login','gettoken','wxnotify','wxlogin'
    ];//跳过登录token验证
    public $uid;
    const PAY = 0;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        //当前方法
        $action = Request::instance()->action();
        if(!in_array($action,$this->noCheck)){
            self::checkUid();
            $accessToken = isset($_SERVER['HTTP_ACCESSTOKEN'])?$_SERVER['HTTP_ACCESSTOKEN']:'';
            $uid = session('uid');
            Token::checkAccessToken($accessToken,$uid);
        }
    }
    protected  function checkUid(){
        $uid = session('uid');
        if(!$uid){
            Share::jsonData(100,'','你还没登录');
        }
        $loginTime = session('login');
        $now = time();
        $expire = 7200;
        if(($loginTime + $expire) < $now ){
            session(null);//销毁所有登录信息
            Share::jsonData(101,'','登录失效，请重新登录！');
        }
        $this->uid = $uid;
    }

    //TODO 余额充值
    public function wxPay(){
        $uid = $this->uid;
        $money = input('money',0);
        Share::checkEmptyParams(['money'=>$money]);
        Appwxpay::recharge($uid,$money);
    }
    /**
     * 微信回调
     */
    public function wxNotify(){
        Appwxpay::notify();
    }
    //TODO 微信授权登录
    public function wxLogin() {
//            $json = '{"nickname":".","openid":"o8m6C57HIDfgizWst5ORVxyAXjfI","unionid":"oTa415y5Xs0rTBmN20ngPoEuSMFg","headimgurl":"http://thirdwx.qlogo.cn/mmopen/vi_32/SkxPZKY0iboCkjbtGXic5oYmFAJNnS1UjiaGs2rGfZutA1FZydUAKl2h7cVGfCtYX50SibbOugJzUgsZQuvQvlhbtw/132"}';
//            $str = json_decode($json,true);
        $name = strip_tags(input('nickname'));//昵称
        $openid = strip_tags(input('openid')); //openid
        $unionid = strip_tags(input('unionid'));
        $inviterCode = strip_tags(input('inviterCode'));//邀请人的邀请码
        $phone = ""; // 手机号码
        $password = "123456"; // 密码
        $headimg = strip_tags(input('headimgurl'));
//			$headimg = $str['headimgurl'];
        if($headimg=='')$headimg ='/uploads/avatar/mr.jpg';
        Share::checkEmptyParams(['openid'=>$openid,'nickname'=>$name]);
        $user = db('member')->where('openid',$openid)->find();
        if(!$user){//新增
            $inviteCode = Share::getInviteCode();
            $params = [
                'phone'=>$phone,
                'password'=>md5($password),
                'real_pass'=>$password,
                'username'=>$name,
                'nickname'=>$name,
                'createTime'=>time(),
                'money'=>0,
                'openid'=>$openid,
                'unionid'=>$unionid,
                'avatar'=>$headimg,
                'inviteCode'=>$inviteCode
            ];
            if($inviterCode){
                $params['inviterCode'] = $inviterCode;
            }
            $res = db('member')->insert($params);
        }else{//修改
            $params = [
                'phone'=>$phone,
                'nickname'=>$name,
                'unionid'=>$unionid,
//                'avatar'=>$headimg,
                'updateTime'=>time(),
            ];
            $res = db('member')->where('openid',$openid)->update($params);
        }
        if($res){//登录成功
            $user = db('member')->where('openid',$openid)->find();
            $uid = $user['id'];
            session('uid',$uid);
            session('login',time());
            //打卡次数
            $signNum = Share::getUserSignNum($uid);
            //累计收益
            $moneyGet = Share::getUserMoneyGet($uid);
            $user['signNum'] = $signNum;
            $user['moneyGet'] = $moneyGet;
            Share::jsonData(1,$user,'登录成功');
        }else{
            Share::jsonData(0,'','授权失败！');
        }

    }


    /**
     * 获取token
     */
    public function getToken(){
        Token::setAccessToken();
    }
    /**
     * 头像上传
     */
    public function uploadImg(){
        $host = config('hostUrl');
        $file = request()->file('file');
        $type = input('type',1);//1-头像  2-商品图片
        if (!empty($file)) {
            // 移动到框架应用根目录/public/uploads/category 目录下
            if($type ==1){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/avatar');
                $dir = '/uploads/avatar';
            }else{
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/product');
                $dir = '/uploads/product';
            }
            if ($info) {
                $src = $dir . '/' . date('Ymd') . '/' . $info->getFilename();
                Share::jsonData(0,['src' => $src]);
            } else {
                // 上传失败获取错误信息
                Share::jsonData(0,'',$file->getError());
            }
        }else{
            Share::jsonData(0,'','图片不存在');
        }
    }
    /**
     * 文件上传
     */
    public function uploadFile(){
        $host = config('hostUrl');
        $file = request()->file('file');
        if (!empty($file)) {
            // 移动到框架应用根目录/public/uploads/category 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/file');
            if ($info) {
                $src = '/uploads/file' . '/' . date('Ymd') . '/' . $info->getFilename();
                Share::jsonData(1,['src' => $src]);
            } else {
                // 上传失败获取错误信息
                Share::jsonData(0,'',$file->getError());
            }
        }else{
            Share::jsonData(0,'','文件不存在');
        }
    }
    /**
     * 用户信息修改
     */
    public function messageEdit(){
        $uid = $this->uid;
        $params['nickname'] = input('nickname');
        $params['sex'] = input('sex');
        $params['real_name'] = input('real_name');
        $params['card'] = input('card');
        $params['avatar'] = input('avatar');
        $params['updateTime'] = time();
        $res = db('member')->where('id',$uid)->update($params);
        if($res){
            Share::jsonData(1);
        }else{
            Share::jsonData(0,'','修改失败');
        }
    }
    /**
     * 用户信息获取
     */
    public function myMessage(){
        $uid = $this->uid;
        $user = db('member')->where('id',$uid)->find();
        //打卡次数
        $signNum = Share::getUserSignNum($uid);
        //累计收益
        $moneyGet = Share::getUserMoneyGet($uid);
        $user['signNum'] = $signNum;
        $user['moneyGet'] = $moneyGet;
        Share::jsonData(1,$user);
    }
    /**
     * 房间挑战
     * 房间类型信息
     * 普通 保底
     */
    public function roomType(){
        $roomType = db('room_type')->select();
        foreach($roomType as $k => $v){
            $roomType[$k]['typeStr'] = Share::getRoomTypeStr($v['type']);
        }
        Share::jsonData(1,$roomType);
    }
    /**
     * 房间挑战
     * 创建房间
     * 用户创建
     */
    public function roomCreate(){
        $uid = $this->uid;
        $params['type'] = input('type',1);//1-保底房间 2-普通房间
//        $params['pattern'] = input('pattern',1);//项目模式  1-每日奖励失败金  2-平分模式
        $number = input('number',0);//默认不限制 0-不限制  限制的话必须大于2
//        $params['sign'] = input('sign',1);//1-一键签到 2-发圈签到
        $params['name'] = input('name');
        $params['desc'] = input('desc');//房间描述
        $params['money'] = input('money');//活动金额
        $params['beginDate'] = input('beginDate','');//开始时间
        $params['day'] = input('day',1);//天数 周期
        $signBegin = input('signBegin');//签到开始时间
        $signEnd = input('signEnd');//签到结束时间
        $signNum = input('signNum',1);//签到次数 最多两次
        $signNum = $signNum>1?2:1;
        $params['signNum'] = $signNum;
        $signBeginMinute = Share::getMinute($signBegin);//获取对应的分钟数
        $signEndMinute = Share::getMinute($signEnd);
        if($signEndMinute < $signBeginMinute){
            Share::jsonData(0,'','首次签到结束时间不能小于首次签到开始时间');
        }
        $params['signBegin'] = $signBeginMinute;
        $params['signEnd'] = $signEndMinute;
        if($signNum > 1){//获取去第二次签到时间
            $secondBegin = input('secondBegin');
            $secondEnd = input('secondEnd');
            $secondBeginMinute = Share::getMinute($secondBegin);
            $secondEndMinute = Share::getMinute($secondEnd);
            if($secondEndMinute < $secondBeginMinute){
                Share::jsonData(0,'','二次签到开始不能小于二次签到结束时间！');
            }
            if($secondBeginMinute < $signEndMinute){
                Share::jsonData(0,'','二次签到开始时间必须大于首次签到结束时间！');
            }
            $params['secondBegin'] = $secondBeginMinute;
            $params['secondEnd'] = $secondEndMinute;
            $params['secondBeginStr'] = $secondBegin;
            $params['secondEndStr'] = $secondEnd;
        }
        if($number && $number < 2){
            Share::jsonData(0,'','报名人数必须大于1');
        }
        Share::checkEmptyParams($params);
        $params['number'] = $number;
        $params['beginTimeStr'] = $signBegin;
        $params['endTimeStr'] = $signEnd;
        $params['sign'] = 1;//1-一键签到 2-发圈签到
        //判断该房间名是否已存在（报名中和活动中）  禁止房间名一样
        $had = db('room_create')->where(['name'=>$params['name'],'status'=>['in',[0,1]]])->find();
        if($had){
            Share::jsonData(0,'','当前房间名已存在，请重试');
        }
        if($params['type'] == 1 ){//保底房间
            $minMoney = Share::getlowest(1);//1-最低金额 2-最高金额
            $maxMoney = Share::getLowest(2);
            if($params['money'] < $minMoney){
                Share::jsonData(0,'','保底房间的最低金额不能小于'.$minMoney);
            }
            if($params['money'] > $maxMoney){
                Share::jsonData(0,'','保底房间的最高金额不能大于'.$maxMoney);
            }
        }else{//普通房间
            $minMoney = Share::getCommon(1);
            $maxMoney = Share::getCommon(2);
            if($params['money'] < $minMoney){
                Share::jsonData(0,'','普通房间的最低金额不能小于'.$minMoney);
            }
            if($params['money'] > $maxMoney){
                Share::jsonData(0,'','普通房间的最高金额不能大于'.$maxMoney);
            }
        }
        $now = strtotime(date('Y-m-d H:i:s'));//当前分钟的时间戳
        $today = strtotime(date('Y-m-d'));//当天的时间戳
        $beginTime  = strtotime($params['beginDate']);//活动开始时间
        if($beginTime < $today){
            Share::jsonData(0,'','活动开始日期不能小于今天');
        }
        $signBeginTime = $beginTime + $params['signBegin']*60;//第一天的活动签到开始时间
        if($signBeginTime < $now){
            Share::jsonData(0,'','首次签到开始时间必须大于当前时间！');
        }
        $params['beginTime'] = $signBeginTime;//首次签到开始时间  时间戳
        $params['createTime'] = time();
        $params['uid'] = $uid;
        $params['status'] = 0;//状态 0-报名中   1-活动中 2-活动结束
        //创建房间费用扣除
        Share::reduceRoomMoney($uid,$params['money']);
        $res = db('room_create')->insert($params);
        if($res){
            $roomId = db('room_create')->getLastInsID();
            //添加自己的报名信息
            Share::addRoomChallenge($uid,$roomId);
            Share::jsonData(1);
        }else{
            Share::jsonData(0,'','创建失败，请重试！');
        }
    }
    /**
     * 房间挑战
     * 挑战列表
     * 用户获取
     */
    public function roomList(){
        $uid = $this->uid;
//        $pattern = input('pattern',0);//项目模式 0-全部  1-每日奖励金瓜分 2-平分模式
        $type = input('type',0);//0-所有 1-保底 2-普通
        $page = input('page',1);
        $pageSize = input('pageSize',10);
        $where = [
            'status'=>0,//报名中
        ];
//        if($pattern){
//            $where['pattern'] = $pattern;
//        }
        if($type){
            $where['type'] = $type;
        }
        $offset = ($page-1)*$pageSize;
        $total = db('room_create')->where($where)->count();
        $data = db('room_create')->where($where)->limit($offset,$pageSize)->select();
        foreach($data as $k => $v){
            $user = db('member')->where('id',$v['uid'])->find();
            $data[$k]['nickname'] = $user['nickname'];
            $data[$k]['avatar'] = $user['avatar'];
            //报名人数
            $joinCount = db('room_join')->where('roomId',$v['id'])->count();
            $data[$k]['joinNum'] = intval($joinCount);
            //是否已经报名
            $isJoin = db('room_join')->where(['roomId'=>$v['id'],'uid'=>$uid])->find();
            if($isJoin){
                $data[$k]['joinData'] = $isJoin;
                $data[$k]['isJoin'] = 1;
            }else{
                $data[$k]['isJoin'] = 0;
                $data[$k]['joinData'] = [];
            }
        }
        $return = [
            'total'=>$total,
            'data'=>$data,
        ];
        Share::jsonData(1,$return);
    }
    /**
     * 房间挑战
     * 房间挑战详情
     */
    public function roomDetail(){
        $uid = $this->uid;
        $roomId = input('roomId',0);
        Share::checkEmptyParams(['roomId'=>$roomId]);
        $room = db('room_create')->where('id',$roomId)->find();
        if(!$room){
            Share::jsonData(0,'','该挑战房间不存在！');
        }
        $room['room_type'] = db('room_type')->where('type',$room['type'])->find();
        //当前报名金额
        $joinCount = db('room_join')->where('roomId',$roomId)->count();
        $joinCount = $joinCount?$joinCount:0;
        $joinMoney = $joinCount * $room['money'];
        $room['joinMoney'] = $joinMoney;
        //是否已经报名
        $isJoin = db('room_join')->where(['roomId'=>$roomId,'uid'=>$uid])->find();
        if($isJoin){
            $room['joinData'] = $isJoin;
            $room['isJoin'] = 1;
            $isSign = Share::getTodayRoomSign($uid,$roomId,$room['signNum']);
        }else{
            $room['isJoin'] = 0;
            $room['joinData'] = [];
            $isSign = 0;//0-未打卡 1-已打卡
        }
        $room['joinNum'] = $joinCount;
        //已打卡次数
        $room['isSign'] = $isSign;
        Share::jsonData(1,$room);
    }
    /**
     * 房间挑战
     * 用户报名
     */
    public function roomJoin(){
        $uid = $this->uid;
        $roomId = input('roomId',0);//房间id
        Share::checkEmptyParams(['roomId'=>$roomId]);
        $room = db('room_create')->where('id',$roomId)->find();
        if(!$room){
            Share::jsonData(0,'','没有该房间信息！');
        }
        Share::checkRoomStatus($room);//检查房间活动状态
        if($room['status'] != 0){//报名中
            Share::jsonData(0,'','该房间挑战不是报名中，不能报名！');
        }
        //判断自己是否已经报名
        $hadJoin = db('room_join')->where(['roomId'=>$roomId,'uid'=>$uid,'type'=>1])->find();
        if($hadJoin){
            Share::jsonData(0,'','你已经报过过该房间挑战，请勿重复参加！');
        }
        if($room['number'] > 1){//有人数限制
            //获取已报名人数
            $roomJoin = Share::getRoomJoinNumber($roomId,1);//1-房间挑战
            if($roomJoin >= $room['number']){
                //修改房间状态
                Share::updateRoomStatus($roomId,1);// 0-报名中   1-活动中 2-活动结束
                Share::jsonData(0,'','当前报名人数已满，不能报名！');
            }
        }
        //扣除报名费用
        Share::reduceRoomMoney($uid,$room['money']);
        //记录挑战报名信息
        $params = [
            'uid'=>$uid,
            'roomId'=>$roomId,
            'createTime'=>time(),
            'type'=>1,//1-房间挑战
            'status'=>1,//1-参与中 2-已失败 3-已完成
        ];
        $res = db('room_join')->insert($params);
        if($res){
            Share::jsonData(1);
        }else{
            Share::jsonData(0,'','报名失败，请售后重试！');
        }
    }
    /**
     * 房间挑战
     * 用户打卡签到
     */
    public function roomSign(){
        $uid = $this->uid;
        $roomId = input('roomId');
        Share::checkEmptyParams(['roomId'=>$roomId]);
        $room = db('room_create')->where('id',$roomId)->find();
        if(!$room){
            Share::jsonData(0,'','没有该房间！');
        }
        Share::checkRoomStatus($room);
        if($room['status'] == 0){
            Share::jsonData(0,'','当前挑战还没有开始，不可进行签到！');
        }
        if($room['status'] == 2){
            Share::jsonData(0,'','当前挑战已经结束了！');
        }
        //判断自己是否已报名
        $hadJoin = db('room_join')->where(['roomId'=>$roomId,'uid'=>$uid])->find();
        if(!$hadJoin){
            Share::jsonData(0,'','你还没有报名参加该房间挑战活动，不可进行签到！');
        }
        //签到判断
        Share::roomSign($uid,$room);
        Share::jsonData(1);
    }

    /**
     * 房间挑战
     * 房间列表
     * 创建人获取
     * 我的发布
     */
    public function myRoom(){
        $uid = $this->uid;
//        $pattern = input('pattern',0);//项目模式 0-全部  1-每日奖励金瓜分 2-平分模式
        $type = input('type',0);//0-所有 1-保底 2-普通
        $status = input('status',99);//99-全部  0-报名中   1-活动中 2-活动结束
        $where = [
            'uid'=>$uid,
        ];
        if($status != 99){
            $where['status'] = $status;
        }
//        if($pattern){
//            $where['pattern'] = $pattern;
//        }
        if($type){
            $where['type'] = $type;
        }
        $data = db('room_create')->where($where)->select();
        foreach($data as $k=> $v){
            //报名金额
            $number = db('room_join')->where(['roomId'=>$v['id'],'type'=>1])->count();
            $data[$k]['joinMoney'] = $v['money']*$number;
            $data[$k]['joinNumber'] = $number;
        }
        Share::jsonData(1,$data);
    }

    /**
     * 房间挑战
     * 我的参与
     * 用户获取
     */
    public function myRoomJoin(){
        $uid = $this->uid;
        $page = input('page',1);
        $pageSize = input('pageSize',10);
        $status = input('status',0);//0-全部 1-参与中 2-已失败 3-已完成
        $where = [
            'uid'=>$uid,
        ];
        if($status){
            $where['status'] = $status;
        }
        $offset = $pageSize*($page-1);
        $total = db('room_join')->where($where)->count();
        $data = db('room_join')->where($where)->limit($offset,$pageSize)->select();
        foreach($data as $k => $v){
            $room = db('room_create')->where('id',$v['roomId'])->find();
            //创建人信息
            $user = db('member')->where('id',$room['uid'])->find();
            $room['roomerNickname'] = $user['nickname'];
            $room['roomerAvatar'] = $user['avatar'];
            $data[$k]['room'] = $room;
        }
        $return = [
            'total'=>$total,
            'data'=>$data,
        ];
        Share::jsonData(1,$return);
    }
    /**
     * 房间挑战
     * 我的打卡
     */
    public function myRoomSign(){
        $uid = $this->uid;
        $page = input('page',1);
        $pageSize = input('pageSize',10);
        $offset = $pageSize*($page-1);
        $total = db('sign')->where(['uid'=>$uid])->count();
        $data = db('sign')->where('uid',$uid)->order('id','desc')->limit($offset,$pageSize)->select();
        foreach($data as $k => $v){
            $room = db('room_create')->where('id',$v['roomId'])->find();
            $data[$k]['roomName'] = $room['name'];
            $data[$k]['signNum'] = $room['signNum'];
        }
        $return = [
            'total'=>$total,
            'data'=>$data,
        ];
        Share::jsonData(1,$return);
    }

    /**
     * 打卡
     * 打卡活动获取
     * 用户获取
     */
    public function clockInList(){
        $uid = $this->uid;
        $data = db('clock_in')->where('status',1)->order('sort','desc')->select();
        foreach($data as $k => $v){
            //报名人数
            $currJoinNum = db('clock_in_join')->where(['clockInId'=>$v['id'],'status'=>1])->count();
            $data[$k]['currJoinNum'] = $currJoinNum?$currJoinNum:0;
            //是否报名
            $isJoin = db('clock_in_join')->where(['uid'=>$uid,'clockInId'=>$v['id'],'status'=>1])->find();//是否当前参与中
            if($isJoin){
                $data[$k]['currJoin'] = 1;
            }else{
                $data[$k]['currJoin'] = 0;;// 1-当前已参加 0-当前未参加
            }
        }
        Share::jsonData(1,$data);
    }
    /**
     * 打卡活动
     * 活动详情
     */
    public function clockDetail(){
        $id = input('clockInId');
        $uid = $this->uid;
        Share::checkEmptyParams(['id'=>$id]);
        $clock = db('clock_in')->where('id',$id)->find();
        if(!$clock){
            Share::jsonData(0,'','该活动不存在');
        }
        //报名人数
        $currJoinNum = db('clock_in_join')->where(['clockInId'=>$clock['id'],'status'=>1])->count();
        $clock['currJoinNum'] = $currJoinNum?$currJoinNum:0;
        //是否报名
        $isJoin = db('clock_in_join')->where(['uid'=>$uid,'clockInId'=>$clock['id'],'status'=>1])->find();//是否当前参与中
        if($isJoin){
            $clock['currJoin'] = 1;
        }else{
            $clock['currJoin'] = 0;;// 1-当前已参加 0-当前未参加
        }
        //参与金额
        $joinMoney = db('clock_in_join')->where(['clockInId'=>$id,'status'=>1])->sum('joinMoney');
        $clock['joinMoney'] = $joinMoney?$joinMoney:0;
        Share::jsonData(1,$clock);
    }
    /**
     * 打卡活动
     * 活动报名
     */
    public function clockInJoin(){
        $uid = $this->uid;
        $clockId = input('clockInId');//活动id
        $joinMoney = input('joinMoney');//报名金额
        $clock = db('clock_in')->where('id',$clockId)->find();
        if(!$clock){
            Share::jsonData(0,'','该活动不存在');
        }
        if($clock['status'] != 1){
            Share::jsonData(0,'','当前打卡活动已关闭！');
        }
        if($joinMoney > $clock['maxMoney']){
            Share::jsonData(0,'','当前活动最大报名金额为'.$clock['maxMoney']);
        }
        if($joinMoney < 1){
            Share::jsonData(0,'','报名金额不能小于1元');
        }
        //判断当前是否已经报名
        $hadSign = db('clock_in_join')->where(['clockInId'=>$clockId,'status'=>1])->find();
        if($hadSign){
            //判断当前打卡天数及状态
            Share::checkClockInStatus($uid,$hadSign,$clock);
            $hadSign = db('clock_in_join')->where('id',$hadSign['id'])->find();
            if($hadSign['status'] == 1){
                Share::jsonData(0,'','你已经报名参加该打卡活动！');
            }
        }
        $params = [
            'uid'=>$uid,
            'clockInId'=>$clockId,
            'status'=>1,
            'beginTime'=>date('Y-m-d'),
            'createTime'=>time(),
            'clockNum'=>0,
            'joinMoney'=>$joinMoney,
        ];
        $res = db('clock_in_join')->insert($params);
        if($res){
            Share::jsonData(1);
        }else{
            Share::jsonData(0,'','报名失败，请重试!');
        }
    }
    /**
     * 打卡活动
     * 每日打卡
     */
    public function clockInSign(){
        $uid = $this->uid;
        $clockId = input('clockInId');
        Share::checkEmptyParams(['clockInId'=>$clockId]);
        $clock = db('clock_in')->where('id',$clockId)->find();
        if(!$clock){
            Share::jsonData(0,'','该活动不存在');
        }
        //判断当前是否已经报名
        $hadSign = db('clock_in_join')->where(['clockInId'=>$clockId,'status'=>1])->find();
        if(!$hadSign){
            Share::jsonData(0,'','你还没有报名参加该打卡活动！');
        }
        //判断当前打卡天数及状态
        Share::checkClockInStatus($uid,$hadSign,$clock);
        //打卡时间
        $beginTime = $clock['beginTime'];
        $endTime = $clock['endTime'];
        //当前时间
        $currTime =Share::getMinute(date("H:m"));
        if($currTime < $beginTime || $currTime > $endTime){
            Share::jsonData(0,'','当前不在活动打卡时间范围内！');
        }
        $today = date('Y-m-d');
        $params = [
            'uid'=>$uid,
            'clockInId'=>$clockId,
            'joinId'=>$hadSign['id'],//当前报名参加的id
            'clockInTime'=>date('Y-m-d H:i:s'),
            'date'=>$today,
            'createTime'=>time(),
        ];
        $res = db('clock_in_sign')->insert($params);
        if($res){
            //记录打卡次数
            $hadNum = $hadSign['clockNum'] + 1;
            $update = ['clockNum'=>$hadNum];
            if($hadNum >=  $clock['days']){//打卡完成
                $update['status'] = 2;
            }
            db('clock_in_join')->where('id',$hadSign['id'])->update($update);
            //发放奖励
            Share::clockInReward($uid,$hadSign['joinMoney'],$clock);
            //退还报名费
            if($hadNum >= $clock['days']){
                Share::returnClockInMoney($uid,$hadSign['joinMoney']);
            }
            Share::jsonData(1);
        }else{
            Share::jsonData(0,'','打卡失败，请重试！');
        }

    }
    /**
     * 打卡活动
     * 打卡记录
     */
    public function clockRecord(){
        $uid = $this->uid;
        $page = input('page',1);
        $pageSize = input('pageSize',10);
        $offset = ($page-1)*$pageSize;
        $total = db('clock_in_sign')->where('uid',$uid)->count();
        $data = db('clock_in_sign')->where('uid',$uid)->order('createTime','desc')->limit($offset,$pageSize)->select();
        foreach($data as $k => $v){
            $clock = db('clock_in')->where('id',$v['clockInId'])->find();
            $data[$k]['clockName'] = isset($clock['name'])?$clock['name']:'已删除';
        }
        $return = [
            'total'=>$total,
            'data'=>$data,
        ];
        Share::jsonData(1,$return);
    }
    /**
     * 关于我们
     * 1-关于我们 2-帮助中心 3-免责申明
     */
    public function aboutUs(){
        $content = db('system')->where('type',1)->find();
        Share::jsonData(1,$content);
    }
    /**
     * 帮助中心
     * 1-关于我们 2-帮助中心 3-免责申明
     */
    public function helpMsg(){
        $content = db('system')->where('type',2)->find();
        Share::jsonData(1,$content);
    }
    /**
     * 免责申明
     * 1-关于我们 2-帮助中心 3-免责申明
     */
    public function disclaimer(){
        $content = db('system')->where('type',3)->find();
        Share::jsonData(1,$content);
    }
    /**
     * 邀请人
     * 单独绑定
     * 已有邀请人不可修改
     */
    public function addInviter(){
        $uid = $this->uid;
        $inviterCode = input('inviterCode','');
        Share::checkEmptyParams(['inviterCode'=>$inviterCode]);
        $inviterUser = db('member')->where('inviteCode',$inviterCode)->find();
        if(!$inviterUser){
            Share::jsonData(0,'','没有该邀请人，请输入正确的邀请码！');
        }
        $user = db('member')->where('id',$uid)->find();
        if($user['inviteCode'] == $inviterUser){
            Share::jsonData(0,'','你不能邀请自己！');
        }
        if($user['inviterCode']){
            Share::jsonData(0,'','你已绑定邀请人，不能修改');
        }
        $res = db('member')->where('id',$uid)->update(['inviterCode'=>$inviterCode]);
        if($res){
            Share::jsonData(1);
        }else{
            Share::jsonData(0,'','添加失败，请重试！');
        }
    }
    /**
     * 邀请人
     *我的邀请
     */
    public function myInvite(){
        $uid = $this->uid;
        $myCode = db('member')->where('id',$uid)->find()['inviteCode'];
        $myInvite = db('member')->where('inviterCode',$myCode)->select();
        $return = [];
        foreach($myInvite as $k => $v){
            $return[] = [
                'id'=>$v['id'],
                'nickname'=>$v['nickname'],
                'avatar'=>$v['avatar'],
                'inviteTime'=>date('Y-m-d H:i',$v['createTime']),
            ];
        }
        Share::jsonData(1,$return);
    }

    /**
     * 排行榜
     * 打卡排行榜
     * 前十
     */
    public function clockInRanking(){
        $uid = $this->uid;
        $data = db('money_get')->where(['type'=>1])->limit(0,10)->order('moneyGet','desc')->select();
        $own = [
            'mySite'=>0,
            'myMoney'=>0,
        ];
        foreach($data as $k => $v){
            $user = db('member')->where('id',$v['uid'])->find();
            $data[$k]['nickname'] = $user['nickname'];
            $data[$k]['avatar'] = $user['avatar'];if($v['uid'] == $uid){
                $own['mySite'] = $k+1;
                $own['myMoney'] = $v['moneyGet'];
            }
        }
        $return = [
            'ranking'=>$data,
            'own'=>$own,
        ];
        Share::jsonData(1,$return);
    }
    /**
     * 排行榜
     * 房间挑战排行榜
     * 前十
     */
    public function roomRanking(){
        $uid = $this->uid;
        $data = db('money_get')->where(['type'=>2])->limit(0,10)->order('moneyGet','desc')->select();
        $own = [
            'mySite'=>0,
            'myMoney'=>0,
        ];
        foreach($data as $k => $v){
            $user = db('member')->where('id',$v['uid'])->find();
            $data[$k]['nickname'] = $user['nickname'];
            $data[$k]['avatar'] = $user['avatar'];if($v['uid'] == $uid){
                $own['mySite'] = $k+1;
                $own['myMoney'] = $v['moneyGet'];
            }
        }
        $return = [
            'ranking'=>$data,
            'own'=>$own,
        ];
        Share::jsonData(1,$return);
    }
    /**
     * 排行榜
     * 闯关排行榜
     * 前十
     */
    public function passRanking(){
        $uid = $this->uid;
        $data = db('money_get')->where(['type'=>3])->limit(0,10)->order('moneyGet','desc')->select();
        $own = [
            'mySite'=>0,
            'myMoney'=>0,
        ];
        foreach($data as $k => $v){
            $user = db('member')->where('id',$v['uid'])->find();
            $data[$k]['nickname'] = $user['nickname'];
            $data[$k]['avatar'] = $user['avatar'];
            if($v['uid'] == $uid){
                $own['mySite'] = $k+1;
                $own['myMoney'] = $v['moneyGet'];
            }
        }
        $return = [
            'ranking'=>$data,
            'own'=>$own,
        ];
        Share::jsonData(1,$return);
    }
    /**
     * 排行榜
     * 习惯打卡榜
     */
    public function habitRanking(){
        $uid = $this->uid;
        $data = db('money_get')->field("*,sum(moneyGet) as moneyGet")->group('uid')->limit(0,10)->order('moneyGet','desc')->select();
        $own = [
            'mySite'=>0,
            'myMoney'=>0,
        ];
        foreach($data as $k => $v){
            $user = db('member')->where('id',$v['uid'])->find();
            $data[$k]['nickname'] = $user['nickname'];
            $data[$k]['avatar'] = $user['avatar'];
            if($v['uid'] == $uid){
                $own['mySite'] = $k+1;
                $own['myMoney'] = $v['moneyGet'];
            }
        }
        $return = [
            'ranking'=>$data,
            'own'=>$own,
        ];
        Share::jsonData(1,$return);
    }
    /**
     *排行榜
     * 邀请榜
     */
    public function inviteRanking(){
        $uid = $this->uid;
        $data = db('money_get')->where(['type'=>3])->limit(0,10)->order('moneyGet','desc')->select();
        $own = [
            'mySite'=>0,
            'myMoney'=>0,
        ];
        foreach($data as $k => $v){
            $user = db('member')->where('id',$v['shareUid'])->find();
            $data[$k]['nickname'] = $user['nickname'];
            $data[$k]['avatar'] = $user['avatar'];
            if($v['shareUid'] == $uid){
                $own['mySite'] = $k+1;
                $own['myMoney'] = $v['moneyGet'];
            }
        }
        $return = [
            'ranking'=>$data,
            'own'=>$own,
        ];
        Share::jsonData(1,$return);
    }
    /**
     * 闯关活动
     * 闯关活动列表
     */
    public function  passList(){
        $uid = $this->uid;
        $data = db('pass')->where('status',1)->order('number','desc')->select();
        foreach($data as $k => $v){
            //报名人数
            $hadJoin = db('pass_join')->where(['passId'=>$v['id']])->group('uid')->count();
            $data[$k]['joinNum'] = $hadJoin?$hadJoin:0;
            //报名金额
            $joinMoney = db('pass_join')->where('passId',$v['id'])->sum('joinMoney');
            $data[$k]['joinMoney'] = $joinMoney?$joinMoney:0;
            //是否报名
            $join = db('pass_join')->where(['uid'=>$uid,'status'=>0,'passId'=>$v['id']])->find();
            if(!$join){
                $isJoin = 0;//0-当前未参加  1-已参加
            }else{//判断是否已过结束时间
                $now = date('Y-m-d H:i:s');
                if($now > $join['endTime']){
                    //判断打卡状态
                    Share::checkPassStatus($uid,$v['id'],$join['id']);
                    $isJoin = 0;
                }else{
                    $isJoin = 1;
                }
            }
            $data[$k]['isJoin'] = $isJoin;
        }
        Share::jsonData(1,$data);
    }
    /**
     * 闯关活动
     * 闯关详情
     */
    public function passDetail(){
        $uid = $this->uid;
        $passId = input('passId');
        Share::checkEmptyParams(['passId'=>$passId]);
        $pass = db('pass')->where('id',$passId)->find();
        if(!$pass){
            Share::jsonData(0,'','没有该闯关活动！');
        }
        //报名人数
        $hadJoin = db('pass_join')->where(['passId'=>$passId])->group('uid')->count();
        $pass['joinNum'] = $hadJoin?$hadJoin:0;
        //报名金额
        $joinMoney = db('pass_join')->where('passId',$passId)->sum('joinMoney');
        $pass['joinMoney'] = $joinMoney?$joinMoney:0;
        //是否报名
        $join = db('pass_join')->where(['uid'=>$uid,'status'=>0,'passId'=>$passId])->find();
        if(!$join){
            $isJoin = 0;//0-当前未参加  1-已参加
            $signData = [];
        }else{//判断是否已过结束时间
            $now = date('Y-m-d H:i:s');
            if($now > $join['endTime']){
                //判断打卡状态
                Share::checkPassStatus($uid,$passId,$join['id']);
                $isJoin = 0;
            }else{
                $isJoin = 1;
            }
            //获取签到时间数据
            $signData = db('pass_sign')->where(['uid'=>$uid,'passId'=>$passId,'joinId'=>$join['id']])->order('number','asc')->select();
        }
        $pass['isJoin'] = $isJoin;
        $pass['signData'] = $signData;
        Share::jsonData(1,$pass);
    }

    /**
     * 闯关活动
     * 闯关报名
     */
    public function passJoin(){
        $uid = $this->uid;
        $passId = input('passId');
        Share::checkEmptyParams(['passId'=>$passId]);
        $pass = db('pass')->where('id',$passId)->find();
        if(!$pass){
            Share::jsonData(0,'','没有该闯关活动！');
        }
        if($pass['status'] != 1){
            Share::jsonData(0,'','该闯关活动已下线！');
        }
        //判断是否在报名时间内
        Share::checkPassJoinTime($pass);
        //检查是否已经报名
        $now = date('Y-m-d H:i:s');
        $time = strtotime($now);
        $hadJoin = db('pass_join')->where(['uid'=>$uid,'status'=>0,'passId'=>$passId])->find();
        if($hadJoin && $hadJoin['endTime'] > $now){//已参加且未结束
            Share::jsonData(0,'','你当前已经参加了该闯关活动(闯关中)，不可重复参加！');
        }elseif($hadJoin && $hadJoin['endTime'] < $now){//已参加且已结束  判断状态修改
            Share::checkPassStatus($uid,$passId,$hadJoin['id']);
        }
        //获取报名结束时间
        $hour = $pass['hour'];
        $second = $hour*3600;
        $endSecond = $time + $second;
        $endTime = date('Y-m-d H:i:s',$endSecond);
        //添加报名
        $params = [
            'uid'=>$uid,
            'passId'=>$passId,
            'joinTime'=>$now,
            'joinMoney'=>$pass['money'],
            'status'=>0,//参加状态  0-参与中 1-已完成 2-未完成
            'createTime'=>$time,
            'endTime'=>$endTime,
            'isReward'=>0,
        ];
        //扣除用户报名费用
        Share::reducePassJoinMoney($uid,$pass['money']);
        $res = db('pass_join')->insert($params);
        if($res){//报名成功
            //生成用户闯关签到
            $join = db('pass_join')->where($params)->find();
            Share::createUserPassSign($uid,$pass,$join);
            Share::jsonData(1,'','报名成功');
        }else{
            Share::jsonData(0,'','报名失败');
        }
    }
    /**
     * 闯关活动
     * 签到
     */
    public function passSign(){
        $uid = $this->uid;
        $passId = input('passId');
        Share::checkEmptyParams(['passId'=>$passId]);
        $pass = db('pass')->where('id',$passId)->find();
        if(!$pass){
            Share::jsonData(0,'','没有该闯关活动');
        }
        if($pass['status'] != 1){
            Share::jsonData(0,'','当前闯关活动已下线');
        }
        $nowTime = date('Y-m-d H:i:s');//当前时间
        //获取报名信息
        $join = db('pass_join')->where(['uid'=>$uid,'passId'=>$passId,'endTime'=>['>=',$nowTime],'status'=>0])->find();
        if(!$join){
            Share::jsonData(0,'','你当前还没有报名该闯关活动！');
        }
        //获取当前时间段内的打卡记录
        $sign = db('pass_sign')->where(['uid'=>$uid,'passId'=>$passId,'joinId'=>$join['id'],'signTimeBegin'=>['<=',$nowTime],'signTimeEnd'=>['>=',$nowTime]])->find();
        if(!$sign){
            Share::jsonData(0,'','当前不在打卡时间内！');
        }
        if($sign['status'] == 1){
            Share::jsonData(0,'','已打卡，请勿重复打卡!');
        }
        //判断之前的打卡成功次数
        $hadSign = db('pass_sign')->where(['uid'=>$uid,'passId'=>$passId,'joinId'=>$join['id'],'number'=>['<',$sign['number']],'status'=>1])->count();
        if($hadSign < ($sign['number'] - 1)){
            //修改参加状态  未完成
            db('pass_join')->where('id',$join['id'])->update(['status'=>2]);
            Share::jsonData(0,'','您已挑战失败，打卡无效');
        }
        //打卡
        $res = db('pass_sign')->where('id',$sign['id'])->update(['status'=>1,'signTime'=>$nowTime]);
        if($res){
            //判断是否完成挑战
            if($sign['number'] == 10){//最后一轮打卡
                //修改参加状态  已完成
                db('pass_join')->where('id',$join['id'])->update(['status'=>1]);
                //发放奖励
                Share::sendPassReward($uid,$pass);
                db('pass_join')->where('id',$join['id'])->update(['isReward'=>1]);
            }
            Share::jsonData(1);
        }else{
            Share::jsonData(0,'','打卡失败，请刷新重试！');
        }
    }
    /**
     * 闯关活动
     * 我的报名
     */
    public function myPass(){
        $uid = $this->uid;
        $status = input('status',99);//99-全部  0-参与中 1-已完成 2-未完成
        $where = [
            'uid'=>$uid,
        ];
        if($status != 99){
            $where['status'] = $status;
        }
        $page = input('page',1);
        $pageSize = input('pageSize',10);
        $offset = $pageSize*($page-1);
        $total = db('pass_join')->where($where)->count();
        $data = db('pass_join')->where($where)->limit($offset,$pageSize)->order('joinTime','desc')->select();
        foreach($data as $k => $v){
            $pass = db('pass')->where('id',$v['passId'])->find();
            $data[$k]['pass'] = $pass;
        }
        $return = [
            'total'=>$total,
            'data'=>$data
        ];
        Share::jsonData(1,$return);
    }
    /**
     * 闯关活动
     * 我的签到
     */
    public function myPassSign(){
        $uid = $this->uid;
        $page = input('page',1);
        $pageSize = input('pageSize',10);
        $offset = $pageSize*($page-1);
        $total = db('pass_sign')->where(['uid'=>$uid,'status'=>1])->count();
        $data = db('pass_sign')->where(['uid'=>$uid,'status'=>1])->limit($offset,$pageSize)->order('signTime','desc')->select();
        foreach($data as $k => $v){
            $pass = db('pass')->where('id',$v['passId'])->find();
            $data[$k]['pass'] = $pass?$pass:[];
        }
        $return = [
            'total'=>$total,
            'data'=>$data,
        ];
        Share::jsonData(1,$return);
    }
    /**
     * 分享有奖
     */
    public function  shareReward(){
        $uid = $this->uid;
        //打卡次数
        $signNum = Share::getUserSignNum($uid);
        //累计收益
        $moneyGet = Share::getUserMoneyGet($uid);
        //加入天数
        $createTime = db('member')->where('id',$uid)->find()['createTime'];
        $joinDate = date('Y-m-d',$createTime);
        $now = date('Y-m-d');//今天
        $joinDays = floor((strtotime($now) - strtotime($joinDate))/86400) +1;
        $return = [
            'signNum'=>$signNum,
            'moneyGet'=>$moneyGet,
            'joinDay'=>$joinDays,
        ];
        Share::jsonData(1,$return);
    }
}