<?php
/**
 * User: nickbai
 * Date: 2017/10/31 12:47
 * Email: 1902822973@qq.com
 */
namespace app\admin\controller;

class System extends Base
{
    // 自动回复设置
    public function reply()
    {
        if(request()->isPost()){

            $param = input('post.');
            if(empty($param['word'])){
                return json(['code' => -1, 'data' => '', 'msg' => '回复内容不能为空']);
            }

            try{
                db('reply')->where('id', 1)->update($param);
            }catch(\Exception $e){
                return json(['code' => -2, 'data' => '', 'msg' => $e->getMessage()]);
            }

            return json(['code' => 1, 'data' => '', 'msg' => '设置成功']);
        }

        $info = db('reply')->where('id', 1)->find();
        $this->assign([
            'info' => $info,
            'status' => config('kf_status')
        ]);

        return $this->fetch();
    }

    // 客服设置
    public function customerService()
    {
        if(request()->isPost()){

            $param = input('post.');
            db('kf_config')->where('id', 1)->update($param);

            return json(['code' => 1, 'data' => '', 'msg' => '设置成功']);
        }

        $this->assign([
            'config' => db('kf_config')->where('id', 1)->find(),
            'status' => config('kf_status')
        ]);

        return $this->fetch();
    }

    // 历史会话记录
    public function wordsLog()
    {
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            // 默认显示最近7天
            $start = input('param.start');
            $end = input('param.end');

            $temp = db('chat_log');
            $countTmp = db('chat_log');
            if(!empty($param['searchText'])){
                $temp = $temp->where('from_name', $param['searchText'])->whereOr('to_name', $param['searchText']);
                $countTmp = $countTmp->where('from_name', $param['searchText'])->whereOr('to_name', $param['searchText']);
            }

            if(!empty($start) && !empty($end) && $start <= $end){
                $temp = $temp->whereBetween('time_line', [strtotime($start), strtotime($end . ' 23:59:59')]);
                $countTmp = $countTmp->whereBetween('time_line', [strtotime($start), strtotime($end . ' 23:59:59')]);
            }

            $result = $temp->limit($offset, $limit)->order('id', 'desc')->select();
            foreach($result as $key=>$vo){
                $result[$key]['time_line'] = date('Y-m-d H:i:s', $vo['time_line']);
            }

            $return['total'] = $countTmp->count();  //总数据
            $return['rows'] = $result;

            return json($return);

        }

        return $this->fetch();
    }

    /**
     * 陌生刘：💻
     * Notes:路由设置
     * User: lyc
     * Date: 2019/7/22
     * Time: 11:42
     * @return mixed|\think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function routing()
    {
        if(request()->isPost()){

            $param = input('post.');
            db('routing')->where('id', 1)->update($param);

            return json(['code' => 1, 'data' => '', 'msg' => '设置成功']);
        }

        $this->assign([
            'routing' => db('routing')->where('id', 1)->find(),
        ]);

        return $this->fetch();
    }
}