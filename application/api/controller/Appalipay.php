<?php
namespace app\api\controller;
use App\Api\PrintService;
use app\common\model\Share;
use App\Config\YlyConfig;
use think\Exception;
use think\Controller;
use app\api\controller\Rebate;
use alipay;
use think\Loader;
Loader::import('alipay.apppay.aop.AopClient');
loader::import('alipay.apppay.aop.request.AlipayTradeAppPayRequest');

//Loader::import('aliuser.aop.AopClient',EXTEND_PATH,'.php');
//Loader::import('aliuser.aop.request.AlipayFundTransToaccountTransferRequest',EXTEND_PATH,'.php');
//Loader::import('aliuser.aop.SignData',EXTEND_PATH,'.php');


//Loader::import('aliuser.aop.AopClient');
Loader::import('aliuser.aop.request.AlipayFundTransToaccountTransferRequest');
Loader::import('aliuser.aop.SignData');

class Appalipay extends Controller
{

    const  GATEWAY = "https://openapi.alipay.com/gateway.do";
    const APPID = "2021001190647108";
    const  RSAPRIVATEKEY = 'MIIEowIBAAKCAQEApB3KvBuMHuRkSZnd4YY/peqvO2XdQOhbi72Vc/tm+MSv5WLmX6ILesSofbsSIZuwgR6MRPlNKtBYgvbKi87eqXJVzHlnBA+I3hQBCQWFHeWlylYtTky4e0l9nwBiNCUbg+gMO/XsPqiSJIGMVXBZnJ0ICmry5YW6W/UVZ/U16AyOHUx2z3283ZSaFPksb3rpSgZ1zIiETkEdZfoN2ADrpRLiNVcVIxfxiPEDX554WiOZYboSwd5mBdfK5dIu/Xsst43R2o/hurAWe/1f5zLYZHbp1L/iN9pWnZjeiXLJE1L8CgoE6kMvlXB6/S0bumCgWHcgLUusXQYBhZF8yst7wwIDAQABAoIBAB46WogRLtrVyF8CFysd1gVSV5pCJeF5qrrvXWKycIHrgFZow/PjXefr5vjZhvpnubfej4l4vazgGR1uJVpg7QQayhDSX5YQ4m29E3+844rhLIs7GjcszGHw5FFv+aaPGyVNjUtR/YSA+gH9VoK6IddF2SBKGJkL64padunh8V7e/W0pDeKRsyRSr26akIlXzTj83qK2BehTRxi4I5tFlFIT/iBLNtaSkGDPNpM10TtYXPBCs/UPDWz/9+r9of7/dkKKTzGTUTFciWEQJZCOBkaRCuoerCCFVxWfJzXfDXsZyAvcSBQ7zS9a5h/bszcFX9BYKq3m3Av9xJp/oJUpKOkCgYEA3GmH2nnRN1wEG9Ok5qG58ym4LiJtout7Flk0B1HCcxCAuuwBSav/t98W8qJfy/8XPZU8XB6g6yUVgPbmxe6i5uae5mDMcMC4QB/u1jXX/fI0jbZZH3sDyF15C3ui2/m0b4DYGm3QH+lTIrmpu8DX+2q4KWMwqtQYTNCyOdFOVEcCgYEAvp1XU13OKc2I3RHCkl20m0FIM4ahqhbuKkFHeKgGMasmj8rt2jAf504zPHtoRhEVeNGx0QZ4tAgyeucwMA8hy308KhrY83Q3RolTDBBVFp8mRTtmu/fe8O5ypQhK5y4JdGNEtRhUaDDKB7iwtzrR/AkKdqZxk9nclBq6gbY+hqUCgYEArmu8nEGGNL7WaYbkmbYlxq2fGLMZ+7FJaHgS2i/zJsdKd6FHq5s287TRHhUspewp4gv1Bhke0rY3/CRmnv+nXf3mhPzZC+kWZXhfsphNYqKGwBYrFORuK+L8ZP/j8xv5k7tsSA3ag0ZLCdHO5ctHn6fmMKpT2vuYd0E3qNVfnQUCgYBJEgHe2G/mwH692EgRWdCZT55A+lRQ4rdsBVhDnY26TpTavH0PjjE8t1KW1ev0KCpmBWS371YoFZhcWvGzCqn22sxMi7wtH5js9kmar0we/uVp1sqcNfoFvDLApvwnwRMoxcEZ9visdD+OVDOSGf9TMZgMQeP6PKL9N33VqaowbQKBgD5cB4wZYPbhGZm64N1ZB9dIPPoXrzBEL6kmRkW5eEu7OjM83M9sNPL9SqAfcYGMFnKhtslN5HQEeNIc+EBXMbXYDtsOFHb7ZHqwW5BAO4E5L1yObKMxz7L6gPQhIusCmG5oCyBHGV8UcedC/gnZIkjq/cmrqFPoMHJ9cN7McCB1'; // 商户私钥

