<?php
/**
 * User: nickbai
 * Date: 2017/10/23 13:33
 * Email: 1902822973@qq.com
 */

namespace app\admin\controller;
use app\api\controller\Appalipay;
use app\api\controller\Appwxpay;
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
                $result[$key]['operate'] = $this->makeBtn($vo['id'],$vo['status']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
                $result[$key]['statusStr'] = $vo['status']==1?'活跃':'冻结';
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
    private function makeBtn ($id,$status)
    {
        $statusStr = $status==1?'冻结':'解冻';
        $operate = '';
//        $operate .= '<a href="/admin/member/detailUser?id='.$id.'">';
//        $operate .= '<button type="button" class="btn btn-primary btn-sm"><i class="fa fa-paste"></i> 编辑</button></a> ';
        $operate .= '<a href="/admin/member/editMoney?id='.$id.'">';
        $operate .= '<button type="button" class="btn btn-primary btn-sm"><i class="fa fa-paste"></i> 余额</button></a> ';
        $operate .= '<a href="javascript:userDel(' . $id . ')"><button type="button" class="btn btn-danger btn-sm">';
        $operate .= '<i class="fa fa-trash-o"></i> 删除</button></a> ';

        $operate .= '<a href="/admin/member/detailUser?id='.$id.'">';
        $operate .= '<button type="button" class="btn btn-info btn-sm"><i class="fa fa-institution"></i> 详情</button></a> ' ;
        $operate .= '<a href="javascript:userStatus(' . $id . ')"><button type="button" class="btn btn-primary btn-sm">';
        $operate .= '<i class="fa fa-trash-o"></i> '.$statusStr.'</button></a> ';

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

//            $info['workerStatus'] = $this->identityCheck($info['worker']);
//            $info['bossStatus'] = $this->identityCheck($info['boss']);
//            $info['driverStatus'] = $this->identityCheck($info['driver']);
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
    // 冻结
    public function userStatus()
    {
        if (request()->isAjax()) {
            $id = input('param.id/d');
            $status = input('status');
            try {
                db('member')->where('id', $id)->update(['status'=>$status]);
            } catch (\Exception $e) {
                return json(['code' => -1, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '操作成功']);
        }
    }
    // 用户提现记录
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
                $result[$key]['returnTime'] = $vo['status']==1?date('Y-m-d H:i:s',$vo['returnTime']):'';
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
    //用户提现 同意
    public function userReturn(){
        if (request()->isAjax()) {
            $id = input('param.id/d');

            try {
                $return = db('user_return')->where('id',$id)->find();
                $realName = db('member')->where('id',$return['uid'])->find()['name'];
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
                if($return['type'] ==1){//微信提现
                    $res = Appwxpay::WeixinReturn($return['uid'],$return['orderNo'],$return['money']);
                }else{//支付宝提现
                    $res = Appalipay::alipayReturn($return['phone'],$return['money'],$realName);
                }
                if(!isset($res['code']) || $res['code'] != 1){
                    return json(['code' => -1, 'data' => '', 'msg' => $res['message']]);
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

    // 用户实名认证
    public function realName()
    {
        $checkArr = [1,2,3];//1-认证中  2-已认证 3-认证失败
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if ($param['check'] != 99) {//99-全部 1-认证中  2-已认证 3-认证失败
                $where['check'] = $param['check'];
            }else{
                $where['check'] = ['in',$checkArr];
            }

            $result = db('member')->where($where)->limit($offset, $limit)->order('id', 'desc')->select();
            foreach ($result as $key => $vo) {
                // 优化显示头像
                $result[$key]['avatar'] = '<img src="' . $vo['avatar'] . '" width="40px" height="40px">';
                // 生成操作按钮
                $result[$key]['operate'] = $this->makeRealNameBtn($vo['id'],$vo['check']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
                $result[$key]['checkStr'] = self::getCheckStr($vo['check']);
            }

            $return['total'] = db('member')->where($where)->count();  //总数据
            $return['rows'] = $result;

            return json($return);

        }
        $check = [
            1=>'认证中',
            2=>'认证成功',
            3=>'认证失败',
        ];
        $this->assign('check',$check);
        return $this->fetch();
    }

    //获取认证状态
    public static function getCheckStr($check){
        $arr = [
            1=>'认证中',
            2=>'认证成功',
            3=>'认证失败',
        ];
        if(isset($arr[$check])){
            return $arr[$check];
        }else{
            return '';
        }
    }
    //设置实名认证按钮
    public static function makeRealNameBtn($id,$check){
        $operate = '';
        if($check == 1 || $check == 3) {//0-申请中
            $operate .= '<a href="javascript:realNameCheck(' . $id . ')"><button type="button" class="btn btn-primary btn-sm">';
            $operate .= '<i class="fa fa-paste"></i>通过</button></a> ';
        }
        return $operate;
    }
    //实名认证审核
    public function realNameCheck(){
        if (request()->isAjax()) {
            $id = input('param.id/d');
            try {
                $member = db('member')->where('id',$id)->find();
                if(!$member){
                    return json(['code' => 1, 'data' => '', 'msg' => '申请用户不存在']);
                }
                db('member')->where('id',$id)->update(['check'=>2]);
            } catch (\Exception $e) {
                return json(['code' => -1, 'data' => '', 'msg' => $e->getMessage()]);
            }
            return json(['code' => 1, 'data' => '', 'msg' => '操作成功']);
        }
    }
    /**
     * 余额修改
     */
    public function editMoney(){
        if(request()->isAjax()){
            $param = input('param.');
            $uid = $param['id'];
            $money = $param['money'];
            $user = db('member')->where('id',$uid)->find();
            if($user['money'] >= $money){
                return json(['code'=>-1,'data'=>'','msg'=>'修改余额不能小于用户当前余额']);
            }
            $res = db('member')->where('id',$uid)->update(['money'=>$money]);
            if($res){
                return json(['code'=>1,'data'=>'','msg'=>'操作成功']);
            }else{
                return json(['code'=>-1,'data'=>'','msg'=>'操作失败']);
            }
        }
        $id = input('id');
        $user = db('member')->where('id',$id)->find();
        $this->assign('info',$user);
        return $this->fetch();
    }
}