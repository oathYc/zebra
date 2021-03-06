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
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [
                'type'=>2,
            ];

            $result = db('system')->where($where)->limit($offset, $limit)->order('createTime', 'desc')->select();
            foreach ($result as $key => $vo) {

                // 生成操作按钮
                $result[$key]['operate'] = $this->makeBtn($vo['id']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);

            }
            $return['total'] = db('clock_in')->count();  //总数据
            $return['rows'] = $result;

            return json($return);

        }
        return $this->fetch();
    }
    // 生成按钮
    private function makeBtn ($id)
    {
        $operate = '';
        $operate .= '<a href="javascript:delHelp(' . $id . ')"><button type="button" class="btn btn-danger btn-sm">';
        $operate .= '<i class="fa fa-trash-o"></i> 删除</button></a> ';

        $operate .= '<a href="/admin/system/editHelp?id='.$id.'">';
        $operate .= '<button type="button" class="btn btn-info btn-sm"><i class="fa fa-institution"></i> 编辑</button></a>';

        return $operate;
    }
    //删除活动
    public function delHelp(){
        $id = input('id');
        $res = db('system')->where('id',$id)->delete();
        if($res){
            return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
        }else{
            return json(['code' => -2, 'data' => '', 'msg' => '删除失败']);
        }
    }
    //帮助中心详情
    public function editHelp(){
        if(request()->isPost()){
            $title = input('post.title');
            $content = input('content');
            $id = input('post.id');
            if(!$title){
                return json(['code' => -1, 'data' => '', 'msg' => '标题不能为空！']);
            }
            if(!$content){
                return json(['code' => -1, 'data' => '', 'msg' => '内容不能为空！']);
            }
            $param['title'] = $title;
            $param['content'] = $content;
            $param['createTime'] = time();
            $param['type'] = 2;
            try{
                if($id){
                    db('system')->where('id',$id)->update($param);
                }else{
                    db('system')->insert($param);
                }
            }catch(\Exception $e){
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '添加成功']);
        }
        $id = input('id');
        $info = db('system')->where('id',$id)->find();
        $this->assign('info',$info);
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
    //版本设置
    public function version()
    {
        $type = 4;//1-关于我们 2-帮助中心 3-免责申明 4-版本
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
    //奖励金额
    public function shareMoney()
    {
        $type = 5;//1-关于我们 2-帮助中心 3-免责申明 4-版本 5-奖励金额
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
    //提现设置
    public function returnTime()
    {
        $type = 6;//1-关于我们 2-帮助中心 3-免责申明 4-版本 5-奖励金额 6-提现设置 7-提现费率
        if(request()->isPost()){

            $param = input('post.');
            $content = $param['beginTime'].'-'.$param['endTime'];
//            if(empty($param['content'])){
//                return json(['code' => -1, 'data' => '', 'msg' => '内容不能为空']);
//            }
            $contentArr = [
                'beginTime'=>$param['beginTime'],
                'endTime'=>$param['endTime'],
                'timeStr'=>$content,
                'percent'=>$param['percent'],
                'returnNum'=>$param['number'],
                'maxMoney'=>$param['maxMoney'],
                'autoCashMoney'=>$param['autoCashMoney'],
                'autoCashMoneyStatus' => $param['autoCashMoneyStatus'],
                'minCashMoney' => isset($param['minCashMoney']) ? $param['minCashMoney'] : 0,
                'freeMoney' => isset($param['freeMoney']) ? $param['freeMoney'] : 0,
            ];
            $params['content'] = json_encode($contentArr);
            try{
                $params['createTime'] = time();
                $params['type'] = $type;
                if($param['id']){
                    $res = db('system')->where('id', $param['id'])->update($params);
                }else{
                    $res = db('system')->insert($params);
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
        if($info){
            $info['content'] = json_decode($info['content'],true);
//            $info['times'] = explode('-',$info['content']);
        }else{
            $info = [
                'id'=>0,
                'times'=>['',''],
            ];
        }
        $this->assign([
            'info' => $info,
        ]);

        return $this->fetch();
    }
    //提现费率 百分比
    public function returnPercent()
    {
        $type = 7;//1-关于我们 2-帮助中心 3-免责申明 4-版本 5-奖励金额 6-提现时间 7-提现费率
        if(request()->isPost()){

            $param = input('post.');
//            if(empty($param['content'])){
//                return json(['code' => -1, 'data' => '', 'msg' => '内容不能为空']);
//            }
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

    //一级分销比例  闯关
    public function passPercent()
    {
        $type = 8;//1-关于我们 2-帮助中心 3-免责申明 4-版本 5-奖励金额 6-提现时间 7-提现费率 8-闯关一级分销比例
        if(request()->isPost()){

            $param = input('post.');
//            if(empty($param['content'])){
//                return json(['code' => -1, 'data' => '', 'msg' => '内容不能为空']);
//            }
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
    //打卡奖励抽成白扥比
    public function clockPercent()
    {
        $type = 9;
        //1-关于我们 2-帮助中心 3-免责申明 4-版本 5-奖励金额 6-提现时间 7-提现费率 8-闯关一级分销比例 9-打卡抽成
        if(request()->isPost()){

            $param = input('post.');
//            if(empty($param['content'])){
//                return json(['code' => -1, 'data' => '', 'msg' => '内容不能为空']);
//            }
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
    //闯关完成额外奖励金额
    public function passReward()
    {
        $type = 10;
        //1-关于我们 2-帮助中心 3-免责申明 4-版本 5-奖励金额 6-提现时间 7-提现费率 8-闯关一级分销比例 9-打卡抽成 10-闯关完成奖励
        if(request()->isPost()){

            $param = input('post.');
//            if(empty($param['content'])){
//                return json(['code' => -1, 'data' => '', 'msg' => '内容不能为空']);
//            }
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