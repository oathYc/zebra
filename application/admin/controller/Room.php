<?php
/**
 * User: nickbai
 * Date: 2017/10/31 12:47
 * Email: 1902822973@qq.com
 */
namespace app\admin\controller;

use app\common\model\Share;

class Room extends Base
{
    //普通房间类型
    public function commonSet(){
        $type = 2;//2-普通房间 1-保底房间
        if(request()->isPost()){

            $param = input('post.');
            if(empty($param['rule'])){
                return json(['code' => -1, 'data' => '', 'msg' => '规则内容不能为空']);
            }
            if(empty($param['minMoney']) || empty($param['maxMoney'])){
                return json(['code'=>-2,'data'=>'','msg'=>'请填写金额范围']);
            }
            if(empty($param['percent'])){
                return json(['code'=>-3,'data'=>'','msg'=>'请填写奖励比例']);
            }
            if($param['percent'] > 100){
                return json(['code'=>-3,'data'=>'','msg'=>'奖励金额比例不能大于100！']);
            }
            try{
                $param['createTime'] = time();
                $param['type'] = $type;
                if($param['id']){
                    $res = db('room_type')->where('id', $param['id'])->update($param);
                }else{
                    unset($param['id']);
                    $res = db('room_type')->insert($param);
                }
                if($res){
                    return json(['code'=>1,'data'=>'','msg'=>'操作成功']);
                }else{
                    return json(['code'=>-1,'data'=>'','msg'=>'操作失败']);
                }
            }catch(\Exception $e){
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }
        }

        $info = db('room_type')->where('type', $type)->find();
        $this->assign([
            'info' => $info,
        ]);

        return $this->fetch();
    }

    //保底房间类型
    public function minSet(){
        $type = 1;//2-普通房间 1-保底房间
        if(request()->isPost()){

            $param = input('post.');
            if(empty($param['rule'])){
                return json(['code' => -1, 'data' => '', 'msg' => '规则内容不能为空']);
            }
            if(empty($param['minMoney']) || empty($param['maxMoney'])){
                return json(['code'=>-2,'data'=>'','msg'=>'请填写金额范围']);
            }
            if(empty($param['percent'])){
                return json(['code'=>-3,'data'=>'','msg'=>'请填写奖励比例']);
            }
            try{
                $param['createTime'] = time();
                $param['type'] = $type;
                if($param['id']){
                    $res = db('room_type')->where('id', $param['id'])->update($param);
                }else{
                    unset($param['id']);
                    $res = db('room_type')->insert($param);
                }
                if($res){
                    return json(['code'=>1,'data'=>'','msg'=>'操作成功']);
                }else{
                    return json(['code'=>-1,'data'=>'','msg'=>'操作失败']);
                }
            }catch(\Exception $e){
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }
        }

        $info = db('room_type')->where('type', $type)->find();
        $this->assign([
            'info' => $info,
        ]);

        return $this->fetch();
    }