    const PUBLICKEY ='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApB3KvBuMHuRkSZnd4YY/peqvO2XdQOhbi72Vc/tm+MSv5WLmX6ILesSofbsSIZuwgR6MRPlNKtBYgvbKi87eqXJVzHlnBA+I3hQBCQWFHeWlylYtTky4e0l9nwBiNCUbg+gMO/XsPqiSJIGMVXBZnJ0ICmry5YW6W/UVZ/U16AyOHUx2z3283ZSaFPksb3rpSgZ1zIiETkEdZfoN2ADrpRLiNVcVIxfxiPEDX554WiOZYboSwd5mBdfK5dIu/Xsst43R2o/hurAWe/1f5zLYZHbp1L/iN9pWnZjeiXLJE1L8CgoE6kMvlXB6/S0bumCgWHcgLUusXQYBhZF8yst7wwIDAQAB'; // 支付宝公钥

	public static function recharge($uid,$money) {
		header('Access-Control-Allow-Origin: *');
		header('Content-type: text/plain');

        $order_no = "CG".time().rand(1111,9999);
        //创建订单
        $params = [
            'uid'=>$uid,
            'money'=>$money,
            'createTime'=>time(),
            'status'=>0,
            'orderNo'=>$order_no,
            'type'=>2,
        ];
        $res = db('money_recharge')->insert($params);
        if(!$res){
            Share::jsonData(0,'','充值下单失败');
        }
		$aop = new \AopClient();
		$aop->gatewayUrl =self::GATEWAY;
		$aop->appId = self::APPID;
		$aop->rsaPrivateKey = self::RSAPRIVATEKEY;
		$aop->format = "json";
		$aop->charset = "UTF-8";
		$aop->signType = "RSA2";
		$aop->alipayrsaPublicKey = self::PUBLICKEY;

		$request = new \AlipayTradeAppPayRequest();

		$bizcontent = "{\"body\":\"".'闯关-余额充值'."\","
		                . "\"subject\": \"".'闯关-余额充值'."\","
		                . "\"out_trade_no\": \"".$order_no."\","
		                . "\"timeout_express\": \"30m\"," 
		                . "\"total_amount\": \"".$money."\","
		                . "\"product_code\":\"QUICK_MSECURITY_PAY\""
		                . "}";

        $hostUrl = config('hostUrl');
        $notifyUrl = $hostUrl.'/api/api/aliNotify';
		$request->setNotifyUrl($notifyUrl);
		$request->setBizContent($bizcontent);

		$response = $aop->sdkExecute($request);
		echo $response;


	}
	public static function notify(){

        $data = $_POST;
        file_put_contents("./uploads/alipay_notify.txt",json_encode($data).'-支付回调：'.PHP_EOL,FILE_APPEND);

		$c = new \AopClient;
		$c->alipayrsaPublicKey = self::PUBLICKEY;
        
//		$result = $c->rsaCheckV1($data,$c->alipayrsaPublicKey,$data['sign_type']);
		$signstr="";

		if($_POST['trade_status']=="TRADE_SUCCESS")
		{
            $out_trade_no = $_POST['out_trade_no'];
            $payMoney = $_POST['buyer_pay_amount'];
            $rechargeOrder = db('money_recharge')->where(["orderNo"=>$out_trade_no,'money'=>$payMoney])->find();
            if($rechargeOrder['status'] ==0){
                $res = db('money_recharge')->where('orderNo',$out_trade_no)->update(['status'=>1,'payTime'=>time()]);
                $uid = $rechargeOrder['uid'];
                if($res){
                    $money = $rechargeOrder['money'];
                    $user = db('member')->where('id',$uid)->find();
                    $add = $user['money'] + $money;
                    $result = db('member')->where('id',$uid)->update(['money'=>$add]);
                    if($result){
                        //记录充值日志
                        Share::userMoneyRecord($uid,$money,'余额充值-支付宝',1,0);
                        echo "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
                    }
                }
            }
			echo 'success';

            return 'success';
		}else{
			$signstr="SignErr";
            file_put_contents("./uploads/czlog.txt",$signstr.$_POST['out_trade_no'].PHP_EOL,FILE_APPEND);

            return;
		}
	}

