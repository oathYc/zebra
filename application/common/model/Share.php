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
    const ROOMTIME = 60;
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
                }elseif(strpos($data,"uploads/category/20") == 1){//后台上传
                    $data = $host.$data;
                }
            }
        }
        return $data;
    }
    /**
     * 验证手机号是否正确
     * @author honfei
     * @param number $mobile
     */
    public static function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^1[3,4,5,7,8,9]{1}[\d]{9}$#', $mobile) ? true : false;
    }


    /**
     * 正则表达式验证email格式
     *
     * @param string $str    所要验证的邮箱地址
     * @return boolean
     */
    public static function isEmail($str) {
        if (!$str) {
            return false;
        }
        return preg_match('#[a-z0-9&\-_.]+@[\w\-_]+([\w\-.]+)?\.[\w\-]+#is', $str) ? true : false;
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
     * $moneyType 0-充值 1-打卡 2-房间挑战 3-闯关 4-余额提现  5-下级用户奖励
     */
    public static function userMoneyRecord($uid,$money,$remark,$type,$moneyType,$isReward=0){
        $params = [
            'uid'=>$uid,
            'money'=>$money,
            'remark'=>$remark,
            'type'=>$type,
            'createTime'=>time(),
            'moneyType'=>$moneyType,
            'isReward'=>$isReward
        ];
        db('user_money_record')->insert($params);
    }
    /**
     * 用户收益记录
     * 收益统计
     * 1-打卡 2-房间挑战 3-闯关  4-邀请奖励
     */
    public static function userMoneyGet($uid,$money,$type){
        $where = [
            'uid'=>$uid,
            'type'=>$type,
        ];
        $user = db('money_get')->where($where)->find();
        if($user){//修改
            $moneyGet = $money + $user['moneyGet'];
            db('money_get')->where('id',$user['id'])->update(['moneyGet'=>$moneyGet]);
        }else{//新增
            $params = [
                'uid'=>$uid,
                'moneyGet'=>$money,
                'type'=>$type,
                'createTime'=>time(),
                'updateTime'=>date('Y-m-d H:i:s'),
            ];
            db('money_get')->insert($params);
        }
    }
    /**
     * 房间挑战
     * 扣除指定的费用
     */
    public static function reduceRoomMoney($uid,$money,$name='',$create=1){
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
            $str = $create==1?'创建':'参与';
            self::userMoneyRecord($uid,$money,$str.'房间挑战支付挑战费用-'.$name,2,2);
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
     * type 1-保底 2-普通
     */
    public static function addRoomChallenge($uid,$roomId,$joinMoney,$type=1){
        if($type ==1){//1-参与中 2-已失败 3-已完成
            $status = 3;
        }else{
            $status = 1;
        }
        $params = [
            'uid'=>$uid,
            'roomId'=>$roomId,
            'createTime'=>time(),
            'type'=>$type,//1-保底 2-普通
            'joinMoney'=>$joinMoney,
            'status'=>$status
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
//                self::returnUserApplyMoney($roomId);
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
        $roomTime = self::ROOMTIME;
        $beginTime = $room['beginTime'];//活动首次签到时间
        $roomEndTime = strtotime($room['beginDate']) + 86400*$room['day'] -1;//活动结束时间
        if( (($beginTime-$roomTime) < $now ) && ($now < $roomEndTime)){//开始挑战 且未结束
            $status = 1;//活动中
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
        $sign = self::getMemberSignMsg($uid,$room['id'],$date,$signNum);//1-房间挑战
        //签到参数
        $params = [];
        //判断是否在第一次打卡时间段内
        $firstTimeBegin = $todayTime + 60*$room['signBegin'];//开始签到时间
        $firstTimeEnd = $todayTime + 60*$room['signEnd'] + 59;//结束签到时间
        if($signNum == 1){//只设置一次签到
            if($nowTime < $firstTimeBegin){
                Share::jsonData(0,'','还没到签到时间，不能进行签到！');
            }elseif($nowTime > $firstTimeEnd){
                if($sign['firstSign'] != 1){//1-参与中 2-已失败 3-已完成
                    db('room_join')->where(['uid'=>$uid,'roomId'=>$room['id']])->update(['status'=>2]);
                    Share::jsonData(0,'','已过签到时间，你已挑战失败!');
                }
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
                if($nowTime < $secondBeginTime){
                    Share::jsonData(0,'','还没到二次签到时间，不能进行签到！');
                }elseif( $nowTime > $secondEndTime){//已过第二次签到时间
                    if($sign['secondSign'] != 1){//1-参与中 2-已失败 3-已完成
                        db('room_join')->where(['uid'=>$uid,'roomId'=>$room['id']])->update(['status'=>2]);
                        Share::jsonData(0,'','已过二次签到时间，你已挑战失败!');
                    }
                    Share::jsonData(0,'','还没到签到时间，不能进行签到！');
                }else{//判断二次是否已签到
                    if($sign['secondSign'] == 1 && $sign['secondSignTime']){
                        self::jsonData(0,'','您已签到，请勿重复签到');
                    }else{
                        //判断第一次签到是否成功
                        if($sign['firstSign'] != 1){
                            //修改参与状态
                            db('room_join')->where(['uid'=>$uid,'roomId'=>$room['id']])->update(['status'=>2]);//挑战失败
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
        $join = db('room_join')->where(['uid'=>$uid,'roomId'=>$room['id']])->find();
        if(!$join){
            Share::jsonData(0,'','您还没有报名该活动！');
        }
        if($join['status'] == 2){//不是参与中状态 1-参与中 2-已失败 3-已完成
            Share::jsonData(0,'','您已经挑战失败了，不能再继续签到了！');
        }elseIf($join['status'] == 3){
            Share::jsonData(0,'','您已经挑战成功了，无需继续签到了！');
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
                    $sign = db('sign')->where(['date'=>$signDate,'uid'=>$uid,'roomId'=>$room['id']])->find();
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
    public static function getMemberSignMsg($uid,$roomId,$date='',$signNum=1){
        $date = $date?$date:date('Y-m-d');
        $where = [
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
                'signNum'=>$signNum,
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
            $signNum = 0;
            for($i=0;$i<=$reduceDay;$i++){
                $date = $i*86400 + $beginTime;
                $targetDay = date('Y-m-d',$date);
                //是否打卡
                $hadSign = db('clock_in_sign')->where(['uid'=>$uid,'clockInId'=>$clock['id'],'joinId'=>$clockJoin['id'],'date'=>$targetDay])->find();
                if($hadSign){
                    $signNum += 1;
                    if($signNum >= $days){//已连续打满打卡天数
                        db('clock_in_join')->where(['id'=>$clockJoin['id']])->update(['status'=>2,'clockNum'=>$signNum]);//0-失败 1-参与中 2-已完成
                    }
                }else{//该天没有打卡记录
                    if($targetDay != $today){//不是今天
                        db('clock_in_join')->where(['id'=>$clockJoin['id']])->update(['status'=>0,'clockNum'=>$signNum]);//0-失败 1-参与中 2-已完成
                    }else{
                        //判断今天打卡状态
                        $joinTime = $clockJoin['createTime'];
                        //今日签到结束时间
                        $signEndTime = strtotime($targetDay.' '.$clock['endTimeStr'].":59");
                        if($joinTime < $signEndTime){//今日签到结束之前报的名
                            //判断当前时间是否已过签到时间
                            $now = time();
                            if($now >= $signEndTime){//当前已过今日打卡签到时间  即未打卡
                                db('clock_in_join')->where(['id'=>$clockJoin['id']])->update(['status'=>0,'clockNum'=>$signNum]);//0-失败 1-参与中 2-已完成
                            }
                        }
                    }

                }
            }
        }
    }
    /**
     * 打卡活动
     * 发放奖励
     */
    public static function clockInReward($uid,$joinMoney,$clock,$join){
        if($clock['rewardType'] == 1){//固定金额
            $money = $clock['reward'];
        }else{//百分比
            $money = $joinMoney * ($clock['reward']/100);
        }
        //金额规范  分
        $money = self::getDecimalMoney($money);
        $user = db('member')->where('id',$uid)->find();
        $addMoney = $user['money'] + $money;
        db('member')->where('id',$uid)->update(['money'=>$addMoney]);
        self::userMoneyRecord($uid,$money,'打卡活动每日奖励'.'-'.$clock['name'],1,1,1);
        self::rewardRecord($uid,$money,$clock['id'],1,$join['id']);
        self::userMoneyGet($uid,$money,1);
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
    public static function returnClockInMoney($uid,$money,$clock){
        $user = db('member')->where('id',$uid)->find();
        $addMoney = $user['money'] + $money;
        $res = db('member')->where('id',$uid)->update(['money'=>$addMoney]);
        if($res){
            self::userMoneyRecord($uid,$money,'打卡活动本金退还'.'-'.$clock['name'],1,1);
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
        $todayUserJoin = [];//用户参与id集合
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
                $todayUserJoin[] = $v['id'];//参与的报名id
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
                $todayUserJoin[] = $v['id'];//参与的报名id
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
                        self::userMoneyRecord($r,$userRewardMoney,'房间挑战每日奖励金发放'.'-'.$room['name'],1,2);
                        self::rewardRecord($r,$userRewardMoney,$roomId,2,$todayUserJoin[$p]);//1-打卡 2-房间挑战 3-闯关
                        self::userMoneyGet($r,$userRewardMoney,2);//收益记录
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
                    self::userMoneyRecord($w,$room['money'],'房间挑战报名费退还'.'-'.$room['name'],1,2);
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
    /**
     * 用户收益记录
     * type  1-打卡 2-房间挑战 3-闯关
     * objectId  对应的活动id
     * joinId   对应报名记录id
     */
    public static function rewardRecord($uid,$money,$objectId,$type,$joinId=0){
        $params = [];
        $date = date('Y-m-d');
        $time = time();
        if($type ==1){//打卡挑战
            $params = [
                'uid'=>$uid,
                'clockInId'=>$objectId,
                'joinId'=>$joinId,
                'date'=>$date,
                'money'=>$money,
                'createTime'=>$time,
            ];
            $res = db('clock_reward')->insert($params);
        }elseif($type ==2){//房间挑战
            $params = [
                'uid'=>$uid,
                'roomId'=>$objectId,
                'joinId'=>$joinId,
                'date'=>$date,
                'money'=>$money,
                'createTime'=>$time,
            ];
            $res = db('room_reward')->insert($params);
        }elseif($type ==3){//闯关挑战
            $params = [
                'uid'=>$uid,
                'passId'=>$objectId,
                'joinId'=>$joinId,
                'date'=>$date,
                'money'=>$money,
                'createTime'=>$time,
            ];
            $res = db('pass_reward')->insert($params);
        }else{
            return false;
        }
        return true;

    }
    /**
     * 邀请码设置
     */
    public static function getInviteCode(){
        //初次生成邀请码
        $array = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9','0');
        $total = count($array);
        for($i = 0; $i<8;$i++){
            $rand = rand(0,$total);
            $code[] = $array[$rand];
        }
        $code = implode('',$code);
        $had = db('member')->where("inviteCode = '{$code}'")->find();
        if(!$had){
            return $code;
        }else{//已有用户有改邀请码 重新生成
            self::getInviteCode();
        }
    }
    /**
     *检查用户的闯关参与状态
     * 已过闯关时间
     * 参加状态  0-参与中 1-已完成 2-未完成
     */
    public static function checkPassStatus($uid,$passId,$joinId){
        $pass = db('pass')->where('id',$passId)->find();
        $join = db('pass_join')->where('id',$joinId)->find();
        $total = db('pass_sign')->where(['uid'=>$uid,'joinId'=>$joinId,'passId'=>$passId,'status'=>1])->count();
        $number = self::getPassNumber($pass);//获取当前活动期数
        if($pass['challenge'] == $total){//挑战成功
            db('pass_join')->where('id',$joinId)->update(['status'=>1]);
        }else{
            //判断当前挑战状态
            //判断是否有未签到记录  0-暂停 1-停止（挑战结束） 2-下一轮（继续挑战）
            if($join['signStatus'] == 1){
                //停止挑战 修改状态
                db('pass_join')->where('id',$join['id'])->update(['status'=>1]);
            }elseif($join['signStatus'] == 2){//下一轮
                //判断是否挑战失败
                $now = date('Y-m-d H:i:s');
                $noSign = db('pass_sign')->where(['uid'=>$uid,'passId'=>$passId,'joinId'=>$join['id']])->order('number','desc')->find();
                if($noSign['status'] ==0){//当前未签到
                    //判断是否挑战失败
                    if($now > $noSign['signTimeEnd']){//已挑战失败 修改状态
                        db('pass_join')->where('id',$join['id'])->update(['status'=>2]);
                    }
                }
            }
        }
    }
    /**
     * 发放闯关奖励
     */
    public static function sendPassReward($uid,$pass,$joinId){
        $today = date('Y-m-d');
        $beginTime = $today.' 00:00:00';
        $endTime = $today.' 23:59:59';
        $reward  = $pass['reward'];
        $rewardType = $pass['rewardType'];
        if( $rewardType == 1){//奖励类型 1-瓜分 2-固定金额  3-报名百分比
            $count = db('pass_join')->where(['passId'=>$pass['id'],'status'=>2,'joinTime'=>['>=',$beginTime,'joinTime'=>['<=',$endTime]]])->count();
            $money = $count*$pass['money']*$reward;
            //成功打卡人数
            $success = db('pass_join')->where(['passId'=>$pass['id'],'status'=>1,'joinTime'=>['>=',$beginTime,'joinTime'=>['<=',$endTime]]])->count();
            if($money && $success){
                $money = $money/$success;
            }else{
                $money = 0;
            }
        }elseif($rewardType == 2){
            $money = $reward;
        }elseif($rewardType == 3){
            $money = $pass['money'] * $reward;
        }else{
            $money = 0;
        }
        $money = self::getDecimalMoney($money);
        //发奖励
        $user = db('member')->where('id',$uid)->find();
        $addMoney = $user['money'] + $money;
        if($money){
            $res = db('member')->where('id',$uid)->update(['money'=>$addMoney]);
            if($res){
                self::userMoneyRecord($uid,$money,'闯关奖励发送'.'-'.$pass['name'],1,3);
                self::rewardRecord($uid,$money,$pass['id'],3,$joinId);//1-打卡 2-房间挑战 3-闯关
                //收益记录
                self::userMoneyGet($uid,$money,3);
            }else{
                Share::jsonData(0,'操作失败');
            }
        }
        //退还本金
        $returnMoney = $addMoney + $pass['money'];
        $re = db('member')->where('id',$uid)->update(['money'=>$returnMoney]);
        self::userMoneyRecord($uid,$pass['money'],'闯关本金退还'.'-'.$pass['name'],1,3);
    }
    /**
     * 闯关报名
     * 检查报名时间
     */
    public static function checkPassJoinTime($pass){
        $currTime = date("H:i");
        $currMinute = self::getMinute($currTime);
        if($currMinute >= $pass['beginTime'] && $currMinute <= $pass['endTime']){
            Share::jsonData(0,'','该闯关活动只能在'.$pass['beginTimeStr'].'-'.$pass['endTimeStr'].'时间段之外才可报名！');
        }
    }
    /**
     * 闯关报名
     * 扣除用户报名费用
     */
    public static function reducePassJoinMoney($uid,$money,$pass){
        $user = db('member')->where('id',$uid)->find();
        if($user['money'] < $money){
            self::jsonData(0,'','用户余额不足，请先充值！');
        }
        $reduce = $user['money'] - $money;
        $res = db('member')->where('id',$uid)->update(['money'=>$reduce]);
        if($res){
            //记录余额消费记录
            self::userMoneyRecord($uid,$money,'闯关报名费扣除-'.$pass['name'],2,3);
        }else{
            self::jsonData(0,'','扣除闯关报名费失败，请稍后重试！');
        }
    }
    /**
     * 打卡活动
     * 扣除用户报名费用
     */
    public static function reduceClockInMoney($uid,$money,$clockName){
        $user = db('member')->where('id',$uid)->find();
        if($user['money'] < $money){
            self::jsonData(0,'','用户余额不足，请先充值！');
        }
        $reduce = $user['money'] - $money;
        $res = db('member')->where('id',$uid)->update(['money'=>$reduce]);
        if($res){
            //记录余额消费记录
            self::userMoneyRecord($uid,$money,'打卡活动报名费扣除-'.$clockName,2,1);
        }else{
            self::jsonData(0,'','扣除报名费失败，请稍后重试！');
        }
    }
    /**
     * 闯关报名
     * 报名签到生成
     */
    public static function createUserPassSign($uid,$pass,$join){
        //挑战时长
        $hour = $pass['hour'];
        $minute = $hour*60;
        //签到次数
        $number = $pass['challenge'];
        //计算每轮签到的时间间隔
        $blankMinute = floor($minute/$number);
        //开始时间
        $beginTime = strtotime($join['joinTime']);
        //获取该闯关的每轮签到时间
        $signMinutes = db('pass_time')->where('passId',$pass['id'])->find();
        //计算获取每一轮的打卡时间
        $sign = [];
        $time = time();
        for($i=1;$i<=$number;$i++){
            $randMinute = rand(1,$blankMinute);
            $signBegin = $beginTime + 60*($blankMinute*($i-1)) + 60*$randMinute;
            $keyVal = self::getKeyVal($i);
            $currSignMinute = $signMinutes[$keyVal];//单轮签到的时间长度
            $signEnd = $signBegin + 60*$currSignMinute -1;
            $signBeginTime = date('Y-m-d H:i:s',$signBegin);
            $signEndTime = date('Y-m-d H:i:s',$signEnd);
            $sign[] = [
                'uid'=>$uid,
                'passId'=>$pass['id'],
                'joinId'=>$join['id'],
                'status'=>0,//0-未打卡 1-已打卡
                'number'=>$i,//第几轮打卡
                'createTime'=>$time,
                'signTimeBegin'=>$signBeginTime,
                'signTimeEnd'=>$signEndTime,
            ];
        }
        db('pass_sign')->insertAll($sign);
    }
    /**
     * 闯关报名
     * 报名签到生成
     * 改版
     * first  1-报名 2-继续挑战
     */
    public static function createUserPassSignNew($uid,$pass,$join,$first=1){
        $signMinutes = 3;//获取该闯关的每轮签到时间   3 分钟
        $time = time();
        //挑战时长
        $hour = $pass['hour'];
        //最小分钟数
        $minMinute = $pass['min']*60;
        $maxMinute = $pass['max']*60;
        //签到次数
        $number = $pass['challenge'];
        //计算每轮签到的时间间隔
        $randMinute = rand($minMinute,$maxMinute);//随机时间段
        if($first ==1){//报名进入 生成第一次的
            //开始时间
            $beginTime = strtotime($join['joinTime']);
            $signBegin = $beginTime  + $randMinute*60 ;//报名时间加随机时间段
            $signEnd = $signBegin + 60*$signMinutes -1;
            $signBeginTime = date('Y-m-d H:i:s',$signBegin);
            $signEndTime = date('Y-m-d H:i:s',$signEnd);
            $sign = [
                'uid'=>$uid,
                'passId'=>$pass['id'],
                'joinId'=>$join['id'],
                'status'=>0,//0-未打卡 1-已打卡
                'number'=>1,//第几轮打卡
                'createTime'=>$time,
                'signTimeBegin'=>$signBeginTime,
                'signTimeEnd'=>$signEndTime,
            ];

        }else{
            //计算获取新一轮的打卡时间
            //获取当前轮数
            $hadSignMax = db('pass_sign')->where(['uid'=>$uid,'passId'=>$pass['id'],'joinId'=>$join['id'],'status'=>1])->order('number','desc')->find();
            $currNumber  = $hadSignMax['number'];
            if($currNumber >=$number){//大于等于挑战栾树
                if($join['status'] != 1){//参加状态  0-参与中 1-已完成 2-未完成
                    db('pass_join')->where('id',$join['id'])->update(['status'=>1]);
                }
                Share::jsonData(0,'','已经挑战完成');
            }
            $newNumber = $currNumber + 1;
            //当前继续的时间为开始时间
            $beginTime = time();
            $signBegin = $beginTime +  60*$randMinute;//当前时间开始  根据时间间隔计算单轮的签到时间
            $signEnd = $signBegin + 60*$signMinutes -1;
            $signBeginTime = date('Y-m-d H:i:s',$signBegin);
            $signEndTime = date('Y-m-d H:i:s',$signEnd);

            $sign = [
                'uid'=>$uid,
                'passId'=>$pass['id'],
                'joinId'=>$join['id'],
                'status'=>0,//0-未打卡 1-已打卡
                'number'=>$newNumber,//第几轮打卡
                'createTime'=>$time,
                'signTimeBegin'=>$signBeginTime,
                'signTimeEnd'=>$signEndTime,
            ];
        }
        db('pass_sign')->insert($sign);

    }
    /**
     * 键值转换
     */
    public static function getKeyVal($key){
        $arr = [
            1=>'one',
            2=>'two',
            3=>'three',
            4=>'four',
            5=>'five',
            6=>'six',
            7=>'seven',
            8=>'eight',
            9=>'night',
            10=>'ten',
        ];
        if(isset($arr[$key])){
            return $arr[$key];
        }else{
            return $arr[1];
        }
    }
    /**
     * 获取用户签到次数
     */
    public static function getUserSignNum($uid){
        //打卡
        $clockNum = db('clock_in_sign')->where(['uid'=>$uid])->count();
        //房间挑战
        $roomNum = db('sign')->where(['uid'=>$uid,'firstSign'=>1])->count();
        //闯关
        $passNum = db('pass_sign')->where(['uid'=>$uid,'status'=>1])->count();
        $signNum = $clockNum + $roomNum + $passNum;
        return $signNum?intval($signNum):0;
    }
    /**
     * 获取用户累计收益金额
     */
    public static function getUserMoneyGet($uid){
        $moneyGet = db('money_get')->where(['uid'=>$uid])->sum('moneyGet');
        return $moneyGet?$moneyGet:0;
    }
    /**
     * 房间挑战
     * 今日打卡
     */
    public static function getTodayRoomSign($uid,$roomId,$signNum){
        $today = date('Y-m-d');
        $signData = db('sign')->where(['uid'=>$uid,'roomId'=>$roomId,'date'=>$today])->find();
        if(!$signData){
            return 0;//0-未打卡
        }
        $sign = 0;
        if($signData['firstSign'] == 1){
            $sign = 1;
            if($signData['secondSign'] == 1 && $signNum == 2){
                $sign = 2;
            }
        }
        return $sign;
    }
    /**
     * 余额体现申请
     * 体现金额判断
     * 除去冻结金额
     */
    public static function checkReturnMoney($uid,$money,$procedures=0){
        $user = db('member')->where('id',$uid)->find();
        if(!$user){
            self::jsonData(0,'','没有该用户');
        }
        $returnMoney = $money + $procedures;
        if($user['money'] < $returnMoney){
            self::jsonData(0,'','你的余额（'.$user['money'].'）不足');
        }
        //获取用户当前的冻结资金 体现申请中
        $frozen = db('user_return')->where(['uid'=>$uid,'status'=>0])->sum('money');
        //可体现金额
        $canApply = $user['money'] - $frozen;
        if($canApply < $returnMoney){
            self::jsonData(0,'','排除冻结资金（'.$frozen.'），你的可提现金额（'.$canApply.'）不足！');
        }
    }
    /**
     * 实名状态判断
     * 提现申请
     * 实名认证审核状态 0-未提交 1-待审核 2-审核通过 3-审核失败
     */
    public static function checkRealNameStatus($uid){
        $user = db('member')->where('id',$uid)->find();
        if(!$user){
            self::jsonData(0,'','没有该用户！');
        }
        if($user['check'] == 0){
            self::jsonData(0,'','您还未提交实名认证审核，无法申请提现！');
        }
        if($user['check'] == 1){
            self::jsonData(0,'','您的实名认证待审核中，暂无法申请提现！');
        }
        if($user['check'] == 3){
            self::jsonData(0,'','您的实名认证审核未通过，请重新提交审核！');
        }
    }
    /**
     * 关闭已结束的闯关
     */
    public static function closePassEnd(){
        $date = date('Y-m-d H:i:s');
        $current = db('pass')->where(['status'=>1])->select();
        foreach($current as $k => $v){
            if($v['passEndTime'] <= $date){//活动已结束
                db('pass')->where('id',$v['id'])->update(['status'=>0]);
            }
        }
    }
    /**
     * 打卡活动
     * 获取昨日收益
     */
    public static function getYesterdayMoneyByClock($uid,$clockInId,$joinId){
        $yesterDay = date('Y-m-d',strtotime("-1day"));
        $moneyRecord = db('clock_reward')->where(['uid'=>$uid,'clockInId'=>$clockInId,'joinId'=>$joinId,'date'=>$yesterDay])->find();
        if($moneyRecord){
            return $moneyRecord['money'];
        }else{
            return 0;
        }
    }
    /**
     * 房间挑战
     * 获取昨日收益
     */
    public static function getYesterdayMoneyByRoom($uid,$roomId){
        $yesterDay = date('Y-m-d',strtotime("-1day"));
        $moneyRecord = db('room_reward')->where(['uid'=>$uid,'roomId'=>$roomId,'date'=>$yesterDay])->find();
        if($moneyRecord){
            return $moneyRecord['money'];
        }else{
            return 0;
        }
    }
    /**
     * 邀请奖励
     * objectId  活动id
     * type 1-打卡 2-房间挑战 3-闯关 4-邀请新人
     */
    public static function shareReward($uid,$objectId,$objectStr,$type=4){
        //判断用户是有有邀请人
        $user = db('member')->where('id',$uid)->find();
        if(!$user || !$user['inviterCode']){
            return false;
        }
        $sharer = db('member')->where('inviteCode',$user['inviterCode'])->find();
        if(!$sharer){
            return false;
        }
        $sharerUid = $sharer['id'];
        if($type != 4){
            //判断是否已经奖励过该类活动
            $hadReward = db('share_reward')->where(['uid'=>$sharerUid,'shareUid'=>$uid,'type'=>$type])->find();
            if($hadReward){
                return false;
            }
        }
        $money = $type==4?8.8:3;//新人奖励8.8  参加活动奖励3元
        $insert = [
            'uid'=>$sharerUid,
            'shareUid'=>$uid,
            'type'=>$type,
            'money'=>$money,
            'objectId'=>$objectId,
            'createTime'=> time(),
        ];
        db('share_reward')->insert($insert);
        //记录邀请奖励收益
        self::userMoneyGet($sharerUid,$money,4);
        //余额增加
        $addMoney = $sharer['money'] + $money;
        db('member')->where('id',$sharer['id'])->update(['money'=>$addMoney]);
        //余额变化记录
        $remark = $type==4?'邀请新人奖励':'参加活动挑战奖励-'.$objectStr;
        self::userMoneyRecord($sharerUid,$money,$remark,1,5);
    }
    /**
     * 闯关
     * 每日凌晨奖励结算
     */
    public static function sendPassRewardNew($uid,$rewardMoney,$pass,$joinId,$number=1){
        $user = db('member')->where('id',$uid)->find();
        if($user){
            if($rewardMoney){
                $addMoney = $user['money'] + $rewardMoney;
                $res = db('member')->where('id',$uid)->update(['money'=>$addMoney]);
                if($res){
                    //余额记录添加
                    self::userMoneyRecord($uid,$rewardMoney,'闯关活动挑战奖励-'.$pass['name'].'第'.$number.'期',1,3,1);
                    //收益记录
                    self::userMoneyGet($uid,$rewardMoney,3);
                    //收益明细记录
                    self::rewardRecord($uid,$rewardMoney,$pass['id'],3,$joinId);
                    return true;
                }else{
                    return false;
                }
            }else{//0元奖励也要记录
                //余额记录添加
                self::userMoneyRecord($uid,$rewardMoney,'闯关活动挑战奖励-'.$pass['name'].'第'.$number.'期',1,3);
                //收益明细记录
                self::rewardRecord($uid,$rewardMoney,$pass['id'],3,$joinId);
            }
        }
        return true;
    }
    /**
     * 闯关
     * 每日凌晨结算
     * 本金退还
     */
    public static function returnPassJoinMoney($uid,$joinMoney,$passName){
        $user = db('member')->where('id',$uid)->find();
        if($user){
            if($joinMoney){
                $addMoney = $user['money'] + $joinMoney;
                $res = db('member')->where('id',$uid)->update(['money'=>$addMoney]);
                if($res){
                    //余额记录添加
                    self::userMoneyRecord($uid,$joinMoney,'闯关活动挑战本金退还-'.$passName,1,3);
                    return true;
                }else{
                    return false;
                }
            }
        }
        return true;
    }
    /**
     * 房间挑战
     * 记录房间挑战的报名费信息
     */
    public static function saveRoomJoinMoney($roomId,$joinMoney){
        $insert = [];
        $time = time();
        foreach($joinMoney as $k => $v){
            $insert[] = [
                'roomId'=>$roomId,
                'price'=>$v,
                'createTime'=>$time,
            ];
        }
        db('room_price')->insertAll($insert);
    }
    /**
     * 房间挑战
     * 检查挑战状态
     */
    public static function checkJoinStatus($uid,$roomId,$signNum){
        $room = db('room_create')->where('id',$roomId)->find();
        $now = time();
        $date = $room['beginDate'];//开始日期
        $today = date('Y-m-d');//今天
        $days = $room['day'];//活动周期
        $todayTime = strtotime($today);
        $dateTime = strtotime($date);
        //计算相差天数
        $reduceDay = ($todayTime - $dateTime)/86400;
        if($reduceDay > $days && $room['status'] != 2){
            db('room_create')->where('id',$roomId)->update(['status'=>2]);//活动结束
        }
        for($i = 0 ;$i<=$reduceDay;$i++){
            $targetDay = date('Y-m-d',($dateTime+$i*86400));
            //获取当天的签到数据
            $signData = db('sign')->where(['roomId'=>$roomId,'uid'=>$uid,'date'=>$targetDay])->find();
            if($targetDay < $today){//今天之前
                if(!$signData){//没有签到数据
                    db('room_join')->where(['uid'=>$uid,'roomId'=>$roomId])->update(['status'=>2]);//修改为失败状态
                    break;
                }
                if($signData['firstSign'] == 1){//已签到
                    if($signNum != 1){//二次签到模式
                        if($signData['secondSign'] != 1){//未签到
                            db('room_join')->where(['uid'=>$uid,'roomId'=>$roomId])->update(['status'=>2]);//修改为失败状态
                            break;
                        }
                    }
                }else{//第一次没有签到
                    db('room_join')->where(['uid'=>$uid,'roomId'=>$roomId])->update(['status'=>2]);//修改为失败状态
                    break;
                }
            }else{//今天
                $targetDayTime = strtotime($targetDay);
                $firstTimeBegin = $targetDayTime + 60*$room['signBegin'];//第一次签到时间
                $firstTimeEnd = $targetDayTime + 60*$room['signEnd']  + 59;//第一次签到结束时间
                if($signNum == 1 && $now > $firstTimeEnd){
                    if(!$signData){//没有签到数据
                        db('room_join')->where(['uid'=>$uid,'roomId'=>$roomId])->update(['status'=>2]);//修改为失败状态
                        break;
                    }elseif($signData && $signData['firstSign'] != 1){//第一次没有签到
                        db('room_join')->where(['uid'=>$uid,'roomId'=>$roomId])->update(['status'=>2]);//修改为失败状态
                        break;
                    }
                }elseif($signNum == 2){
                    $secondTimeBegin = $targetDayTime + 60*($room['secondBegin']);//第二次签到开始时间
                    $secondTimeEnd = $targetDayTime + 60*$room['secondEnd'] + 59;//第二次签到结束时间
                    if(!$signData){
                        if($now > $firstTimeEnd){//已过第一次签到时间
                            db('room_join')->where(['uid'=>$uid,'roomId'=>$roomId])->update(['status'=>2]);//修改为失败状态
                            break;
                        }
                    }elseif($now > $firstTimeEnd && $signData['firstSign'] != 1){
                        db('room_join')->where(['uid'=>$uid,'roomId'=>$roomId])->update(['status'=>2]);//修改为失败状态
                        break;
                    }elseif($now > $secondTimeEnd && $signData['secondSign'] != 1){
                        db('room_join')->where(['uid'=>$uid,'roomId'=>$roomId])->update(['status'=>2]);//修改为失败状态
                        break;
                    }
                }
            }
        }
    }
    /**
     * 获取闯关期数
     */
    public static function getPassNumber($pass){
        $beginDate  = date("Y-m-d",$pass['createTime']);//开始日期
        $beginTime = strtotime($beginDate);
        $today = date('Y-m-d');//今天日期
        if($today == $beginDate){
            $number=1;//第一期
        }else{
            $now  = time();
            //出去日期获取时分秒
            $days= floor(($now - $beginTime)/86400);
            $reduceSecond = $now - 86400*$days -$beginTime;//相差的秒数
            $compareTime = 3600*8;//八小时
            if($compareTime > $reduceSecond){
                //凌晨八小时之前，算前一天的期数
                $number = $days;
            }else{
                $number = $days+ 1;//新的一期了
            }
        }
        return $number;
    }
    /**
     * 检查闯关签到时间
     * 如果当前签到时间在禁止报名时间端内  签到成功后即停止后续挑战
     * 签到成功及挑战成功
     * 不管是否挑战轮数都完成
     */
    public static function checkSignTime($pass,$joinId){
        $now = date('H:i:s');
        $beginTime = $pass['beginTimeStr'].":00";
        $endTime = $pass['endTimeStr'].':59';
        if($now > $beginTime && $now <= $endTime){
            db('pass_join')->where('id',$joinId)->update(['status'=>1]);
        }
    }
}