    //挑战房间列表
    public function roomList(){
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['type'])) {
                $where['type'] = 1;//1-保底 2-普通
            }

            $result = db('room_create')->where($where)->limit($offset, $limit)->order('id', 'desc')->select();
            foreach ($result as $key => $vo) {
                // 创建人信息
                $user = db('member')->where('id',$vo['uid'])->find();
                $result[$key]['nickname'] = $user['nickname'];
                $result[$key]['typeStr'] = self::getTypeStr($vo['type']);
                $result[$key]['number'] = $vo['number']?$vo['number']:'不限制';
                $result[$key]['firstSign'] = $vo['beginTimeStr'].'-'.$vo['endTimeStr'];
                $result[$key]['secondSign'] = $vo['secondBeginStr'].'-'.$vo['secondEndStr'];
                //获取奖励比例
                $result[$key]['percent'] = db('room_type')->where('type',$vo['type'])->find()['percent'];
                $result[$key]['statusStr'] = self::getStatusStr($vo['status']);
                // 生成操作按钮
//                $result[$key]['operate'] = $this->makeBtn($vo['id']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
            }

            $return['total'] = db('room_create')->where($where)->count();  //总数据
            $return['rows'] = $result;

            return json($return);

        }
        $type = [
            1=>'保底房间',
            2=>'普通房间'
        ];
        $this->assign(['type'=>$type]);
        return $this->fetch();
    }
    /**
     * 获取房间挑战状态值
     */
    public static function getStatusStr($status){
        $arr = [
            0=>'报名中',
            1=>'挑战中',
            2=>'已结束'
        ];
        if(isset($arr[$status])){
            return $arr[$status];
        }else{
            return '';
        }
    }
    /**
     *房间类型
     */
    public static function getTypeStr($type){
        $arr = [
            1=>'保底房间',
            2=>'普通房间',
        ];
        if(isset($arr[$type])){
            return $arr[$type];
        }else{
            return '';
        }
    }
    /**
     *挑战参与状态
     */
    public static function getJoinStatus($status){
        $arr = [
            1=>'参与中',
            2=>'已失败',
            3=>'已完成',
        ];
        if(isset($arr[$status])){
            return $arr[$status];
        }else{
            return '';
        }
    }

    //挑战房间报名
    public function roomJoin(){
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['type'])) {
                $where['type'] = 1;//1-保底 2-普通
            }
            if(!empty($param['roomId'])){
                $where['roomId'] = $param['roomId'];
            }

            $result = db('room_join')->where($where)->limit($offset, $limit)->order('id', 'desc')->select();
            foreach ($result as $key => $vo) {
                // 房间信息
                $room = db('room_create')->where('id',$vo['roomId'])->find();
                $result[$key]['roomName'] = $room['name'];
                $result[$key]['beginDate'] = $room['beginDate'];
                $result[$key]['joinMoney'] = $room['money'];
                $result[$key]['day'] = $room['day'];
                $result[$key]['signNum'] = $room['signNum'];
                //奖励比例
                $roomType = db('room_type')->where('type',1)->find();
                $result[$key]['percent'] = $roomType['percent'];
                //打卡用户
                $user = db('member')->where(['id'=>$vo['uid']])->find();
                $result[$key]['nickname'] = $user['nickname'];
                $result[$key]['typeStr'] = self::getTypeStr($room['type']);
                //报名时间
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
                //报名状态
                $result[$key]['statusStr'] = self::getJoinStatus($vo['status']);
                // 生成操作按钮
//                $result[$key]['operate'] = $this->makeBtn($vo['id']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
            }

            $return['total'] = db('room_join')->where($where)->count();  //总数据
            $return['rows'] = $result;

            return json($return);

        }
        $type = [
            1=>'保底房间',
            2=>'普通房间'
        ];
        $this->assign(['type'=>$type]);
        return $this->fetch();
    }

    //打卡记录
    public function roomSign(){
        if (request()->isAjax()) {

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['type'])) {
                $where['type'] = 1;//1-保底 2-普通
            }
            if(!empty($param['roomId'])){
                $where['roomId'] = $param['roomId'];
            }

            $result = db('sign')->where($where)->limit($offset, $limit)->order('id', 'desc')->select();
            foreach ($result as $key => $vo) {
                // 房间信息
                $room = db('room_create')->where('id',$vo['roomId'])->find();
                $result[$key]['roomName'] = $room['name'];
                //打卡用户
                $user = db('member')->where(['id'=>$vo['uid']])->find();
                $result[$key]['nickname'] = $user['nickname'];
                $result[$key]['typeStr'] = self::getTypeStr($room['type']);
                $result[$key]['firstSign'] = $vo['firstSign']==1?'已签到':'未签到';
                $result[$key]['signNum'] = $room['signNum'];
                if($room['signNum'] == 2){
                    $result[$key]['secondSign'] = $vo['secondSign']==1?'已签到':'未签到';
                }else{
                    $result[$key]['secondSign'] = '';
                    $result[$key]['secondSignTime'] = '';
                }
                // 生成操作按钮
//                $result[$key]['operate'] = $this->makeBtn($vo['id']);
                $result[$key]['createTime'] = date('Y-m-d H:i:s',$vo['createTime']);
            }

            $return['total'] = db('room_create')->where($where)->count();  //总数据
            $return['rows'] = $result;

            return json($return);

        }
        $type = [
            1=>'保底房间',
            2=>'普通房间'
        ];
        $this->assign(['type'=>$type]);
        return $this->fetch();
    }

    //排行榜
    public function ranking(){
        if(request()->isAjax()){
            $type = 2;//1-打卡 2-房间挑战 3-闯关
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