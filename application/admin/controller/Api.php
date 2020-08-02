<?php
/**
 * User: nickbai
 * Date: 2017/10/23 13:33
 * Email: 1902822973@qq.com
 */

namespace app\admin\controller;


class Api extends Base {
    //接口

    //分类图片上传
    // 上传客服头像
    public function uploadCateImg ()
    {
        if (request()->isAjax()) {

            $file = request()->file('file');
            if (!empty($file)) {
                // 移动到框架应用根目录/public/uploads/category 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/category');
                if ($info) {
                    $src = '/uploads/category' . '/' . date('Ymd') . '/' . $info->getFilename();
                    return json(['code' => 0, 'data' => ['src' => $src], 'msg' => 'ok']);
                } else {
                    // 上传失败获取错误信息
                    return json(['code' => -1, 'data' => '', 'msg' => $file->getError()]);
                }
            }
        }
    }
    // 上传客服头像
    public function uploadImg ()
    {
        if (request()->isAjax()) {

            $file = request()->file('file');
            if (!empty($file)) {
                // 移动到框架应用根目录/public/uploads/category 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/static');
                if ($info) {
                    $src = '/uploads/static' . '/' . date('Ymd') . '/' . $info->getFilename();
                    return json(['code' => 0, 'data' => ['src' => $src], 'msg' => 'ok']);
                } else {
                    // 上传失败获取错误信息
                    return json(['code' => -1, 'data' => '', 'msg' => $file->getError()]);
                }
            }
        }
    }
}