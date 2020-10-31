<?php
	namespace app\api\controller;
	use app\common\model\Share;
	use think\Controller;


class Appwxpay extends Controller
{
    const APPID = 'wxec196ca61616173c';//wxec196ca61616173c
    const MCHID = '1602479152';//1602479152
    const KEY = '28b9f167e4e3ea093ef43b39d165a796';//28b9f167e4e3ea093ef43b39d165a796
	public static function recharge($uid,$money) {

	    //--********************** author:loveAKY 修改支付参数 date:2020-7-18 18:16

		$appid= self::APPID;
		$mch_id= self::MCHID;
		$key= self::KEY;

        //*********************************  end

        $out_trade_no = "CG".time().rand(1111,9999);
		//创建订单
        $params = [
            'uid'=>$uid,
            'money'=>$money,
            'createTime'=>time(),
            'status'=>0,
            'orderNo'=>$out_trade_no,
            'type'=>1,
        ];
        $res = db('money_recharge')->insert($params);
        if(!$res){
            Share::jsonData(0,'','充值下单失败');
        }
		$nonce_str=self::getNonceStr();
		$body = '闯关-余额充值';
		$total_fee = ($money * 100);
		// dump($body);
		// exit;
        $hostUrl = config('hostUrl');
		$spbill_create_ip=$_SERVER['REMOTE_ADDR'];
		$notify_url=$hostUrl."/api/api/wxNotify";
		$trade_type="APP";
		$str1="appid=$appid&body=$body&mch_id=$mch_id&nonce_str=$nonce_str&notify_url=$notify_url&out_trade_no=".$out_trade_no."&spbill_create_ip=$spbill_create_ip&total_fee=$total_fee&trade_type=$trade_type&key=$key";
		$sign=strtoupper(md5($str1));


$post_data =<<<xml
<xml>
   <appid><![CDATA[$appid]]></appid>
   <body><![CDATA[$body]]></body>
   <mch_id><![CDATA[$mch_id]]></mch_id>
   <nonce_str><![CDATA[$nonce_str]]></nonce_str>
   <notify_url><![CDATA[$notify_url]]></notify_url>
   <out_trade_no><![CDATA[$out_trade_no]]></out_trade_no>
   <spbill_create_ip><![CDATA[$spbill_create_ip]]></spbill_create_ip>
   <total_fee><![CDATA[$total_fee]]></total_fee>
   <trade_type><![CDATA[APP]]></trade_type>
   <sign><![CDATA[$sign]]></sign>
</xml>
xml;

   //初始化

	$data = self::getcurl('https://api.mch.weixin.qq.com/pay/unifiedorder',$post_data);
//    var_dump($data);die;
    $ar=self::xmlToArray($data);//将xml转换成数组的结果
    if($ar['return_code'] == 'FAIL'){
        Share::jsonData(0,'',$ar['return_msg']);
    }
//    var_dump($ar);die;
    //获取当前时间的时间戳
    $time=intval(time());
    //进行二次签名
    $str2="appid=$appid&noncestr=$nonce_str&package=Sign=WXPay&partnerid=$mch_id&prepayid=$ar[prepay_id]&timestamp=$time&key=$key";
    $sign1=strtoupper(md5($str2));//签名md5加密后，转换为大写
     echo "{\"appid\":\"$ar[appid]\",\"noncestr\":\"$nonce_str\",\"package\":\"Sign=WXPay\",\"partnerid\":\"$mch_id\",\"prepayid\":\"$ar[prepay_id]\",\"timestamp\":$time,\"sign\":\"$sign1\"}";
	}


    private static	function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

 	private static	function getNonceStr() {
	    $code = "";
	    for ($i=0; $i < 10; $i++) {
	        $code .= mt_rand(1000,2000);        //获取随机数
	    }
	    $nonceStrTemp = md5($code);
	    $nonce_str = mb_substr($nonceStrTemp, 1,30);  //MD5加密后截取30位字符
	    return $nonce_str;
	}

    protected static function toXml($data)
    {
        if (!is_array($data) || count($data) <= 0) {
            throw new InvalidArgumentException('convert to xml error !invalid array!');
        }
        $xml = '<xml>';
        foreach ($data as $key => $val) {
            $xml .= (is_numeric($val) ? "<{$key}>{$val}</{$key}>" : "<{$key}>{$val}</{$key}>");
            // $xml .= (is_numeric($val) ? "<{$key}>{$val}</{$key}>" : "<{$key}><![CDATA[{$val}]]></{$key}>");
        }
        return $xml . '</xml>';
    }
	private static function getcurl($url,$data){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
	    $output = curl_exec($ch);
	    curl_close($ch);
	    return $output;
	}

