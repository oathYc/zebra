<?php
/**
 * User: nickbai
 * Date: 2017/10/31 12:47
 * Email: 1902822973@qq.com
 */
namespace app\admin\controller;

use app\common\model\Share;

class Pass extends Base
{
    //闯关首页
    public function index(){
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];

            $result = db('pass')->where($where)->limit($offset, $limit)->order('number', 'desc')->select();
            foreach ($result as $key => $vo) {
                // 优化显示头像
                $statusStr  = $vo['status']== 1?'启用':'关闭';
                $result[$key]['statusStr'] = $statusStr;
                $result[$key]['joinTime'] = $vo['beginTimeStr'].'-'.$vo['endTimeStr'];
                $result[$key]['rewardType'] = self::rewardType($vo['rewardType']);
                // 生成操作按钮
                $result[$key]['operate'] = $this->makeBtn($vo['id'],$vo['status']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
                //报名价格
                $prices = db('pass_price')->where('passId',$vo['id'])->order('price','asc')->select();
                $pricesArr = [];
                foreach($prices as $v){
                    $pricesArr[] = $v['price'];
                }
                $result[$key]['minMax'] = $vo['min'].'-'.$vo['max'];
                $result[$key]['moneys'] = implode('、',$pricesArr);
            }
            $return['total'] = db('pass')->where($where)->count();  //总数据
            $return['rows'] = $result;

            return json($return);

        }
        return $this->fetch();
    }
    //添加打卡活动
    public function addPass(){
        if(request()->isPost()){
            $param = input('post.');
            unset($param['file']);
            $id = $param['id'];
            $param['beginTime'] = Share::getMinute($param['beginTimeStr']);
            $param['endTime'] = Share::getMinute($param['endTimeStr']);
            if($param['endTime'] <= $param['beginTime']){
                return json(['code' => -1, 'data' => '', 'msg' => '禁止报名结束时间必须大于开始时间！']);
            }
            if(($param['secondBeginStr'] && !$param['secondEndStr']) || (!$param['secondBeginStr'] && $param['secondEndStr'])){
                $param['secondEndStr'] = '';
                $param['secondBeginStr'] = '';
            }
            $param['secondBegin'] = Share::getMinute($param['secondBeginStr']);
            $param['secondEnd'] = Share::getMinute($param['secondEndStr']);
            if($param['secondEnd'] && $param['secondBegin'] && ($param['secondEnd'] <= $param['secondBegin'])){
                return json(['code' => -1, 'data' => '', 'msg' => '禁止报名结束时间必须大于开始时间！']);
            }
//            if(!$param['hour']){
//                $param['hour'] = '2.5';
//            }
            if(!$param['min'] || !$param['max']){
                return json(['code'=>-1,'data'=>'','msg'=>'最小和最大签到时间都必须填写']);
            }
            if($param['min'] >= $param['max']){
                return json(['code'=>-1,'data'=>'','msg'=>'最大签到时间必须大于最小签到时间']);
            }
            if($param['challenge'] < 1){
                return json(['code' => -1, 'data' => '', 'msg' => '挑战轮数必须大于1']);
            }
            $moneys = $param['moneys'];
            if(!$moneys){
                return json(['code' => -1, 'data' => '', 'msg' => '请填写报名金额']);
            }
            unset($param['moneys']);
            $param['money'] = $moneys[0];

            $signTime = isset($param['signTime'])?$param['signTime']:[];
            $signPercent = isset($param['signPercent'])?$param['signPercent']:[];
            unset($param['signTime']);
            unset($param['signPercent']);
//            if(in_array($param['rewardType'],[1,3]) && $param['reward'] > 100){
//                $param['reward'] = 100;
//            }
            $param['rewardType'] = 3;//报名费百分比奖励
//            $has = db('pass')->field('id')->where('number', $param['number'])->find();
//            if(!empty($has)){
//                return json(['code' => -1, 'data' => '', 'msg' => '该闯关活动（期数相同）已经存在']);
//            };
            try{
//                $signTime = $param['signTime'];
                unset($param['signTime']);
                unset($param['id']);
                if($id){
                    db('pass')->where('id',$id)->update($param);
                }else{
                    $param['createTime'] = time();
                    db('pass')->insert($param);
                }
                $passId = db('pass')->where('name',$param['name'])->find()['id'];
                //记录打卡报名金额
                $time = time();
                $moneyArr = [];
                foreach($moneys as $k => $v){
                    $moneyArr[] = [
                        'passId'=>$passId,
                        'price'=>$v,
                        'createTime'=>$time,
                    ];
                }
                if($moneyArr){
                    db('pass_price')->where('passId',$passId)->delete();
                    db('pass_price')->insertAll($moneyArr);
                }
                //记录签到时间 每轮签到奖励
                $signTimeArr = [];
                $signPercentArr = [];
                $number = $param['challenge'];
                for($j=0;$j<$number;$j++){
                    if(isset($signTime[$j]) && $signTime[$j]>0){
                        $signTimeArr[] = ['number'=>$j+1,'time'=>$signTime[$j],'passId'=>$passId,'createTime'=>$time];
                    }else{
                        $signTimeArr[] = ['number'=>$j+1,'time'=>3,'passId'=>$passId,'createTime'=>$time];//默认3分钟
                    }
                    if($param['rewardType'] == 3){//报名百分比才设置对应的签到奖励
                        if(isset($signPercent[$j]) && $signPercent[$j]>0){
                            $signPercentArr[] = ['number'=>$j+1,'percent'=>$signPercent[$j],'passId'=>$passId,'createTime'=>$time];
                        }else{
                            $signPercentArr[] = ['number'=>$j+1,'percent'=>0,'passId'=>$passId,'createTime'=>$time];//默认3分钟
                        }
                    }
                }
                db('pass_time')->where('passId',$passId)->delete();
                db('pass_time')->insertAll($signTimeArr);
                db('pass_percent')->where('passId',$passId)->delete();
                if($param['rewardType'] == 3){
                    db('pass_percent')->insertAll($signPercentArr);
                }
            }catch(\Exception $e){
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '/admin/pass/index', 'msg' => '添加闯关活动成功']);
        }
        return $this->fetch();
    }
    public static function rewardType($type){
        $arr = [
            1=>'失败金额百分比瓜分',
            2=>'固定金额',
            3=>'报名费用百分比'
        ];
        if(isset($arr[$type])){
            return $arr[$type];
        }else{
            return '';
        }
    }

    // 生成按钮
    private function makeBtn ($id,$status)
    {
        $statusStr = $status==1?'关闭':'启用';
        $operate = '';
        $operate .= '<a href="javascript:editStatus(' . $id . ','.$status.')">';
        $operate .= '<button type="button" class="btn btn-primary btn-sm"><i class="fa fa-paste"></i> '.$statusStr.'</button></a> ';
        $operate .= '<a href="javascript:delClock(' . $id . ')"><button type="button" class="btn btn-danger btn-sm">';
        $operate .= '<i class="fa fa-trash-o"></i> 删除</button></a> ';

        $operate .= '<a href="/admin/pass/detailPass?id='.$id.'">';
        $operate .= '<button type="button" class="btn btn-info btn-sm"><i class="fa fa-institution"></i> 修改</button></a>';

        return $operate;
    }
    //状态修改
    public function editStatus(){
        $id = input('id');
        $status = input('status');
        $status = $status==1?0:1;
        $res = db('pass')->where('id',$id)->update(['status'=>$status]);
        if($res){
            return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
        }else{
            return json(['code' => -2, 'data' => '', 'msg' => '修改失败']);
        }
    }
    //打卡活动详情
    public function detailPass(){
        $id = input('id');
        if(request()->isPost()){
            $rule = input('rule');
            if(!$rule){
                return json(['code' => -1, 'data' => '', 'msg' => '规则不能为空']);
            }
            $res = db('pass')->where('id',$id)->update(['rule'=>$rule]);
            if($res){

                return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
            }else{

                return json(['code' => -1, 'data' => '', 'msg' => '修改规则失败']);
            }
        }
        $info = db('pass')->where('id',$id)->find();
        if($info['image']){
            $info['imageStr'] = '<img src="' . $info['image'] . '" width="40px" height="40px">';;
        }else{
            $info['imageStr'] = '';
        }
        if($info['background']){
            $info['backgroundStr'] = '<img src="' . $info['background'] . '" width="40px" height="40px">';;
        }else{
            $info['backgroundStr'] = '';
        }
        $info['statusStr'] = $info['status'] == 1?'启用':'关闭';
        $info['signTime'] = db('pass_time')->where(['passId'=>$info['id']])->find();
        //获取报名金额
        //打卡价格
        $prices = db('pass_price')->where('passId',$info['id'])->order('price','asc')->select();
        if(!$prices){
            $prices[0]['price'] = 0;
        }
        $info['pricesArr'] = $prices;
        $pricesArr = [];
        foreach($prices as $v){
            $pricesArr[] = $v['price'];
        }
        $info['moneys'] = implode('元、',$pricesArr).'元';
        //获取签到时间
        $signTime = db('pass_time')->where('passId',$info['id'])->order('number','asc')->select();
        foreach($signTime as $p => $r){
            $percent = db('pass_percent')->where(['passId'=>$info['id'],'number'=>$r['number']])->find();
            if(!$percent){
                $percent = 0;
            }else{
                $percent = $percent['percent']?$percent['percent']:0;
            }
            $signTime[$p]['percent'] = $percent;
        }
        $info['signTimeArr'] = $signTime;
        $this->assign('info',$info);
        return $this->fetch();
    }
    //删除活动
    public function delPass(){
        $id = input('id');
        $res = db('pass')->where('id',$id)->delete();
        if($res){
            return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
        }else{
            return json(['code' => -2, 'data' => '', 'msg' => '删除失败']);
        }
    }

    //闯关报名
    public function passJoin(){
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $status = $param['status'];
            $uid = $param['uid'];
            $passId = $param['passId'];
            $where = [];
            if($status != 99){
                $where['status'] = $status;
            }
            if($uid){
                $where['uid'] = $uid;
            }
            if($passId){
                $where['passId'] = $passId;
            }

            $result = db('pass_join')->where($where)->limit($offset, $limit)->order('id', 'desc')->select();
            foreach ($result as $key => $vo) {
                $result[$key]['statusStr'] = self::clockPassStatus($vo['status']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
                //获取打卡活动信息
                $passId = $vo['passId'];
                $pass = db('pass')->where("id",$passId)->find();
                if($pass){
                    $result[$key]['passName'] = $pass['name'];
                }else{
                    $result[$key]['passName'] = '已被删除';
                }
                $result[$key]['rewardStr'] = $vo['isReward'] == 1?'已发放':'未发放';
                //获取报名者信息
                $user = db('member')->where('id',$vo['uid'])->find();
                $result[$key]['nickname'] = $user['nickname'];
            }
            $return['total'] = db('pass_join')->where($where)->count();  //总数据
            $return['rows'] = $result;
            return json($return);

        }
        //获取活动数据
        $pass = db('pass')->field("id,name")->select();
        $this->assign('pass',$pass);
        $statusArr = [
            0=>'参与中',
            1=>'已完成',
            2=>'未完成',
        ];
        $this->assign('statusArr',$statusArr);
        return $this->fetch();
    }
    //参与报名状态
    public static function clockPassStatus($status){
        $arr = [
            0=>'参与中',
            1=>'已完成',
            2=>'未完成'
        ];
        if(isset($arr[$status])){
            return $arr[$status];
        }else{
            return '';
        }
    }
    //闯关签到
    public function passSign(){
        //处理签到失败的数据
        $now = date('Y-m-d H:i:s');
        db('pass_sign')->where(['signTimeEnd'=>['<',$now],'status'=>0])->update(['status'=>2]);
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $status = $param['status'];
            $uid = $param['uid'];
            $passId = $param['passId'];
            $where = [];
            if($status != 99){
                $where['status'] = $status;
            }
            if($uid){
                $where['uid'] = $uid;
            }
            if($passId){
                $where['passId'] = $passId;
            }
            $result = db('pass_sign')->where($where)->limit($offset, $limit)->order('id', 'desc')->select();
            foreach ($result as $key => $vo) {
                //获取打卡活动信息
                $passId = $vo['passId'];
                $pass = db('pass')->where("id",$passId)->find();
                if($pass){
                    $result[$key]['passName'] = $pass['name'];
                }else{
                    $result[$key]['passName'] = '已被删除';
                }
                //报名信息
                $passJoin = db('pass_join')->where('id',$vo['joinId'])->find();
                $result[$key]['joinTime'] = $passJoin['joinTime'];
                $result[$key]['statusStr'] = self::getStatusStr($vo['status']);
                //期数
                $joinNumber = db('pass_join')->where('id',$vo['joinId'])->find()['number'];
                $result[$key]['joinNumber'] = '第'.$joinNumber.'期'.$vo['number'].'轮';
                //获取报名者信息
                $user = db('member')->where('id',$vo['uid'])->find();
                $result[$key]['nickname'] = $user['nickname'];
            }
            $return['total'] = db('pass_sign')->where($where)->count();  //总数据
            $return['rows'] = $result;
            return json($return);

        }

        $statusArr = [
            0=>'待签到',
            1=>'已签到',
            2=>'签到失败'
        ];
        //获取活动数据
        $pass = db('pass')->field("id,name")->select();
        $this->assign('pass',$pass);
        $this->assign('statusArr',$statusArr);
        return $this->fetch();
    }
    //签到状态
    public static function getStatusStr($status){
        $arr = [
            0=>'待签到',
            1=>'已签到',
            2=>'签到失败'
        ];
        if(isset($arr[$status])){
            return $arr[$status];
        }else{
            return '';
        }
    }
    //排行榜
    public function ranking(){
        if(request()->isAjax()){
            $type = 3;//1-打卡 2-房间挑战 3-闯关
            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = [
                'type'=>$type,
            ];
            $data = db('money_get')->where($where)->order('moneyGet','desc')->limit($offset,$limit)->select();
            foreach($data as $k => $v){
                $user = db('member')->where('id',$v['uid'])->find();
                $data[$k]['nickname'] = $user['nickname'];
            }
            $return['total'] = db('money_get')->where($where)->count();  //总数据
            $return['rows'] = $data;
            return json($return);
        }
        return $this->fetch();
    }
    //闯关奖励
    public function passReward(){
        if(request()->isAjax()){
            self::updatePassRewardRecord();
            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = [
            ];
            if($param['passId']){
                $where['passId'] = $param['passId'];
            }
            $data = db('pass_reward_record')->where($where)->order('number','desc')->limit($offset,$limit)->select();
            $return['total'] = db('pass_reward_record')->where($where)->count();  //总数据
            $return['rows'] = $data;
            return json($return);
        }
        $pass = db('pass')->select();
        $this->assign('pass',$pass);
        return $this->fetch();
    }
    //更新奖励数据
    public static function updatePassRewardRecord(){
        $pass = db('pass')->select();
        foreach($pass as $k => $v){
            //获取当前期数
            $number = Share::getPassNumber($v);
            if($number > 1){
                $targetNumber  = $number-1;//统计期数
                $time = time();
                $insertAll = [];
                for($i=1;$i<=$targetNumber;$i++){
                    //判断是否已有记录
                    $hadRecord = db('pass_reward_record')->where(['passId'=>$v['id'],'number'=>$i])->find();
                    if(!$hadRecord){//统计该期的数据
                        $joinData = db('pass_join')->where(['passId'=>$v['id'],'number'=>$i])->select();
                        $joinNumber = count($joinData);//参与人数
                        $joinMoney = 0;//参与金额
                        $joinIds = [];//参与id集合
                        $signNumber = 0;//闯关次数
                        $rewardMoney = 0;
                        foreach($joinData as $r => $t){
                            $joinIds[] = $t['id'];
                            $joinMoney += $t['joinMoney'];
                        }
                        if($joinNumber > 0){
                            $signNumber = db('pass_sign')->where(['passId'=>$v['id'],'status'=>1,'joinId'=>['in',$joinIds]])->count();
                            //获取发出去的闯关收益
                            $rewardMoney = db('pass_reward')->where(['passId'=>$v['id'],'joinId'=>['in',$joinIds]])->sum('money');
                        }
                        $insertAll[] = [
                            'passId'=>$v['id'],
                            'passName'=>$v['name'],
                            'number'=>$i,
                            'joinNumber'=>$joinNumber,
                            'joinMoney'=>$joinMoney,
                            'signNumber'=>$signNumber,
                            'rewardMoney'=>$rewardMoney,
                            'createTime'=>$time,
                        ];
                    }
                }
                if($insertAll){
                    db('pass_reward_record')->insertAll($insertAll);
                }
            }
        }
    }
}