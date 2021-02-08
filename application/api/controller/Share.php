<?php
/**
 * Created by
 * Author loveAKY
 * Date 2020/7/26
 * time 16:22
 */

namespace app\api\controller;


use think\Controller;

class Share extends Controller
{

    protected $appid = 'wxec1c0894849624f3'; // appid
    protected $secret = "9d3162d792699ad270d64d7994d98c2b";
    protected  $host = 'http://cgyq.hualin688.com';

    public function index(){
        $inviterCode = input('inviterCode');
        file_put_contents("./uploads/share_index_code.txt",json_encode([$inviterCode]).PHP_EOL,FILE_APPEND);
        $redirect_uri = urlencode ($this->host.'/api/share/getapp?inviterCode='.$inviterCode);

        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&inviterCode=".$inviterCode."#wechat_redirect";

        $this->redirect($url);
    }

    //
    public function getapp(){
        $code = $_GET["code"];
        $inviterCode = $_GET["inviterCode"];

        $record = json_encode(['code'=>$code,'inviterCode'=>$inviterCode]);
        file_put_contents("./uploads/share_code.txt",json_encode($record).PHP_EOL,FILE_APPEND);

        $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->secret."&code=$code&grant_type=authorization_code";
        $oauth2 = $this->getJson($oauth2Url);

        if(!isset($oauth2['access_token'])){
            echo '服务器出错（token），请退出刷新重试！';die;
            \app\common\model\Share::jsonData(0,'','');
        }
        $access_token = $oauth2["access_token"];

        $openid = $oauth2['openid'];
        $get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" .$access_token. "&openid=".$openid;

        $userinfo = $this->getJson($get_user_info_url);

        file_put_contents("./uploads/invite_user.txt",json_encode($userinfo).PHP_EOL,FILE_APPEND);

        if(!isset($userinfo['unionid'])){
            echo '服务器出错（unionid），请退出刷新重试！';die;
            \app\common\model\Share::jsonData(0,'','服务器出错，请退出刷新重试');
        }

        $unionid=$userinfo["unionid"];

        if($unionid!=''&&$inviterCode){
            //请求数据接口
            $hostUrl = config('hostUrl');
            file_get_contents($hostUrl."/api/api/userShare?openid=".$openid."&unionid=".$unionid.'&inviterCode='.$inviterCode);

        }
        $hostUrl = $this->host;
        $this->redirect($hostUrl);
        exit();
    }


    private function getJson($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }

    /**
     * excel读取
     * 渠道小组数据获取
     */
    public function doExcel(){
        //引入类库
        include "./../extend/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php";
        include_once "./../extend/PinYin.php";

        //elsx文件路径
        $inputFileName = "./uploads/1208.xlsx";
        $oldData = file_get_contents('./uploads/newData1203.txt');
        $oldArr = json_decode($oldData,true);
        var_dump(count($oldArr));
//        die;
        // 读取excel文件
        try {
            $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(\Exception $e) {

        }

        // 确定要读取的sheet，什么是sheet，看excel的右下角，真的不懂去百度吧
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // 获取excel文件的数据，$row=2代表从第二行开始获取数据
        $insertAll = [];
        for ($row = 2; $row <= $highestRow; $row++){
            //数组信息  0-小组id  1-小组名称 2-渠道id  3-子渠道id
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            $rowData = $rowData[0];
//这里得到的rowData都是一行的数据，得到数据后自行处理，我们这里只打出来看看效果
            if((count($rowData) >=4 )){
                $insertAll[] = [
                    'group_id'=>$rowData[0],
                    'promotion_second_channel_id'=>$rowData[3],
                ];
            }
        }
        var_dump(count($insertAll));
        foreach($insertAll as $k =>$v){
            if(!in_array($v,$oldArr)){
                $oldArr[] = $v;
            }
        }
        var_dump(count($oldArr));
        $newData = json_encode($oldArr);
        file_put_contents("./uploads/newData1203.txt",$newData);
        var_dump(json_encode($oldArr));
    }
    /**
     * excel读取
     * 渠道小组数据获取
     */
    public function doExcel1(){
        //引入类库
        include "./../extend/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php";
        include_once "./../extend/PinYin.php";

        //elsx文件路径
        $inputFileName = "./uploads/1208.xlsx";
        // 读取excel文件
        try {
            $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(\Exception $e) {

        }

        // 确定要读取的sheet，什么是sheet，看excel的右下角，真的不懂去百度吧
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // 获取excel文件的数据，$row=2代表从第二行开始获取数据
        $insertAll = [];
        for ($row = 2; $row <= $highestRow; $row++){
            //数组信息  0-工作室名称  1-医生姓名 2-医生手机  3-医生id 4-已注册 5-已入组  6-工作室id 7-渠道id  8-子渠道id
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
            $rowData = $rowData[0];
//这里得到的rowData都是一行的数据，得到数据后自行处理，我们这里只打出来看看效果
            $insertAll[] = [
                'workName'=>$rowData[0],
                'doctorName'=>$rowData[1],
                'doctorPhone'=>$rowData[2],
                'doctorId'=>$rowData[3],
                'isRegister'=>$rowData[4],
                'isGroupIn'=>$rowData[5],
                'workId'=>$rowData[6],
                'channelTopId'=>$rowData[7],
                'channelSecondId'=>$rowData[8],
            ];
        }
        var_dump(count($insertAll));
        $newData = json_encode($insertAll);
        file_put_contents("./uploads/newData1208.txt",$newData);
        var_dump(json_encode($newData));
    }
    public function getData(){
        $data = file_get_contents("./uploads/newData1208.txt");
        $data = json_decode($data,true);
        var_dump(($data));die;
        $insertAll = [];
        foreach($data as $k => $v){
            if (!in_array($k,[284,285,288,289])){
                continue;
            }
            $time = time();
            $keyWords = $v['doctorName'].",".$v['doctorId'].",".$v['doctorPhone'];
            $insertAll[] = " insert into promotion_records(`promotion_top_channel_id`,`promotion_second_channel_id`,`promotion_second_channel_title`,`id_user`,`doctor_name`,`doctor_phone`,`target_user_type`,`status`,`finished_at`,`keywords`,`created_at`,`updated_at`) value(".$v['channelTopId'].",".$v['channelSecondId'].",'工作室',".$v['doctorId'].",'".$v['doctorName']."',".$v['doctorPhone'].",-1,1,$time,'".$keyWords."',$time,$time)";
        }
        $str = implode(";\n",$insertAll);
        var_dump($str);

    }


}