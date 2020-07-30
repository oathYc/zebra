<?php
namespace app\common\model;
/**
 * 陌生刘：💻
 * Created by PhpStorm.
 * User: StubbornGrass - liu
 * Date: 2019/6/13
 * Time: 17:21
 */
class Share extends \think\Model
{
    const COMMON = 20;
    const LOWEST = 10;
    /**
     * @param int $code
     * @param array $data
     * @param string $message
     * 数据json输出
     * code  0-失败  1-成功
     */
    public static function jsonData($code=1,$data=[],$message='success'){
        if($data){
            //处理域名
            $host = config('hostUrl');
            $data = self::addHost($host,$data);
        }
        $json = [
            'code'=>$code,
            'message'=>$message,
            'data'=>$data,
        ];
        $json = json_encode($json);
        die($json);
    }

    public static function checkEmptyParams($params=[]){
        foreach($params as $k => $v){
            if(empty($v)){
                $message = "参数{$k}不能为空！";
                self::jsonData(0,[],$message);
            }
        }
    }

    public static function addHost($host,$data){
//        $data =  "/uploads/avatar/20200714/0ed01490e1d19088495af62593bf609c.jpg";
        if(is_array($data)){
            foreach($data as $k => $val){
                $val = self::addHost($host,$val);
                $data[$k] = $val;
            }
        }else{
            if($data){
                if(strpos($data,"uploads/product/2020") == 1){//商品
                    $data = $host.$data;
                }elseif(strpos($data,"uploads/avatar/2020") == 1){//头像
                    $data = $host.$data;
                }elseif(strpos($data,"uploads/file/2020") == 1){//文件
                    $data = $host.$data;
                }
            }
        }
        return $data;
    }

    /*
     * 时间转换分钟
     */
    public static function getMinute($date){
        if(!$date){
            return 0;
        }
        $arr = explode(':',$date);
        if(count($arr) != 2){
            return 0;
        }
        $minute = intval($arr[0])*60 + intval($arr[1]);
        return $minute;
    }
    /**
     * 获取普通房间最低金额
     */
    public static function getCommon(){
        return self::COMMON;
    }
    /**
     * 获取保底房间最低金额
     */
    public static function getLowest(){
        return self::LOWEST;
    }
    /**
     * 用户金额记录日志
     * type  1-新增 2-减少
     */
    public static function userMoneyRecord($uid,$money,$remark,$type){
        $params = [
            'uid'=>$uid,
            'money'=>$money,
            'remark'=>$remark,
            'type'=>$type,
            'createTime'=>time(),
        ];
        db('user_money_record')->insert($params);
    }
    /**
     * 用户创建房间
     * 扣除指定的费用
     */
    public static function reduceRoomMoney($uid,$money){
        $user = db('member')->where('id',$uid)->find();
        if(!$user){
            self::jsonData(0,'','没有该用户');
        }
        $userMoney = $user['money'];
        if($userMoney < $money){
            self::jsonData(0,'','余额不足，请先充值！');
        }
        $reduce = $userMoney - $money;
        $res = db('member')->where('id',$uid)->update(['money'=>$reduce]);
        if($res){
            //记录余额使用记录
            self::userMoneyRecord($uid,$money,'创建房间支付挑战费用',2);
        }else{
            self::jsonData(0,'','扣除费用失败，请重试');
        }
    }

    /**
     * curl请求
     * post请求
     */
    public static  function curlPost($url , $data=array()){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        // POST数据

        curl_setopt($ch, CURLOPT_POST, 1);

        // 把post的变量加上

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $output = curl_exec($ch);

        curl_close($ch);
        // 显示错误信息
        if (curl_error($ch)) {
            print "Error: " . curl_error($ch);
        } else {
            // 打印返回的内容
            curl_close($ch);
        }
        return $output;

    }

