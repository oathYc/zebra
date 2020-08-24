<?php
/**
 * User: nickbai
 * Date: 2017/10/23 13:33
 * Email: 1902822973@qq.com
 */

namespace app\admin\controller;
use app\common\model\Share;
use app\common\utils\Mail;
use think\Request;

class Member extends Base
{
    // 用户列表
    public function index()
    {
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['nickname'] = ['like', '%' . $param['searchText'] . '%'];
            }

            $result = db('member')->where($where)->limit($offset, $limit)->order('id', 'desc')->select();
            foreach ($result as $key => $vo) {
                // 优化显示头像
                $result[$key]['avatar'] = '<img src="' . $vo['avatar'] . '" width="40px" height="40px">';
                // 生成操作按钮
                $result[$key]['operate'] = $this->makeBtn($vo['id']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
            }

            $return['total'] = db('member')->count();  //总数据
            $return['rows'] = $result;

            return json($return);

        }

        return $this->fetch();
    }

    // 用户余额记录
    public function moneyRecord()
    {
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if ($param['type'] != 99) {
                $where['moneyType'] = $param['type'];
            }

            $result = db('user_money_record')->where($where)->limit($offset, $limit)->order('id', 'desc')->select();
            foreach ($result as $key => $vo) {
                $user = db('member')->where('id',$vo['uid'])->find();
                // 优化显示头像
                $result[$key]['avatar'] = '<img src="' . $user['avatar'] . '" width="40px" height="40px">';
                $result[$key]['nickname'] = $user['nickname'];
                // 生成操作按钮
//                $result[$key]['operate'] = $this->makeBtn($vo['id']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
                $result[$key]['moneyTypeStr'] = self::getMoneyTypeStr($vo['moneyType']);
                $result[$key]['typeStr'] = $vo['type']==1?'收入':'支出';
            }

            $return['total'] = db('user_money_record')->where($where)->count();  //总数据
            $return['rows'] = $result;

            return json($return);

        }
        $typeArr = [
            0=>'余额充值',
            1=>'打卡挑战',
            2=>'房间挑战',
            3=>'闯关挑战',
            4=>'余额体现',
        ];
        $this->assign('types',$typeArr);
        return $this->fetch();
    }

    // 用户体现记录
    public function returnApply()
    {
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if ($param['status'] != 99) {//99-全部 0-体现申请中  1-已提现
                $where['status'] = $param['status'];
            }

            $result = db('user_return')->where($where)->limit($offset, $limit)->order('id', 'desc')->select();
            foreach ($result as $key => $vo) {
                $user = db('member')->where('id',$vo['uid'])->find();
                // 优化显示头像
                $result[$key]['avatar'] = '<img src="' . $user['avatar'] . '" width="40px" height="40px">';
                $result[$key]['nickname'] = $user['nickname'];
                // 生成操作按钮
                $result[$key]['operate'] = $this->makeReturnBtn($vo['id'],$vo['status']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
                $result[$key]['returnTime'] = date('Y-m-d H:i:s',$vo['returnTime']);
                $result[$key]['typeStr'] = $vo['status']==1?'微信':'支付宝';
                $result[$key]['statusStr'] = $vo['status']==1?'已提现':'提现中';
            }

            $return['total'] = db('user_return')->where($where)->count();  //总数据
            $return['rows'] = $result;

            return json($return);

        }
        $status = [
            0=>'申请中',
            1=>'已提现'
        ];
        $this->assign('status',$status);
        return $this->fetch();
    }
    //余额操作类型
    public static function getMoneyTypeStr($moneyType){
        $arr = [
            0=>'余额充值',
            1=>'打卡挑战',
            2=>'房间挑战',
            3=>'闯关挑战',
            4=>'余额体现',
        ];
        if(isset($arr[$moneyType])){
            return $arr[$moneyType];
        }else{
            return '';
        }
    }


    // 生成按钮
    private function makeBtn ($id)
    {
        $operate = '';
//        $operate .= '<a href="/admin/member/detailUser?id='.$id.'">';
//        $operate .= '<button type="button" class="btn btn-primary btn-sm"><i class="fa fa-paste"></i> 编辑</button></a> ';
        $operate .= '<a href="javascript:userDel(' . $id . ')"><button type="button" class="btn btn-danger btn-sm">';
        $operate .= '<i class="fa fa-trash-o"></i> 删除</button></a> ';

        $operate .= '<a href="/admin/member/detailUser?id='.$id.'">';
        $operate .= '<button type="button" class="btn btn-info btn-sm"><i class="fa fa-institution"></i> 详情</button></a>';

        return $operate;
    }

    // 生成按钮
    private function makeReturnBtn ($id,$status)
    {
        $operate = '';
        if($status == 0) {//0-申请中
            $operate .= '<a href="javascript:applyCheck(' . $id . ')"><button type="button" class="btn btn-primary btn-sm">';
            $operate .= '<i class="fa fa-paste"></i> 同意</button></a> ';
        }

        return $operate;
    }

    // 用户详情
    public function detailUser()
    {
        $id = input('id');
        $info = db('member')->where('id', $id)->find();
        if($info){

            $info['workerStatus'] = $this->identityCheck($info['worker']);
            $info['bossStatus'] = $this->identityCheck($info['boss']);
            $info['driverStatus'] = $this->identityCheck($info['driver']);
        }else{
            $info = [];
        }
        $this->assign(['info' => $info]);
        return $this->fetch('edit_user');
    }

    // 删除用户
    public function delUser()
    {
        if (request()->isAjax()) {
            $id = input('param.id/d');

            try {
                db('member')->where('id', $id)->delete();
            } catch (\Exception $e) {
                return json(['code' => -1, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '删除客用户成功']);
        }
    }
    //用户提现 同意
    public function userReturn(){
        if (request()->isAjax()) {
            $id = input('param.id/d');

            try {
                $return = db('user_return')->where('id',$id)->find();
                if($return['type'] ==1){//微信提现

                }else{//支付宝提现

                }
                //判断用户余额
                $user= db('member')->where("id",$return['uid'])->find();
                if(!$user){
                    return json(['code' => 1, 'data' => '', 'msg' => '申请用户不存在']);
                }
                //计算提现金额
                $reduceMoney = $return['money'] + $return['procedures'];//提现金额加手续费
                if($reduceMoney > $user['money']){
                    return json(['code' => 1, 'data' => '', 'msg' => '用户余额不足，不可提现']);
                }
                if(!$return){
                    return json(['code' => 1, 'data' => '', 'msg' => '没有该申请信息']);
                }
                if($return['status'] != 0){
                    return json(['code' => 1, 'data' => '', 'msg' => '该申请状态不是提现中！']);
                }
                db('user_return')->where('id', $id)->update(['status'=>1,'returnTime'=>time()]);
                //修改用户余额
                $hadMoney = $user['money'] - $reduceMoney;
                db('member')->where('id',$return['uid'])->update(['money'=>$hadMoney]);
                //余额记录
                Share::userMoneyRecord($return['uid'],$reduceMoney,'余额体现，体现金额-'.$return['money'].'元，手续费-'.$return['procedures'].'元',2,4);
            } catch (\Exception $e) {
                return json(['code' => -1, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '操作成功']);
        }
    }

}