    public function testPrint()
    {
        $this->printOrder('4004668693',1596);
    }



// 支付宝
    public static function alipayReturn($account,$amount,$username) {
        $order_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

//            $res = $this -> userWithDraw($order_no,'863758424@qq.com',0.1,'吴亚丁');
        $res = self::userWithDraw($order_no,$account,$amount,$username);

        return $res;
    }


    /**
     * @User 一秋
     * @param $id  提现数据id
     * @param $out_biz_no 编号
     * @param $payee_account 提现的支付宝账号
     * @param $amount 转账金额
     * @param $payee_real_name 账号的真实姓名
     * @param $returnId 提现申请id
     * @return bool|Exception
     */
    public static function userWithDraw(
        $out_biz_no,$payee_account,$amount,$payee_real_name,$returnId)
    {
        $payer_show_name = '用户余额提现';
        $remark = '提现到支付宝';
        $aop = new \AopClient();
        $aop->gatewayUrl = self::GATEWAY;//支付宝网关 https://openapi.alipay.com/gateway.do这个是不变的
        $aop->appId = self::APPID;//商户appid 在支付宝控制台找
        $aop->rsaPrivateKey =self::RSAPRIVATEKEY;//私钥 工具生成的
        $aop->alipayrsaPublicKey= self::PUBLICKEY;//支付宝公钥 上传应用公钥后 支付宝生成的支付宝公钥
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='utf-8';
        $aop->format='json';
        $request = new \AlipayFundTransToaccountTransferRequest();
        $request->setBizContent("{" .
            "\"out_biz_no\":\"$out_biz_no\"," .
            "\"payee_type\":\"ALIPAY_LOGONID\"," .
            "\"payee_account\":\"$payee_account\"," .
            "\"amount\":\"$amount\"," .
            "\"payer_show_name\":\"$payer_show_name\"," .
            "\"payee_real_name\":\"$payee_real_name\"," .
            "\"remark\":\"$remark\"" .
            "}");
        $result = $aop->execute($request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        file_put_contents('./uploads/reponse.txt',$result);
        if(!empty($resultCode)&&$resultCode == 10000){
            //提现成功以后 更新表状态
            //并且记录 流水等等
            file_put_contents("./uploads/txlog.txt",'支付宝账号：'.$payee_account.','.'真实姓名：'.$payee_real_name.','.'转账金额：'.$amount.PHP_EOL,FILE_APPEND);
            return  ['code'=>1,'message'=>'提现成功'];
        }
        return ['code'=>0,'message'=>'提现失败'];
    }


    /**
    $amount 发送的金额目前发送金额不能少于1元
    $re_openid, 发送人的 openid
    $desc  //  企业付款描述信息 (必填)
    $check_name    收款用户姓名 (选填)
     */
    function sendMoney($total_amount,$re_openid,$desc='用户餐厅消费',$check_name=''){
        $total_amount = (100) * $total_amount;

        $data=array(
            'mch_appid'=>$this->appid,//商户账号appid
            'mchid'=> $this->MCHID,//商户号
            'nonce_str' => $this -> createNoncestr(),//随机字符串
//            'partner_trade_no'=> date('YmdHis').rand(1000, 9999),//商户订单号
            'partner_trade_no'=> date('YmdHis').rand(1000, 9999),//商户订单号
            'openid'=> $re_openid,//用户openid
            'check_name'=>'NO_CHECK',//校验用户姓名选项,
            're_user_name'=> $check_name,//收款用户姓名
            'amount'=>$total_amount,//金额
            'desc'=> $desc,//企业付款描述信息
            'spbill_create_ip'=> $this->get_client_ip(),//Ip地址partner_trade_no
        );
        $secrect_key=$this->SECRECT_KEY;///这个就是个API密码。MD5 32位。
        $data=array_filter($data);
        ksort($data);
        $str='';
        foreach($data as $k=>$v) {
            $str.=$k.'='.$v.'&';
        }
        $str.='key='.$secrect_key;
        $data['sign']=md5($str);
        $xml = $this -> arraytoxml($data);

        $url='https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers'; //调用接口

        $res = $this -> curl($xml,$url);

        $return = $this -> xmltoarray($res);

        if ($return['result_code'] == 'FAIL') {
            return $return['err_code_des'];
        }
        //返回来的结果
        // [return_code] => SUCCESS [return_msg] => Array ( ) [mch_appid] => wxd44b890e61f72c63 [mchid] => 1493475512 [nonce_str] => 616615516 [result_code] => SUCCESS [partner_trade_no] => 20186505080216815
        // [payment_no] => 1000018361251805057502564679 [payment_time] => 2018-05-15 15:29:50


        $responseObj = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
        echo $res = $responseObj->return_code;  //SUCCESS  如果返回来SUCCESS,则发生成功，处理自己的逻辑

        return true;
    }

    function createNoncestr($length =32)
    {

        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";

        $str ="";

        for ( $i = 0; $i < $length; $i++ )  {

            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);

        }
        return $str;
    }

