<?php
/**
 * 定时任务
 * 定时扫描执行
 */

namespace app\api\controller;

use app\common\model\Share;
use think\Controller;



header("Access-Control-Allow-Origin:*");
class Task extends Controller
{
   /**
    * 房间挑战
    * 活动开始判断
    * 开始时间判断
    * 每2分钟扫描一次
    * 提前2分钟开启活动
    * http://cg.aoranjianzhong.com/api/task/roomBeginCheck
    */
   public function roomBeginCheck(){
        $date = date('Y-m-d');
        //获取还在报名中的房间挑战信息 状态 0-报名中   1-活动中 2-活动结束
       $room = db('room_create')->where(['beginDate'=>$date,'status'=>0])->select();
       $now = time();//当前时间戳
       foreach($room as $k => $v){
           $beginTime = $v['beginTime'];//活动第一次开始签到时间
           $roomTime = Share::ROOMTIME;
           $roomBegin = $beginTime - $roomTime;//提前2分钟开始时间
           if($now >=($roomBegin)){//活动已经开始
               db('room_create')->where('id',$v['id'])->update(['status'=>1]);//修改状态为活动中
           }
       }
   }
   /**
    * 房间挑战
    * 活动奖励发放
    * 结束判断
    * 每晚23:59:58扫描执行
    * http://cg.aoranjianzhong.com/api/task/roomEndCheck
    */
   public function roomEndCheck(){
        $nowTime = time();//当前时间
       //获取活动中的房间挑战信息 状态 0-报名中   1-活动中 2-活动结束
       //isEnd   0-未结算  1-已结算
       $room = db('room_create')->where('isEnd',0)->select();
        foreach($room as $k => $v){
            $beginDate = $v['beginDate'];//开始日期
            $days = $v['day'];//活动周期
            $endSign = $v['signNum'] == 1?$v['signEnd']:$v['secondEnd'];//判断打卡次数
            $endTime = strtotime($beginDate) + 86400*($days-1) + 60*$endSign;//最后打卡时间
            if($nowTime > $endTime){//已过最后的打卡时间  活动结束
                //计算挑战情况
                self::updateRoomJoinStatus($v);
                //发放奖励
                self::sendRoomReward($v);
                db('room_create')->where('id',$v['id'])->update(['status'=>2,'isEnd'=>1]);//活动结束  奖励发放
            }
        }
   }
    /**
     * 处理活动报名的状态
     * 判断用户的参与情况
     * 每晚23:59:58扫描执行
     * http://cg.aoranjianzhong.com/api/task/checkUserJoin
     */
    public function checkUserJoin(){
        $today = date('Y-m-d');
        //打卡活动
        self::checkClockJoin($today);
        //闯关
        self::checkPassJoin();
        //房间挑战
        self::checkRoomJoin($today);

    }

