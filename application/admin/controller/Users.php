<?php
/**
 * User: nickbai
 * Date: 2017/10/23 13:33
 * Email: 1902822973@qq.com
 */

namespace app\admin\controller;
use app\common\utils\Mail;

class Users extends Base
{
    // 客服列表
    public function index ()
    {
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['user_name'] = ['like', '%' . $param['searchText'] . '%'];
            }

            $result = db('users')->where($where)->limit($offset, $limit)->order('id', 'desc')->select();
            foreach ($result as $key => $vo) {
                // 优化显示头像
                $result[$key]['user_avatar'] = '<img src="' . $vo['user_avatar'] . '" width="40px" height="40px">';

                // 优化显示状态
                if (1 == $vo['status']) {
                    $result[$key]['status'] = '<span class="label label-primary">启用</span>';
                } else {
                    $result[$key]['status'] = '<span class="label label-danger">禁用</span>';
                }

                // 查询分组
                $result[$key]['group'] = '-';
                $groups = db('groups')->field('name')->where('id', $vo['group_id'])->find();
                if (!empty($groups)) {
                    $result[$key]['group'] = $groups['name'];
                }

                // 优化显示在线状态
                /*if(1 == $vo['online']){
                    $result[$key]['online'] = '<span class="label label-primary">在线</span>';
                }else{
                    $result[$key]['online'] = '<span class="label label-danger">离线</span>';
                }*/

                // 生成操作按钮
                $result[$key]['operate'] = $this->makeBtn($vo['id']);
            }

            $return['total'] = db('users')->count();  //总数据
            $return['rows'] = $result;

