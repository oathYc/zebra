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
    const COMMONMIN = 20;
    const COMMONMAX = 100000;
    const LOWESTMIN = 10;
    const LOWESTMAX = 100000;
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
//        $data =  "/uploads/avatar/20200804/mr.jpg";
        if(is_array($data)){
            foreach($data as $k => $val){
                $val = self::addHost($host,$val);
                $data[$k] = $val;
            }
        }else{
            if($data){
                if(strpos($data,"uploads/product/20") == 1){//商品
                    $data = $host.$data;
                }elseif(strpos($data,"uploads/avatar/20") == 1){//头像
                    $data = $host.$data;
                }elseif(strpos($data,"uploads/file/20") == 1){//文件
                    $data = $host.$data;
                }
            }
        }
        return $data;
    }

    /**
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
     * type 1-最低 2-最高
     */
    public static function getCommon($type =1){
        $minSet = db('room_type')->where('type',2)->find();//1-保底房间 2-普通房间
        if($type ==1){
            return isset($minSet['minMoney'])?$minSet['minMoney']:self::COMMONMIN;
        }else{
            return isset($minSet['maxMoney'])?$minSet['maxMoney']:self::COMMONMAX;
        }
    }
    /**
     * 获取保底房间最低金额
     * type 1-最低 2-最高
     */
    public static function getLowest($type =1){
        $minSet = db('room_type')->where('type',1)->find();//1-保底房间 2-普通房间
        if($type ==1){
            return isset($minSet['minMoney'])?$minSet['minMoney']:self::LOWESTMIN;
        }else{
            return isset($minSet['maxMoney'])?$minSet['maxMoney']:self::LOWESTMAX;
        }
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
            self::userMoneyRecord($uid,$money,'参与房间挑战支付挑战费用',2);
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
        $number = db('room_join')->where(['roomId'=>$roomId,'type'=>$type])->count();
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
        $roomEndTime = strtotime($room['beginDate']) + 86400*$room['day'] -1;//活动结束时间
        if( ($beginTime < $now ) && ($now < $roomEndTime)){//开始挑战 且未结束
            $status = 1;//活动报名中
        }elseif($now > $roomEndTime){//活动已经结束
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
        $sign = self::getMemberSignMsg($uid,$room['id'],$date,1);//1-房间挑战
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
                        //判断第一次签到是否成功
                        if($sign['firstSign'] != 1){
                            //修改参与状态
                            db('room_join')->where(['uid'=>$uid,'roomId'=>$room['id'],'type'=>1])->update(['status'=>2]);//挑战失败
                            self::jsonData(0,'','你已挑战失败，签到无效！（今日首次签到失败）');
                        }
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
            $res = db('sign')->where('id',$sign['id'])->update($params);
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
            $begin = $room['beginDate'];
            $beginTime = strtotime($begin);
            $days = $room['day'];//挑战周期
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
            'date'=>$date,
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
    /**
     * 判断当前用户的打卡参与状态
     * 打卡活动
     */
    public static function checkClockInStatus($uid,$clockJoin,$clock){
        if($clockJoin['status'] == 1){//参与中
            $begin = $clockJoin['beginTime'];
            $days = $clock['days'];//需打卡天数
            $today = date('Y-m-d');//今天
            $todayTime = strtotime($today);
            $beginTime =  strtotime($begin);
            //相差天数
            $reduceDay = floor($todayTime - $beginTime)/86400;//今天减报名时间
            if($reduceDay > 0){//大于一天
                $signNum = 0;
                for($i=0;$i<$reduceDay;$i++){
                    $date = $i*86400 + $beginTime;
                    $targetDay = date('Y-m-d',$date);
                    //是否打卡
                    $hadSign = db('clock_in_sign')->where(['uid'=>$uid,'clockInId'=>$clock['id'],'joinId'=>$clockJoin['id'],'date'=>$targetDay])->find();
                    if($hadSign){
                        $signNum += 1;
                        if($signNum >= $days){//已连续打满打卡天数
                            db('clock_in_join')->where(['id'=>$clockJoin['id']])->update(['status'=>2,'clockNum'=>$signNum]);//0-失败 1-参与中 2-已完成
                        }
                    }else{//当天没打卡  参与失败 修改状态
                        db('clock_in_join')->where(['id'=>$clockJoin['id']])->update(['status'=>0,'clockNum'=>$signNum]);//失败
                    }
                }
            }
        }
    }
    /**
     * 打卡活动
     * 发放奖励
     */
    public static function clockInReward($uid,$joinMoney,$clock){
        if($clock['rewardType'] == 1){//固定金额
            $money = $clock['reward'];
        }else{//百分比
            $money = $joinMoney * $clock['reward'];
        }
        //金额规范  分
        $money = self::getDecimalMoney($money);
        $user = db('member')->where('id',$uid)->find();
        $addMoney = $user['money'] + $money;
        $res = db('member')->where('id',$uid)->update(['money'=>$addMoney]);
        if($res){
            self::userMoneyRecord($uid,$money,'打卡活动每日奖励',1);
        }
    }
    /**
     * 金额获取
     * 两位小数
     * 单位分
     */
    public static function getDecimalMoney($money){
        if($money){
            return floor(100*$money)/100;
        }else{
            return 0;
        }
    }

    /**
     * 打卡活动
     * 挑战成功
     * 退还本金
     */
    public static function returnClockInMoney($uid,$money){
        $user = db('member')->where('id',$uid)->find();
        $addMoney = $user['money'] + $money;
        $res = db('member')->where('id',$uid)->update(['money'=>$addMoney]);
        if($res){
            self::userMoneyRecord($uid,$money,'打卡活动本金退还',1);
        }else{
            Share::jsonData(0,'','本金退还失败');
        }
    }
    /**
     * 获取房间类型描述
     */
    public static function getRoomTypeStr($type){
        $arr = [
            1=>'保底房间',
            2=>'普通房间',
        ];
        if(isset($arr[$type])){
            return $arr[$type];
        }else{
            return '';
        }
    }
    /**
     * 房间挑战
     * 奖励发放  当天失败金的金额发送
     * 完成退还报名费
     * finish 0-未完成 1-已完成
     * day  房间挑战天数
     */
    public static function roomEveryDayReward($roomId,$finish = 0){
        //获取活动中和已完成的报名信息 1-参与中 2-已失败 3-已完成
        $join = db('room_join')->where(['status'=>1,'roomId'=>$roomId,'type'=>1])->select();
        //房间信息
        $room = db('room_create')->where('id',$roomId)->find();
        if(!$room){
            return false;
        }
        $day = $room['day'];//房间挑战天数
        $finishUser = [];//完成挑战用户
        $todaySign = [];//今日签到用户
        $signNum = $room['signNum'];//房间签到数
        $today = date('Y-m-d');//今日时间
        $failNum = 0;//失败者数量  昨天打卡 今日未打卡的才算
        $failUser = [];//失败者uid
        $yesterday  =  date('Y-m-d',(strtotime($today) - 86400));//昨天时间
        foreach($join as $k => $v){
            //今日是否已打卡
            $isSign = db('sign')->where(['uid'=>$v['uid'],'date'=>$today,'roomId'=>$roomId,'type'=>1])->find();
            if(!$isSign){//今日未打卡
                //修改参与状态  失败
                db('room_join')->where('id',$v['id'])->update(['status'=>2]);
                //判断昨天是否打卡
                $yesterSign = db('sign')->where(['uid'=>$v['uid'],'date'=>$yesterday,'roomId'=>$roomId,'type'=>1])->find();
                if($yesterSign){//昨日已打卡
                    $failNum += 1;
                    $failUser[] = $v['uid'];
                }else{
                    continue;//结束当前循环
                }
            }elseif($signNum == 1 && $isSign['firstSign'] ==1){//一次签到
                $todaySign[] =$v['uid'];//今日完成打卡的用户
                if($finish == 1){//活动结束 判断是否都完成挑战
                    $hadSign = db('sign')->where(['uid'=>$v['uid'],'roomId'=>$roomId,'type'=>1])->group('date')->count();
                    if($hadSign == $day){//打卡天数完成
                        $finishUser[] = $v['uid'];
                        //修改参与状态  完成
                        db('room_join')->where('id',$v['id'])->update(['status'=>3]);
                    }else{
                        //修改参与状态  失败
                        db('room_join')->where('id',$v['id'])->update(['status'=>2]);
                    }
                }
            }elseif($signNum == 2 && $isSign['firstSign'] == 1 && $isSign['secondSign'] == 1){
                //二次签到
                $todaySign[] = $v['uid'];
                if($finish == 1){//活动结束 判断是否都完成挑战
                    $hadSign = db('sign')->where(['uid'=>$v['uid'],'roomId'=>$roomId,'type'=>1])->group('date')->count();
                    if($hadSign == $day){//打卡天数完成
                        $finishUser[] = $v['uid'];
                        //修改参与状态  完成
                        db('room_join')->where('id',$v['id'])->update(['status'=>3]);
                    }else{
                        //修改参与状态  失败
                        db('room_join')->where('id',$v['id'])->update(['status'=>2]);
                    }
                }
            }else{//今日没有完成打卡
                //修改参与状态  失败
                db('room_join')->where('id',$v['id'])->update(['status'=>2]);
                //判断昨天是否打卡
                $yesterSign = db('sign')->where(['uid'=>$v['uid'],'date'=>$yesterday,'roomId'=>$roomId,'type'=>1])->find();
                if($yesterSign){//昨日已打卡
                    $failNum += 1;
                    $failUser[] = $v['uid'];
                }else{
                    continue;//结束当前循环
                }
            }
        }
        //计算失败金
        $failMoney = $failNum * $room['money'];
        $rewardMoney = 0;//失败金
        $userRewardMoney = 0;//每人的奖励金额
        if($todaySign){//发放奖励金
            $userRewardMoney = 0;//每个人的奖励金额
            $percent = db('room_type')->where('type',$room['type'])->find()['percent'];//发放比例  百分比
            if($failMoney && $percent){
                $rewardMoney = ($failMoney*($percent/100));//奖励金额
                $userRewardMoney = $rewardMoney/count($todaySign);//每个人所得到的金额
                $userRewardMoney = self::getDecimalMoney($userRewardMoney);
            }
            if($userRewardMoney){
                foreach($todaySign as $p => $r){//发放奖励金额
                    $user = db('member')->where('id',$r)->find();
                    $addMoney = $user['money'] + $userRewardMoney;
                    $res = db('member')->where('id',$r)->update(['money'=>$addMoney]);
                    if($res){
                        self::userMoneyRecord($r,$userRewardMoney,'房间挑战每日奖励金发放',1);
                    }
                }
            }
        }
        if($finishUser){//退还报名费
            foreach($finishUser as $e => $w){
                $user = db('member')->where('id',$w)->find();
                $addMoney = $user['money'] + $room['money'];
                $res = db('member')->where('id',$w)->update(['money'=>$addMoney]);
                if($res){
                    self::userMoneyRecord($w,$room['money'],'房间挑战报名费退还',1);
                }
            }
        }
        //房间挑战数据记录
        $params = [
            'roomId'=>$roomId,
            'date'=>$today,
            'signSuccess'=>count($todaySign),
            'signFail'=>$failNum,
            'failMoney'=>$rewardMoney,
            'createTime'=>time(),
            'rewardMoney'=>$userRewardMoney,
            'finish'=>$finish,
            'finishNum'=>count($finishUser),
            'roomBegin'=>$room['beginDate'],
        ];
        db('room_record')->insert($params);
    }
}