    /**
     * curl请求
     * post请求
     */
    public static  function curlget($httpUrl){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $httpUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        // POST数据

        curl_setopt($ch, CURLOPT_POST, 0);

        $output = curl_exec($ch);

        curl_close($ch);
        // 显示错误信息
        if (curl_error($ch)) {
            print "Error: " . curl_error($ch);
        } else {
            // 打印返回的内容
            curl_close($ch);
        }

        return $output;

    }
    /**
     * 添加自己发起的房间挑战报名
     */
    public static function addRoomChallenge($uid,$roomId){
        $params = [
            'uid'=>$uid,
            'roomId'=>$roomId,
            'createTime'=>time(),
            'type'=>1,//1-房间挑战
        ];
        db('room_join')->insert($params);
    }
    /**
     * 获取房间当前报名的挑战人数
     * type 1-房间挑战
     */
    public static function getRoomJoinNumber($roomId,$type=1){
        $number = db('create_join')->where(['roomId'=>$roomId,'type'=>$type])->count();
        return $number?$number:0;
    }
    /**
     * 修改房间调账的状态
     * status  0-报名中   1-活动中 2-活动结束
     */
    public static function updateRoomStatus($roomId,$status){
        $room = db('room_create')->where('id',$roomId)->find();
        if($room){
            $currStatus = $room['status'];
            if($status > $currStatus){//修改状态应该大于当前房间状态
                db('room_create')->where(['id'=>$roomId])->update(['status'=>$status]);
                //退还参赛者的本金
                self::returnUserApplyMoney($roomId);
            }
        }
    }
    /**
     * 房间跳转结束
     * 退还坚持者的本金
     */
    public static function returnUserApplyMoney($roomId){

    }
    /**
     * 房间报名
     * 检查房间状态
     * status  0-报名中   1-活动中 2-活动结束
     */
    public static function checkRoomBegin($room){
        $today = date('Y-m-d');//当天时间
        $todayTime = strtotime($today);//当天时间 时间戳
        $currTime = time();//当前时间戳
        $firstBegin = $room['signBegin'];//第一次签到开始时间 单位分钟
        $beginDay = $room['beginTime'];//活动开始时间
        $beginDayTime = strtotime($beginDay);//活动开始时间 时间戳
        $days = $room['days'];//周期 单位天
        $roomEndTime = $beginDayTime + $days*86400 - 1;//房间挑战结束时间
        if($todayTime > $beginDayTime){//活动已经开始
            if($todayTime < $roomEndTime){//还在活动中
                if($room['status'] != 1){
                    db('room_create')->where('id',$room['id'])->update(['status'=>1]);
                }
                Share::jsonData(0,'','挑战已经开始啦，不能再报名了！');
            }else{//活动已经结束
                if($room['status'] != 2){
                    db('room_create')->where('id',$room['id'])->update(['status'=>2]);
                }
                Share::jsonData(0,'','挑战已经结束啦！');
            }
        }elseif($todayTime == $beginDayTime){//当天开始 判断是否在首次打卡开始时间之前
//            $firstBeginTime = $todayTime + 60*$firstBegin;//首次打卡开始时间的时间戳
            if($room['status'] != 1){//设置挑战开始  已经开始就不能再报名
                db('room_create')->where('id',$room['id'])->update(['status'=>1]);
            }
//            if($firstBeginTime <= $currTime){
//                Share::jsonData(0,'','挑战已经开始了，不能再报名了！');
//            }
        }
    }
    /**
     * 检查房间挑战状态
     * status  0-报名中   1-活动中 2-活动结束
     */
    public static function checkRoomStatus(&$room){
        $now = time();
        $beginTime = $room['beginTime'];//活动首次签到时间
        $roomEndTime = strtotime($room['beginTimeDate']) + 86400*$room['days'] -1;//活动结束时间
        if($beginTime >= $now){//已经开始挑战
            $status = 1;//活动中
        }elseif($now >= $roomEndTime){//活动已经结束
            $status  = 2;
        }else{
            $status = 0;
        }
        if($room['status'] != $status){
            $room['status'] = $status;
            self::updateRoomStatus($room['id'],$status);
        }
    }