            return json($return);

        }

        return $this->fetch();
    }

    // 添加客服
    public function addUser ()
    {
        if (request()->isPost()) {

            $param = input('post.');
            unset($param['file']); // 删除layui头像上传隐藏字段
            // 检测头像
            if (empty($param['user_avatar'])) {
                return json(['code' => -1, 'data' => '', 'msg' => '请上传头像']);
            }

            if (empty($param['group_id'])) {
                return json(['code' => -4, 'data' => '', 'msg' => '请选择分组']);
            }

            $has = db('users')->field('id')->where('user_name', $param['user_name'])->find();
            if (!empty($has)) {
                return json(['code' => -2, 'data' => '', 'msg' => '该客服已经存在']);
            }

            $param['user_pwd'] = md5($param['user_pwd'] . config('salt'));
            $param['online'] = 2; // 离线状态

            try {
                db('users')->insert($param);
            } catch (\Exception $e) {
                return json(['code' => -3, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '添加客服成功']);
        }

        $this->assign(['groups' => db('groups')->select(), 'status' => config('kf_status')]);

        return $this->fetch();
    }

    // 编辑客服
    public function editUser ()
    {
        if (request()->isAjax()) {

            $param = input('post.');
            unset($param['file']); // 删除layui头像上传隐藏字段

            if (empty($param['group_id'])) {
                return json(['code' => -4, 'data' => '', 'msg' => '请选择分组']);
            }

            // 检测用户修改的用户名是否重复
            $has = db('users')->where('user_name', $param['user_name'])->where('id', '<>', $param['id'])->find();
            if (!empty($has)) {
                return json(['code' => -1, 'data' => '', 'msg' => '该客服已经存在']);
            }

            // 修改用户头像
            if (empty($param['user_avatar'])) {
                unset($param['user_avatar']);
            }

            // 修改用户密码
            if (empty($param['user_pwd'])) {
                unset($param['user_pwd']);
            } else {
                $param['user_pwd'] = md5($param['user_pwd'] . config('salt'));
            }

            try {
                db('users')->where('id', $param['id'])->update($param);
            } catch (\Exception $e) {
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '编辑客服成功']);
        }

        $id = input('param.id/d');
        $info = db('users')->where('id', $id)->find();

        $this->assign(['info' => $info, 'status' => config('kf_status'), 'groups' => db('groups')->select()]);
        return $this->fetch();
    }

    // 删除客服
    public function delUser ()
    {
        if (request()->isAjax()) {
            $id = input('param.id/d');

            try {
                db('users')->where('id', $id)->delete();
            } catch (\Exception $e) {
                return json(['code' => -1, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '删除客服成功']);
        }
    }

    // 上传客服头像
    public function upAvatar ()
    {
        if (request()->isAjax()) {

            $file = request()->file('file');
            if (!empty($file)) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info) {
                    $src = '/uploads' . '/' . date('Ymd') . '/' . $info->getFilename();
                    return json(['code' => 0, 'data' => ['src' => $src], 'msg' => 'ok']);
                } else {
                    // 上传失败获取错误信息
                    return json(['code' => -1, 'data' => '', 'msg' => $file->getError()]);
                }
            }
        }
    }

    // 生成按钮
    private function makeBtn ($id)
    {
        $operate = '<a href="' . url('users/edituser', ['id' => $id]) . '">';
        $operate .= '<button type="button" class="btn btn-primary btn-sm"><i class="fa fa-paste"></i> 编辑</button></a> ';

        $operate .= '<a href="javascript:userDel(' . $id . ')"><button type="button" class="btn btn-danger btn-sm">';
        $operate .= '<i class="fa fa-trash-o"></i> 删除</button></a> ';

        //$operate .= '<a href="javascript:;">';
        //$operate .= '<button type="button" class="btn btn-info btn-sm"><i class="fa fa-institution"></i> 详情</button></a>';

        return $operate;
    }

    //获取留言列表
    public function msgList ()
    {
        if (request()->isAjax()) {
            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['comment_text'] = ['like', '%' . $param['searchText'] . '%'];
            }

            $result = db('comment')->where($where)->limit($offset, $limit)->order('status desc')->select();//order('id', 'desc')
            foreach ($result as $key => $vo) {
                //留言内容
                if (strlen($vo['comment_text']) > 30) {
                    $comment_text = cutstr_html($vo['comment_text']);
                    $result[$key]['comment_text'] = mb_substr($comment_text, 0, 30) . '...<a  href="javascript:more(&quot;' . $comment_text . '&quot;);" >更多>></a>';
                }
                //截图
                $result[$key]['img'] = '-';
                if ($vo['img']) {
                    $result[$key]['img'] = '<img src="' . $vo['img'] . '" width="40px" height="40px" style="cursor: pointer;" onclick="getBigImg(&quot;'. $vo['img'] .'&quot;)">';
                }

                //回复状态
                if ($vo['status'] == 2) {
                    $result[$key]['reply_text'] = '-';
                    $result[$key]['status'] = '<span class="label label-danger">未回复</span>';
                    //操作
                    $result[$key]['operate'] = '<a href="javascript:detail(' . $vo['id'] . ');" >处理/详情</a>';

                } else {
                    if(strlen($vo['reply_text']) > 30) {
                        $reply_text = cutstr_html($vo['reply_text']);
                        $result[$key]['reply_text'] = mb_substr($reply_text, 0, 30) . '...<a  href="javascript:more(&quot;' . $reply_text . '&quot;);" >更多>></a>';
                    }
                    $result[$key]['status'] = '<span class="label label-primary">已回复</span>';
                    //操作
                    $result[$key]['operate'] = '<a href="javascript:return false;" onclick="return false;" style="color:#777;cursor: default;">处理/详情</a>';
                }
                //留言日期
                $result[$key]['updated_at'] = date("Y-m-d", $vo['updated_at']);

            }

            $return['total'] = db('comment')->count();  //总数据
            $return['rows'] = $result;

            return json($return);
        }
        return $this->fetch();
    }

    //留言详情
    public function detail ()
    {
        if (request()->isAjax()) {
            $id = input('get.id');
            $result = db('comment')->where('id', $id)->find();
            return json($result);
        }
    }
    //回复留言
    public function msgReply ()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            if ($data['reply_text']) {
                $send = Mail::sendMail($data['mailbox'], $data['comment_text'], $data['reply_text']);
                if ($send) {
                    $result = db('comment')->where('id', $data['id'])->update(['reply_text' => $data['reply_text'], 'status' => 1]);
                    if ($result) {
                        return json(["msg" => '回复成功', "code" => 200, "data" => $send]);
                    } else {
                        return json(["msg" => '回复失败', "code" => -1, "data" => []]);
                    }
                } else {
                    return json(["msg" => '发送失败', "code" => -1, "data" => []]);
                }
            }
        }
    }
    //获取配置
    public function getConfig ()
    {
        if (request()->isAjax()) {
            $result = db('config')->where('id', 1)->find();
            if ($result) {
                return json(["msg" => '成功', "code" => 200, "data" => $result]);
            }
        }
    }
    //修改配置
    public function msgConfig ()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            if (!$data['msg_time']) {
                $data['msg_time'] = 1;
            }
            if (!$data['msg_frequency']) {
                $data['msg_frequency'] = 5;
            }
            $result = db('config')->where('id', 1)->update($data);
            if ($result) {
                return json(["msg" => '成功', "code" => 200, "data" => []]);
            }
        }
    }
}