   /**
    * 闯关活动
    * 活动奖励结算
    * 每天八点一分
    * http://cg.aoranjianzhong.com/api/task/passRewardSend
    */
   public function passRewardSend(){
       //获取今天八点结束挑战的活动
       $date = date('Y-m-d');
       $yesterDay = date('Y-m-d',(strtotime($date)-86400));
       $beginTime = $yesterDay.' 08:00:00';//昨天八点
       //八点五分时间
       $endTime = $date.' 08:00:10';
       $pass = db('pass')->select();

       foreach($pass as $k => $v){
           //获取所有报名信息 （昨天八点到今天八点的报名数据）
           //获取要结算的活动期数
           $beginTime = strtotime(date("Y-m-d",$v['createTime']));
           $todayTime = strtotime($date);
           $number = floor(($todayTime-$beginTime)/86400);
           if($number == 0){
               $number = 1;//处理为第一期
           }else{
               $reduceSecond = $v['createTime'] - $beginTime;
               if($reduceSecond < 3600*8){//在今天凌晨到八点之前创建的活动
                   $number += 1;
               }
           }
           $allJoin = db('pass_join')->where(['passId'=>$v['id'],'number'=>$number,'isReward'=>0])->select();
           $userSign = [];//用户签到信息
           $totalChallenge = count($allJoin);//挑战人数
           $challengeSuccess = 0;//挑战成功人数
           foreach($allJoin as $o => $p){
               //判断报名挑战的挑战成功轮数
               $signNumber = self::getUserSignNumber($p['uid'],$p['passId'],$p['id']);
               //判断用户是否挑战成功 成功退还本金
               $moneyReturn = self::getUserChallengeSuccess($p);
               $userSign[] = [
                   'uid'=>$p['uid'],
                   'signNumber'=>$signNumber,
                   'moneyReturn'=>$moneyReturn,//1—退本金 0-不退
                   'joinId'=>$p['id'],
                   'joinMoney'=>$p['joinMoney'],
               ];
               if($moneyReturn ==1){//退本金即挑战成功
                   $challengeSuccess += 1;
               }
           }
           $challengeFail = $totalChallenge - $challengeSuccess;//失败人数
           //判断活动奖励模式 奖励类型 1-失败金额瓜分百分比 2-固定金额  3-报名百分比
           $rewardType = $v['rewardType'];
           if($rewardType == 1){
                //失败金奖励金额
               $failMoney = $challengeFail*$v['money']*($v['reward']/100);
               //每人奖励金额
               if($failMoney && $challengeSuccess){
                   $rewardMoney = $failMoney/$challengeSuccess;
               }else{
                    $rewardMoney = 0;
               }
           }elseif($rewardType == 2){
               $rewardMoney = $v['reward'];
           }else{
               $rewardMoney = 0;
//               $rewardMoney = $v['money'] * ($v['reward']/100);
           }
           $rewardMoney = Share::getDecimalMoney($rewardMoney);
           //奖励发放
           foreach($userSign as $t => $val){
               $challengeNumber = $val['signNumber'];
               $uid = $val['uid'];
               $return = $val['moneyReturn'];
               $joinId = $val['joinId'];
               if($rewardType == 1){
                   $userMoney = $rewardMoney;
               }elseif($rewardType == 2){
                   $userMoney = $rewardMoney*intval($challengeNumber);//按挑战轮数计算
               }else{
                   $rewardMoney = $val['joinMoney'] * ($v['reward']/100);
                   $userMoney = $rewardMoney*intval($challengeNumber);//按挑战轮数计算
               }
               //判断是否已发改奖励 避免重复发放
               $checkDate = date('Y-m-d');
               $isReward = db('pass_reward')->where(['uid'=>$uid,'passId'=>$v['id'],'joinId'=>$joinId,'date'=>$checkDate])->find();
               if($isReward){
//                   修改对应的奖励发送状态
                   db('pass_join')->where('id',$joinId)->update(['isReward'=>1]);//奖励状态
                   continue;
               }
               //奖励发放
               Share::sendPassRewardNew($uid,$userMoney,$v,$joinId,$number);
               //本金退还
               if($return == 1){
                    Share::returnPassJoinMoney($uid,$v['money'],$v['name']);
               }
               //修改对应的奖励发送状态
               db('pass_join')->where('id',$joinId)->update(['isReward'=>1]);//奖励状态
           }
           //判断当前状态 状态 0-下线 1-活动中
//           if($v['status'] == 1){//活动中但是已到结束时间
//               db('pass')->where('id',$v['id'])->update(['status'=>0,'isEnd'=>1]);
//           }
       }
   }

   /**
    * 获取当前用户的挑战签到轮数
    */
   public static function getUserSignNumber($uid,$passId,$joinId){
       if(!$uid || !$passId || !$joinId ){
           return 0;
       }
       $hadSign = db('pass_sign')->where(['uid'=>$uid,'passId'=>$passId,'joinId'=>$joinId,'status'=>1])->order('number')->count();
       if($hadSign){
           return $hadSign;
       }else{
           return 0;
       }
   }
   /**
    * 判断用户当前挑战的状态
    * status 参加状态  0-参与中 1-已完成 2-未完成
    */
   public static function getUserChallengeSuccess($join){
       if(!$join || $join['status'] == 2){
           return 0 ;// 0-失败 1-成功 退还本金
       }
       if($join['status'] == 1){
           $signError = db('pass_sign')->where(['uid'=>$join['uid'],'passId'=>$join['passId'],'joinId'=>$join['id'],'status'=>2])->find();
            //是否有签到失败的数据
           if($signError){
               db('pass_join')->where('id',$join['id'])->update(['status'=>2]);//参加状态  0-参与中 1-已完成 2-未完成
               return 0;
           }
           return 1;
       }
       if($join['status'] == 0){
           //判断当前签到状态 参与签到状态 0-暂停 1-停止（挑战结束） 2-下一轮（继续挑战）
           if($join['signStatus'] == 0 || $join['signStatus'] == 1){
               db('pass_join')->where('id',$join['id'])->update(['status'=>1]);
               return 1;
           }else{//下一轮挑战中
               //判断是否有未签到的记录
               $noSign = db('pass_sign')->where(['uid'=>$join['uid'],'passId'=>$join['passId'],'joinId'=>$join['id'],'status'=>0])->find();
               if($noSign){//存在 判断当前签到的结束时间 已过签到结束时间说明挑战失败 未过咋忽略该轮挑战签到  闯关暂停引起的
                   $now = date('Y-m-d H:i:s');
                   if($noSign['signTimeEnd'] >= $now){
                       $status = 1;
                       $success = 1;//1-退还本金 0-不退
                   }else{//挑战失败
                       $status = 2;
                       $success = 0;
                   }
                    db('pass_join')->where('id',$join['id'])->update(['status'=>$status]);
                   return $success;
               }else{//没有
                   //判断是否有签到失败的数据
                   $signError = db('pass_sign')->where(['uid'=>$join['uid'],'passId'=>$join['passId'],'joinId'=>$join['id'],'status'=>2])->find();
//                    没有说明都签到成功
                   if($signError){
                       db('pass_join')->where('id',$join['id'])->update(['status'=>2]);//参加状态  0-参与中 1-已完成 2-未完成
                        return 0;
                   }else{
                       db('pass_join')->where('id',$join['id'])->update(['status'=>1]);
                       return 1;
                   }
               }
           }
       }
       return 0;
   }

