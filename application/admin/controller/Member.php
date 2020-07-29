<?php
/**
 * User: nickbai
 * Date: 2017/10/23 13:33
 * Email: 1902822973@qq.com
 */

namespace app\admin\controller;
use app\common\utils\Mail;
use think\Request;

class Member extends Base
{
    // 客服列表
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

}