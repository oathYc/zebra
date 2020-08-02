<?php
/**
 * User: nickbai
 * Date: 2017/10/31 12:47
 * Email: 1902822973@qq.com
 */
namespace app\admin\controller;

use app\common\model\Share;

class Clock extends Base
{
    //打卡首页
    public function index(){
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];

            $result = db('clock_in')->where($where)->limit($offset, $limit)->order('sort', 'desc')->select();
            foreach ($result as $key => $vo) {
                // 优化显示头像
                $statusStr  = $vo['status']== 1?'启用':'关闭';
                $result[$key]['statusStr'] = $statusStr;
                $result[$key]['signTime'] = $vo['beginTimeStr'].'-'.$vo['endTimeStr'];
                $result[$key]['rewardType'] = self::rewardType($vo['rewardType']);
                // 生成操作按钮
                $result[$key]['operate'] = $this->makeBtn($vo['id'],$vo['status']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
            }
            $return['total'] = db('clock_in')->count();  //总数据
            $return['rows'] = $result;

            return json($return);

        }
        return $this->fetch();
    }
    //添加打卡活动
    public function addClock(){
        if(request()->isPost()){

            $param = input('post.');
            unset($param['file']);
            $param['beginTime'] = Share::getMinute($param['beginTimeStr']);
            $param['endTime'] = Share::getMinute($param['endTimeStr']);
            if($param['endTime'] <= $param['beginTime']){
                return json(['code' => -1, 'data' => '', 'msg' => '打卡结束时间必须大于开始时间！']);
            }
            $param['createTime'] = time();

            $has = db('clock_in')->field('id')->where('name', $param['name'])->find();
            if(!empty($has)){
                return json(['code' => -1, 'data' => '', 'msg' => '该打卡活动已经存在']);
            }

            try{
                db('clock_in')->insert($param);
            }catch(\Exception $e){
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '添加打卡活动成功']);
        }
        return $this->fetch();
    }
    public static function rewardType($type){
        $arr = [
            1=>'固定金额（元）',
            2=>'百分比'
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

        $operate .= '<a href="/admin/clock/detailClock?id='.$id.'">';
        $operate .= '<button type="button" class="btn btn-info btn-sm"><i class="fa fa-institution"></i> 详情</button></a>';

        return $operate;
    }
    //状态修改
    public function editStatus(){
        $id = input('id');
        $status = input('status');
        $status = $status==1?0:1;
        $res = db('clock_in')->where('id',$id)->update(['status'=>$status]);
        if($res){
            return json(['code' => 1, 'data' => '', 'msg' => '修改成功']);
        }else{
            return json(['code' => -2, 'data' => '', 'msg' => '修改失败']);
        }
    }
    //打卡活动详情
    public function detailClock(){
        $id = input('id');
        $info = db('clock_in')->where('id',$id)->find();
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
        $this->assign('info',$info);
        return $this->fetch();
    }
    //删除活动
    public function delClock(){
        $id = input('id');
        $res = db('clock_in')->where('id',$id)->delete();
        if($res){
            return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
        }else{
            return json(['code' => -2, 'data' => '', 'msg' => '删除失败']);
        }
    }

    //打卡报名
    public function clockJoin(){
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];

            $result = db('clock_in_join')->where($where)->limit($offset, $limit)->order('id', 'desc')->select();
            foreach ($result as $key => $vo) {
                $result[$key]['statusStr'] = self::clockJoinStatus($vo['status']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
                //获取打卡活动信息
                $clockId = $vo['clockInId'];
                $clock = db('clock_in')->where("id",$clockId)->find();
                if($clock){
                    $result[$key]['clockName'] = $clock['name'];
                }else{
                    $result[$key]['clockName'] = '已被删除';
                }

                //获取报名者信息
                $user = db('member')->where('id',$vo['uid'])->find();
                $result[$key]['nickname'] = $user['nickname'];
            }
            $return['total'] = db('clock_in_join')->count();  //总数据
            $return['rows'] = $result;
            return json($return);

        }
        return $this->fetch();
    }
    //参与报名状态
    public static function clockJoinStatus($status){
        $arr = [
            0=>'失败',
            1=>'参与中',
            2=>'已完成'
        ];
        if(isset($arr[$status])){
            return $arr[$status];
        }else{
            return '';
        }
    }
}