    function unicode() {
        $str = uniqid(mt_rand(),1);
        $str=sha1($str);
        return md5($str);
    }

    function arraytoxml($data){
        $str='<xml>';
        foreach($data as $k=>$v) {
            $str.='<'.$k.'>'.$v.'</'.$k.'>';
        }
        $str.='</xml>';
        return $str;
    }

    function xmltoarray($xml) {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring),true);
        return $val;
    }

    function curl($param="",$url) {

        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init();                                      //初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);                 //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);                    //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);                      //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);           // 增加 HTTP Header（头）里的字段
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            // curl_setopt($ch,CURLOPT_SSLCERT,ROOT_PATH.'public\cert\apiclient_cert.pem'); //这个是证书的位置绝对路径
            // curl_setopt($ch,CURLOPT_SSLKEY,ROOT_PATH.'public\cert\apiclient_key.pem'); //这个也是证书的位置绝对路径
            // curl_setopt($ch,CURLOPT_SSLCERT,'public\cert\apiclient_cert.pem'); //这个是证书的位置相对路径
            // curl_setopt($ch,CURLOPT_SSLKEY,'public\cert\apiclient_key.pem'); //这个也是证书的位置相对路径
            curl_setopt($ch, CURLOPT_SSLCERT, 'cert'.DIRECTORY_SEPARATOR.'apiclient_cert.pem');
            curl_setopt($ch, CURLOPT_SSLKEY, 'cert'.DIRECTORY_SEPARATOR.'apiclient_key.pem');
        $data = curl_exec($ch);                                 //运行curl
        curl_close($ch);
        return $data;
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    function get_client_ip($type = 0,$adv=false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }


}