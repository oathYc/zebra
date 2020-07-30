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
            }
        }
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
            $firstBeginTime = $todayTime + 60*$firstBegin;//首次打卡开始时间的时间戳
            if($firstBeginTime <= $currTime){
                Share::jsonData(0,'','挑战已经开始了，不能再报名了！');
            }
        }
    }
}