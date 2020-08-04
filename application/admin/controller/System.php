<?php
/**
 * User: nickbai
 * Date: 2017/10/31 12:47
 * Email: 1902822973@qq.com
 */
namespace app\admin\controller;

class System extends Base
{
    // 关于我们
    public function aboutUs()
    {
        $type = 1;//1-关于我们 2-帮助中心
        if(request()->isPost()){

            $param = input('post.');
            if(empty($param['content'])){
                return json(['code' => -1, 'data' => '', 'msg' => '内容不能为空']);
            }

            try{
                $param['createTime'] = time();
                $param['type'] = $type;
                if($param['id']){
                    $res = db('system')->where('id', $param['id'])->update($param);
                }else{
                    unset($param['id']);
                    $res = db('system')->insert($param);
                }
                if($res){
                    return json(['code'=>1,'data'=>'','msg'=>'操作成功']);
                }else{
                    return json(['code'=>-1,'data'=>'','msg'=>'操作失败']);
                }
            }catch(\Exception $e){
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '设置成功']);
        }

        $info = db('system')->where('type', $type)->find();
        $this->assign([
            'info' => $info,
        ]);

        return $this->fetch();
    }
    //帮助中心
    public function help()
    {
        $type = 2;//1-关于我们 2-帮助中心
        if(request()->isPost()){

            $param = input('post.');
            if(empty($param['content'])){
                return json(['code' => -1, 'data' => '', 'msg' => '内容不能为空']);
            }

            try{
                $param['createTime'] = time();
                $param['type'] = $type;
                if($param['id']){
                    $res = db('system')->where('id', $param['id'])->update($param);
                }else{
                    unset($param['id']);
                    $res = db('system')->insert($param);
                }
                if($res){
                    return json(['code'=>1,'data'=>'','msg'=>'操作成功']);
                }else{
                    return json(['code'=>-1,'data'=>'','msg'=>'操作失败']);
                }
            }catch(\Exception $e){
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '设置成功']);
        }

        $info = db('system')->where('type', $type)->find();
        $this->assign([
            'info' => $info,
        ]);

        return $this->fetch();
    }
    //免责申明
    public function disclaimer()
    {
        $type = 3;//1-关于我们 2-帮助中心 3-免责申明
        if(request()->isPost()){

            $param = input('post.');
            if(empty($param['content'])){
                return json(['code' => -1, 'data' => '', 'msg' => '内容不能为空']);
            }

            try{
                $param['createTime'] = time();
                $param['type'] = $type;
                if($param['id']){
                    $res = db('system')->where('id', $param['id'])->update($param);
                }else{
                    unset($param['id']);
                    $res = db('system')->insert($param);
                }
                if($res){
                    return json(['code'=>1,'data'=>'','msg'=>'操作成功']);
                }else{
                    return json(['code'=>-1,'data'=>'','msg'=>'操作失败']);
                }
            }catch(\Exception $e){
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '设置成功']);
        }

        $info = db('system')->where('type', $type)->find();
        $this->assign([
            'info' => $info,
        ]);

        return $this->fetch();
    }
}