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
            }
            $return['total'] = db('pass')->count();  //总数据
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
            $param['beginTime'] = Share::getMinute($param['beginTimeStr']);
            $param['endTime'] = Share::getMinute($param['endTimeStr']);
            if($param['endTime'] <= $param['beginTime']){
                return json(['code' => -1, 'data' => '', 'msg' => '打卡结束时间必须大于开始时间！']);
            }
            if(!$param['hour']){
                $param['hour'] = '2.5';
            }
            if($param['money'] < 1){
                Share::jsonData(0,'','报名金额必须大于1');
            }
            $param['createTime'] = time();
            if(in_array($param['rewardType'],[1,3]) && $param['reward'] > 100){
                $param['reward'] = 100;
            }
            $has = db('pass')->field('id')->where('number', $param['number'])->find();
            if(!empty($has)){
                return json(['code' => -1, 'data' => '', 'msg' => '该闯关活动（期数相同）已经存在']);
            }

            try{
                $signTime = $param['signTime'];
                unset($param['signTime']);
                db('pass')->insert($param);
            }catch(\Exception $e){
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }
            $passId = db('pass')->getLastInsID();
            //记录闯关签到时间
            foreach($signTime as $k => $v){
                $signTime[$k] = $v?$v:3;//默认三分钟
            }
            $signTime['passId'] = $passId;
            $signTime['createTime'] = time();
            db('pass_time')->insert($signTime);
            return json(['code' => 1, 'data' => '', 'msg' => '添加闯关活动成功']);
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
        $operate .= '<button type="button" class="btn btn-info btn-sm"><i class="fa fa-institution"></i> 详情</button></a>';

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
        $info = db('pass')->where('id',$id)->find();
        if($info['image']){
            $info['image'] = '<img src="' . $info['image'] . '" width="40px" height="40px">';;
        }else{
            $info['image'] = '';
        }
        if($info['background']){
            $info['background'] = '<img src="' . $info['background'] . '" width="40px" height="40px">';;
        }else{
            $info['background'] = '';
        }
        $info['statusStr'] = $info['status'] == 1?'启用':'关闭';
        $info['signTime'] = db('pass_time')->where(['passId'=>$info['id']])->find();
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

            $where = [];

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
            $return['total'] = db('pass_join')->count();  //总数据
            $return['rows'] = $result;
            return json($return);

        }
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
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [
                'status'=>1,//获取已签到的
            ];

            $result = db('pass_sign')->where($where)->limit($offset, $limit)->order('signTime', 'desc')->select();
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

                //获取报名者信息
                $user = db('member')->where('id',$vo['uid'])->find();
                $result[$key]['nickname'] = $user['nickname'];
            }
            $return['total'] = db('pass_sign')->where($where)->count();  //总数据
            $return['rows'] = $result;
            return json($return);

        }
        return $this->fetch();
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
}