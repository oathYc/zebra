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
    * 每5分钟扫描一次
    * 提前五分钟开启活动
    */
   public function roomBeginCheck(){
        $date = date('Y-m-d');
        //获取还在报名中的房间挑战信息 状态 0-报名中   1-活动中 2-活动结束
       $room = db('room_create')->where(['beginDate'=>$date,'status'=>0])->select();
       $now = time();//当前时间戳
       foreach($room as $k => $v){
           $beginTime = $v['beginTime'];//活动第一次开始签到时间
           $roomBegin = $beginTime - 600;//提前五分钟开始时间
           if($now >=($roomBegin)){//活动已经开始
               db('room_create')->where('id',$v['id'])->update(['status'=>1]);//修改状态为活动中
           }
       }
   }
   /**
    * 房间挑战
    * 活动奖励发放
    * 结束判断
    * 每晚23:59:50扫描执行
    */
   public function roomEndCheck(){
        $nowTime = time();//当前时间
       //获取活动中的房间挑战信息 状态 0-报名中   1-活动中 2-活动结束
       $room = db('room_create')->where('status',1)->select();
        foreach($room as $k => $v){
            $beginDate = $v['beginDate'];//开始日期
            $days = $v['day'];//活动周期
            $endSign = $v['signNum'] == 1?$v['signEnd']:$v['secondEnd'];//判断打卡次数
            $endTime = strtotime($beginDate) + 86400*($days-1) + 60*$endSign;//最后打卡时间
            //发放每天的奖励给坚持打卡的用户
            if($endTime <= $nowTime){//活动结束
                Share::roomEveryDayReward($v['id'],1);//1-活动完成 0-活动未完成 完成退还报名费
                db('room_create')->where('id',$v['id'])->update(['status'=>2]);//修改状态为活动结束
            }else{
                Share::roomEveryDayReward($v['id'],0);
            }
        }
   }

   /**
    * 闯关活动
    * 活动奖励结算
    * 每天八点五分
    */
   public function passRewardSend(){
       //获取今天八点结束挑战的活动
       $date = date('Y-m-d');
       $beginTime = $date.' 00:00:00';//今日凌晨
       //八点五分时间
       $endTime = $date.' 08:05:00';
       $pass = db('pass')->where(['isEnd'=>0,'passEndTime'=>['<=',$endTime]])->select();
       foreach($pass as $k => $v){
           //判断当前状态 状态 0-下线 1-活动中
           if($v['status'] == 1){
               db('pass')->where('id',$v['id'])->update(['status'=>0]);
           }
           //获取所有报名信息
           $allJoin = db('pass_join')->where(['passId'=>$v['id']])->select();
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
               $rewardMoney = $failMoney/$challengeSuccess;
           }elseif($rewardType == 2){
               $rewardMoney = $v['reward'];
           }else{
               $rewardMoney = $v['money'] * ($v['reward']/100);
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
               }else{
                   $userMoney = $rewardMoney*intval($challengeNumber);//按挑战轮数计算
               }
               //奖励发放
               Share::sendPassRewardNew($uid,$userMoney,$v,$joinId);
               //本金退还
               if($return == 1){
                    Share::returnPassJoinMoney($uid,$v['money'],$v['name']);
               }
               //修改对应的奖励发送状态
               db('pass_join')->where('id',$joinId)->update(['isReward'=>1]);//参余状态
           }
           db('pass')->where('id',$v['id'])->update(['idEnd'=>1]);
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
           return 1;
       }
       if($join['status'] == 0){
           //判断当前签到状态 参与签到状态 0-暂停 1-停止（挑战结束） 2-下一轮（继续挑战）
           if($join['signStatus'] == 0 || $join['signStatus'] == 1){
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
               }else{//没有 说明都签到成功
                   db('pass_join')->where('id',$join['id'])->update(['status'=>1]);
                   return 1;
               }
           }
       }
       return 0;
   }

}