    /**
     * 房间挑战
     * 用户签到
     */
    public static function roomSign($uid,$room){
        $signNum = $room['signNum'];//签到次数
        $date = date('Y-m-d');
        $signTime = date('Y-m-d H:i:s');
        $todayTime = strtotime($date);
        $nowTime = time();//当前时间
        //获取用户的当天打卡记录
        $sign = self::getMemberSignMsg($uid,$date,1);//1-房间挑战
        //签到参数
        $params = [];
        //判断是否在第一次打卡时间段内
        $firstTimeBegin = $todayTime + 60*$room['signBegin'];//开始签到时间
        $firstTimeEnd = $todayTime + 60*$room['signEnd'] + 59;//结束签到时间
        if($signNum == 1){//只设置一次签到
            if($nowTime < $firstTimeBegin || $nowTime > $firstTimeEnd){
                Share::jsonData(0,'','还没到签到时间，不能进行签到！');
            }else{//判断是否已经签到  首次签到
                if($sign['firstSign'] == 1 && $sign['firstSignTime']){
                    self::jsonData(0,'','您已签到，请勿重复签到');
                }else{//进行签到
                    //判断当前用户的参与状态
                    self::checkUserJoinStatus($uid,$room);
                    $params['firstSign'] = 1;
                    $params['firstSignTime'] = $signTime;
                }
            }
        }else{
            //获取二次签到时间
            $secondBeginTime = $todayTime + 60*$room['secondBegin'];//二次开始签到时间
            $secondEndTime = $todayTime + 60*$room['secondEnd'] + 59;//二次结束签到时间
            if($nowTime < $firstTimeBegin){
                Share::jsonData(0,'','还没到签到时间，不能进行签到！');
            }elseif( $nowTime > $firstTimeEnd){//判断第二次签到时间
                if($nowTime < $secondBeginTime || $nowTime > $secondEndTime){
                    Share::jsonData(0,'','还没到签到时间，不能进行签到！');
                }else{//判断二次是否已签到
                    if($sign['secondSign'] == 1 && $sign['secondSignTime']){
                        self::jsonData(0,'','您已签到，请勿重复签到');
                    }else{
                        //判断当前用户的参与状态
                        self::checkUserJoinStatus($uid,$room);
                        $params['secondSign'] = 1;
                        $params['secondSignTime'] = $signTime;
                    }
                }
            }else{//判断是否已签到  第一次签到
                if($sign['firstSign'] == 1 && $sign['firstSignTime']){
                    self::jsonData(0,'','您已签到，请勿重复签到');
                }else{//进行签到
                    //判断当前用户的参与状态
                    self::checkUserJoinStatus($uid,$room);
                    $params['firstSign'] = 1;
                    $params['firstSignTime'] = $signTime;
                }
            }
        }
        if($params){
            $res = db('sign')->where(['uid'=>$sign['uid'],'type'=>$sign['type'],'roomId'=>$sign['roomId'],'date'=>$sign['date']])->update($params);
            if($res){
                self::jsonData(1);
            }else{
                self::jsonData(0,'','签到失败，请重试！');
            }
        }

    }
    /**
     * 检查当前用户参与挑战的状态
     *
     */
    public static function checkUserJoinStatus($uid,$room){
        $roomId = $room['id'];
        $join = db('room_join')->where(['uid'=>$uid,'roomId'=>$room['id'],'type'=>1])->find();
        if(!$join){
            Share::jsonData(0,'','您还没有报名该活动！');
        }
        if($join['status'] != 1){//不是参与中状态
            Share::jsonData(0,'','您已经挑战失败了，不能再继续签到了！');
        }else{//判断当前用户今天之前是否有断签的记录
            $begin = $room['beginTimeDate'];
            $beginTime = strtotime($begin);
            $days = $room['days'];//挑战周期
            $endDate = strtotime($begin) + 86400*($days-1);//结束日期
            $endDate = date('Y-m-d',$endDate);
            $today = date('Y-m-d');
            $signNum = $room['signNum'];//签到次数
            if($today > $endDate){//超过结束日期
                if($room['status'] != 2){
                    self::updateRoomStatus($room['id'],2);
                }
                Share::jsonData(0,'','当前挑战已经结束了');
            }
            $error = 0;
            for($i=0;$i<$days;$i++){
                $signDate = date('Y-m-d',($beginTime + $i*86400));
                if($signDate < $today){//签到时间小于今天
                    $sign = db('sign')->where(['date'=>$signDate,'uid'=>$uid,'roomId'=>$room['id'],'type'=>1])->find();
                    if($sign['firstSign'] == 1){//已签到
                        if($signNum != 1){//二次签到模式
                            if($sign['secondSign'] != 1){//未签到
                                db('room_join')->where(['uid'=>$uid,'roomId'=>$roomId])->update(['status'=>2]);//修改为失败状态
                                $error = 1;
                                break;
                            }
                        }
                    }else{//第一次没有签到
                        db('room_join')->where(['uid'=>$uid,'roomId'=>$roomId])->update(['status'=>2]);//修改为失败状态
                        $error = 1;
                        break;
                    }
                }
            }
            if($error == 1){
                Share::jsonData(0,'','您已挑战失败，签到无效！');
            }
        }
    }

    /**
     * 获取用户某天的签到记录
     * 不存在则实例化
     * type 1-房间挑战
     */
    public static function getMemberSignMsg($uid,$roomId,$date='',$type=1){
        $date = $date?$date:date('Y-m-d');
        $where = [
            'type'=>1,
            'uid'=>$uid,
            'date'=>$uid,
            'roomId'=>$roomId,
        ];
        $sign = db('sign')->where($where)->find();
        if(!$sign){//初始化  插入当天的打卡记录初始数据
            $params = [
                'uid'=>$uid,
                'roomId'=>$roomId,
                'date'=>$date,
                'type'=>$type,
                'createTime'=>time(),
            ];
            db('sign')->insert($params);
            $sign = db('sign')->where($where)->find();
        }
        return $sign;
    }
}