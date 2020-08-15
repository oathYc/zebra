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
                Share::roomEveryDayReward($room['id'],1);//1-活动完成 0-活动未完成 完成退还报名费
                db('room_create')->where('id',$v['id'])->update(['status'=>2]);//修改状态为活动结束
            }else{
                Share::roomEveryDayReward($room['id'],0);
            }
        }
   }


}