    /**
     * 解析XML数据
     * @param string $xml 源数据
     * @return mixed
     */
    protected static function fromXml($xml)
    {
        if (!$xml) {
            throw new InvalidArgumentException('convert to array error !invalid xml');
        }
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA), JSON_UNESCAPED_UNICODE), true);
    }

	public static function notify(){

            $xml=file_get_contents('php://input', 'r');
            $paramFile = './uploads/wxParam.txt';
            file_put_contents($paramFile, $xml.PHP_EOL);
            $data = array();
            if( empty($xml) ){
                return false;
            }
            $data = self::fromXml( $xml );

            file_put_contents('./uploads/wxParam1.txt', json_encode($data).PHP_EOL,FILE_APPEND);
            if( !empty($data['return_code']) ){
                if( $data['return_code'] == 'FAIL' ){
                    return false;
                }
            }
            if ($data['result_code'] == 'SUCCESS') {
                $file  = 'wxlog.txt';//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
                $content = $data['total_fee'] / 100;
                file_put_contents($file, $content.PHP_EOL,FILE_APPEND);
                $out_trade_no = $data['out_trade_no'];
                $rechargeOrder = db('money_recharge')->where("orderNo",$out_trade_no)->find();
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
                            Share::userMoneyRecord($uid,$money,'余额充值-微信',1,0);
                            echo "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
                        }
                    }
                }
            }
        echo "<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[failed]]></return_msg></xml>";

            exit();
        }

    /**
     * 微信提现
     * uid 用户id
     * orderNumber  订单号
     * money  提现金额
     * serFee  服务费
     * type  1-退款 2-提现
     */
    public static function WeixinReturn($uid,$orderNumber,$money){
        if(!$uid  || !$money){
            return ['code'=>0,'message'=>'参数错误'];
        }
        $user = db('member')->where('id',$uid)->find();
        if(!$user){
            return ['code'=>0,'message'=>'没有该用户'];
        }
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $openid = $user['openid'];//用户openid
        $mch_appid = self::APPID;//appid
        $mchid = self::MCHID;;//商户号
        $nonce_str = md5($orderNumber);//随机字符串
        $partner_trade_no = $orderNumber;//商户订单号
        $check_name = 'NO_CHECK';//校验用户姓名选项 NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名
        $amount = 100*$money;//金额  分
        $desc = '用户余额提现';///企业付款备注
        $spbill_create_ip = $_SERVER['REMOTE_ADDR'];//ip地址
        $signArr = ['openid'=>$openid,'mch_appid'=>$mch_appid,'mchid'=>$mchid,'nonce_str'=>$nonce_str,'partner_trade_no'=>$partner_trade_no,'check_name'=>$check_name,'amount'=>$amount,'desc'=>$desc,'spbill_create_ip'=>$spbill_create_ip];
        //生成签名
        ksort($signArr);
        $key = self::KEY;
        $sign = self::signWxpay($signArr,$key);//签名
        $signArr['sign'] = $sign;
        //组合xml数据
        $xml = self::getXml($signArr);
        $return = self::postCa($url,$xml);
        $return = (array)simplexml_load_string($return, 'SimpleXMLElement', LIBXML_NOCDATA); //将微信返回的XML转换成数组
        if(isset($return['return_code']) && $return['return_code'] == 'SUCCESS' && $return['result_code'] == 'SUCCESS'){
            return ['code'=>1,'message'=>'success'];
        }elseif(isset($return['return_msg'])){
            return ['code'=>0,'message'=>$return['return_msg']];
        }else{
            return ['code'=>0,'message'=>'微信提现接口请求失败'];
        }
    }
    /**
     * 微信签名
     * 签名生成
     * @param $signArr
     * md5算法加密 转大写
     */
    public static function signWxpay($signArr,$key){
        $signStr = '';
        foreach($signArr as $k => $v){
            if($v != ''){
                $signStr .= $k.'='.$v.'&';
            }
        }
        $signStr.='key='.$key;
        $signStr = md5($signStr);
        $signStr = strtoupper($signStr);
        return $signStr;
    }
    /**
     * xsm数据组合
     */
    public static function getXml($data){
        if(is_array($data)){
            $xml = '<xml>';
            foreach($data as $k => $v){
                $xml .= "<$k>$v</$k>";
            }
            $xml .= "</xml>";
            return $xml;
        }else{
            return '';
        }
    }
    /**
     * post请求
     * @param $url
     * @param string $post_data
     * @param int $timeout
     * @return mixed
     * @Obelisk
     */
    public static  function postCa($url, $post_data = '', $timeout = 5){//curl
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);//设置执行最长秒数
        curl_setopt ($ch, CURLOPT_POST, 1);
        if(is_array($post_data)){
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        }else{
            curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);//微信 xml数据
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //默认格式为PEM，可以注释
        $dir = dirname(__FILE__);
        $cert = $dir."/wxCa/apiclient_cert.pem";
        $key = $dir.'/wxCa/apiclient_key.pem';
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,$cert);
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,$key);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
//            $info = curl_getinfo($ch);
//            var_dump($info);
            echo "call faild, errorCode:$error\n";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }
}