   /**
    * 判断用户打卡参与状态
    */
   public static function checkClockJoin($today){
       //状态  0-失败 1-参与中 2-已完成
       $joinData = db('clock_in_join')->where(['status'=>1])->select();
       foreach($joinData as $k => $v){
           //判断当前是不是已经挑战失败
            $todaySign = db('clock_in_sign')->where(['uid'=>$v['uid'],'joinId'=>$v['id'],'clockInId'=>$v['clockInId'],'date'=>$today])->find();
            if(!$todaySign){//当天未签到  挑战失败
                db('clock_in_join')->where('id',$v['id'])->update(['status'=>0]);
            }
       }
   }
   /**
    * 判断用户闯关参与状态
    */
   public static function checkPassJoin(){
        //状态 参加状态  0-参与中 1-已完成 2-未完成
       $joinData = db('pass_join')->where(['status'=>0])->select();
       foreach($joinData as $k => $v){
           //签到状态 signStatus 参与签到状态 0-暂停 1-停止（挑战结束） 2-下一轮（继续挑战）
           if($v['signStatus'] == 2){//判断最近一轮签到是否未签到且过签到结束时间
               $noSign = db('pass_sign')->where(['uid'=>$v['uid'],'passId'=>$v[
                   'passId'],'joinId'=>$v['id'],'status'=>0])->find();
               $now = date('Y-m-d H:i:s');
               if($noSign && $now > $noSign['signTimeEnd']){ //有待签到且已过签到结束时间
                   db('pass_join')->where('id',$v['id'])->update(['status'=>2]);

               }
           }
       }
   }
   /**
    * 判断用户房间挑战参与状态
    */
   public static function checkRoomJoin($today){
       //1-参与中 2-已失败 3-已完成
        $joinData = db('room_join')->where(['status'=>1])->select();
        $now = time();//当前时间
        $dayTime = strtotime(date('Y-m-d'));
        foreach($joinData as $k => $v){
            $sign = db('sign')->where(['uid'=>$v['uid'],'roomId'=>$v['roomId'],'date'=>$today])->find();
            $room = db('room_create')->where('id',$v['roomId'])->find();
            if(!$room){
                db('room_join')->where('id',$v['id'])->update(['status'=>2]);
            }else{
                if(!$sign){//打卡失败
                    db('room_join')->where('id',$v['id'])->update(['status'=>2]);
                }else{
                    $signNumber = $room['signNum'];
                    $firstSignEnd = $dayTime + $room['signEnd']*60 +59;//第一次签到结束时间
                    //判断打卡时间
                    if($sign['firstSign'] != 1 && $now > $firstSignEnd){
                        db('room_join')->where('id',$v['id'])->update(['status'=>2]);
                    }elseif($signNumber == 2 && $sign['firstSign'] ==1 && $sign['secondSign'] != 1){
                        $secondSignEnd = $dayTime + $room['secondEnd']*60 + 59;
                        if($now > $secondSignEnd){
                            db('room_join')->where('id',$v['id'])->update(['status'=>2]);
                        }
                    }
                }
            }
        }
   }
   /**
    * 房间挑战
    * 活动结束
    * 奖励清算
    */
   public static function updateRoomJoinStatus($room){
        //获取报名数据
       $joinData = db('room_join')->where(['roomId'=>$room['id']])->select();
       $type = $room['type'];//1-保底 2-普通
       $days = $room['day'];//活动周期
       $signNum = $room['signNum'];
       //检查参加状态
       foreach($joinData as $k => $v){//只管参与中的
           if($v['status'] == 1 ){//1-参与中 2-已失败 3-已完成
               //获取签到数据
               $signData = db('sign')->where(['uid'=>$v['uid'],'roomId'=>$room['id']])->group('date')->select();
               $signCount = count($signData);
               if($signCount < $days){//签到天数不够
                   db('room_join')->where('id',$v['id'])->update(['status'=>2]);
               }else{
                   foreach($signData as $o => $p){//检查打卡次数
                       if($p['firstSign'] != 1){//第一次签到失败
                           db('room_sign')->where('id',$v['id'])->update(['status'=>2]);
                           break;
                       }else{
                           if($signNum != 1 && $p['secondSign'] != 1){//有二次签到但是未签到
                               db('room_sign')->where('id',$v['id'])->update(['status'=>2]);
                               break;
                           }
                       }
                   }
               }
           }
       }
   }
   /**
    * 房间挑战
    * 奖励发送
    * 本金退还
    */
   public static function sendRoomReward($room){
       $type = $room['type'];//1-保底 2-普通
       //获取成功数据 1-参与中 2-已失败 3-已完成
       $successCount = db('room_join')->where(['roomId'=>$room['id'],'status'=>2])->count();//成功人数
       $successMoney = db('room_join')->where(['roomId'=>$room['id'],'status'=>2])->sum('joinMoney');//成功金额
       $failMoney = db('room_join')->where(['roomId'=>$room['id'],'status'=>3])->sum('joinMoney');//失败金
       $failCount = db('room_join')->where(['roomId'=>$room['id'],'status'=>3])->count();//失败人数
       //计算失败金
       if($type ==1 && $failCount < 1){//保底房间并且无人失败 已房主报名费为奖励金 房主本金不退
           $successMoney = $successMoney - $room['money'];//除去房主的报名金额
           $failMoney =  $room['money'];//房主报名金额
           $successData = db('room_join')->where(['roomId'=>$room['id'],'status'=>2,'uid'=>['!=',$room['uid']]])->select();//除去房主的成功报名信息
       }else{
           $successData = db('room_join')->where(['roomId'=>$room['id'],'status'=>2])->select();
       }
       //获取平台抽成比例
       $percent = 8;
       //除去抽成金额
       $sendMoney = ((100-$percent)/100)*$failMoney;//发给用户的总奖励金额
       foreach($successData as $k => $v){
           if($sendMoney){
               //计算用户报名金在成功金中的占比
               $sendPercent = floor(($v['joinMoney']/$successMoney)*100)/100;
               $userMoney = $sendMoney*$sendPercent;//每个人的应得奖金
           }else{
               $userMoney = 0;
               $sendPercent = 0;
           }
           //添加奖励
           $user  = db('member')->where('id',$v['uid'])->find();
           $addMoney = $user['money'] + $userMoney;
           db('member')->where('id',$v['uid'])->update(['money'=>$addMoney]);
           //余额记录添加
           Share::userMoneyRecord($v['uid'],$userMoney,'房间挑战奖励发放-'.$room['name'],1,2,1);
           //收益记录
           Share::userMoneyGet($v['uid'],$userMoney,2);
           Share::rewardRecord($v['uid'],$userMoney,$room['id'],2,$v['id']);
           //本金退还
           $returnMoney = $addMoney + $v['joinMoney'];
           db('member')->where('id',$v['uid'])->update(['money'=>$returnMoney]);
           Share::userMoneyRecord($v['uid'],$v['joinMoney'],'房间挑战报名费退还-'.$room['name'],1,2);
           //记录用户的占比数据
           db('room_join')->where('id',$v['id'])->update(['getMoney'=>$userMoney,'getPercent'=>$sendPercent]);
           //记录房间数据
           db('room_create')->where('id',$room['id'])->update(['successCount'=>$successCount,'successMoney'=>$successMoney,'failCount'=>$failCount,'failMoney'=>$failMoney]);
       }
   }

}