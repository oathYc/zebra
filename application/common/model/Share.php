<?php
namespace app\common\model;
/**
 * 陌生刘：💻
 * Created by PhpStorm.
 * User: StubbornGrass - liu
 * Date: 2019/6/13
 * Time: 17:21
 */
class Share extends \think\Model
{
    /**
     * @param int $code
     * @param array $data
     * @param string $message
     * 数据json输出
     * code  0-失败  1-成功
     */
    public static function jsonData($code=1,$data=[],$message='success'){
        if($data){
            //处理域名
            $host = config('hostUrl');
            $data = self::addHost($host,$data);
        }
        $json = [
            'code'=>$code,
            'message'=>$message,
            'data'=>$data,
        ];
        $json = json_encode($json);
        die($json);
    }

    public static function checkEmptyParams($params=[]){
        foreach($params as $k => $v){
            if(empty($v)){
                $message = "参数{$k}不能为空！";
                self::jsonData(0,[],$message);
            }
        }
    }

    public static function addHost($host,$data){
//        $data =  "/uploads/avatar/20200714/0ed01490e1d19088495af62593bf609c.jpg";
        if(is_array($data)){
            foreach($data as $k => $val){
                $val = self::addHost($host,$val);
                $data[$k] = $val;
            }
        }else{
            if($data){
                if(strpos($data,"uploads/product/2020") == 1){//商品
                    $data = $host.$data;
                }elseif(strpos($data,"uploads/avatar/2020") == 1){//头像
                    $data = $host.$data;
                }elseif(strpos($data,"uploads/file/2020") == 1){//文件
                    $data = $host.$data;
                }
            }
        }
        return $data;
    }

    /**
     * curl请求
     * post请求
     */
    public static  function curlPost($url , $data=array()){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        // POST数据

        curl_setopt($ch, CURLOPT_POST, 1);

        // 把post的变量加上

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $output = curl_exec($ch);

        curl_close($ch);
        // 显示错误信息
        if (curl_error($ch)) {
            print "Error: " . curl_error($ch);
        } else {
            // 打印返回的内容
            var_dump($output);
            curl_close($ch);die;
        }
        return $output;

    }

    /**
     * curl请求
     * post请求
     */
    public static  function curlget($httpUrl){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $httpUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        // POST数据

        curl_setopt($ch, CURLOPT_POST, 0);

        $output = curl_exec($ch);

        curl_close($ch);
        // 显示错误信息
        if (curl_error($ch)) {
            print "Error: " . curl_error($ch);
        } else {
            // 打印返回的内容
            var_dump($output);
            curl_close($ch);
        }

        return $output;

    }
}