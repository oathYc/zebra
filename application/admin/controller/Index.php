<?php
namespace app\admin\controller;


class Index extends Base
{
    // 后台总体框架
    public function index()
    {
        return $this->fetch('/index');
    }

    // 后台默认首页
    public function indexPage()
    {


        return $this->fetch('index');
    }

    // 清除缓存
    public function clear()
    {
        if (false === removeDir(RUNTIME_PATH)) {
            return json(['code' => -1, 'data' => '', 'msg' => '清除缓存失败']);
        }
        return json(['code' => 1, 'data' => '', 'msg' => '清除缓存成功']);
    }

    // 修改管理员密码
    public function changePassword()
    {
        if(request()->isPost()){

            $param = input('post.');
            $reLogin = false;

            if(empty($param['old_pwd']) && !empty($param['password'])){
                return json(['code' => -2, 'data' => '', 'msg' => '请输入旧密码']);
            }

            if(!empty($param['old_pwd']) && empty($param['password'])){
                return json(['code' => -3, 'data' => '', 'msg' => '请输入新密码']);
            }

            if(!empty($param['old_pwd']) && !empty($param['password'])){

                $userPwd = db('admins')->where('id', cookie('user_id'))->find();
                if(empty($userPwd)){
                    return json(['code' => -4, 'data' => '', 'msg' => '管理员不存在']);
                }

                if(md5($param['old_pwd'] . config('salt')) != $userPwd['password']){
                    return json(['code' => -1, 'data' => '', 'msg' => '旧密码错误']);
                }

                $info['password'] = md5($param['password'] . config('salt'));
                $reLogin = true;
            }

            db('admins')->where('id', cookie('user_id'))->setField('password', $info['password']);

            return json(['code' => 1, 'data' => $reLogin, 'msg' => '修改信息成功']);
        }
    }
}
