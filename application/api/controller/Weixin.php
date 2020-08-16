<?php
	namespace app\api\controller;

	use App\Api\PrintService;
    use App\Config\YlyConfig;
    use think\Controller;
	use think\Db;
    use think\Exception;
    use think\Loader;
    use think\Session;
	use app\api\controller\Rebate;
	use app\api\controller\Tx;
	/**
	 * 
	 */


    Loader::import('aliuser.aop.AopClient');
    Loader::import('aliuser.aop.request.AlipayFundTransToaccountTransferRequest');
    Loader::import('aliuser.aop.SignData');

	header('Access-Control-Allow-Origin:*'); // 允许跨域请求
	header('Access-Control-Allow-Headers:Content-Type'); // 允许跨域请求
	class Weixin extends Controller
	{


        protected $appid = 'wxfc89d0b322f96cc8'; // appid

        protected $MCHID = '1524220761'; // 商户号  1601114191

        protected $SECRECT_KEY = '70921929a63a8cc192d1c45e0fc6037a'; // 密钥

		// 微信登陆
		public function index() {
			$wchat = new \wechat\WechatOauth();

			$code = request()->param('code',"");

			$user = $wchat->getUserAccessUserInfo($code);

			if (isset($user['openid'])) {
				dump($user['openid']);
				exit;
				$res = Db::name('user') -> where('openid',$user['openid']) -> find();
				if ($res) {
					$data = $res;
				}
				else {
					Db::name('user') -> insert([
							'openid' => $user['openid'],
							'sex' => $user['sex'],
							'nickname' => $user['nickname'],
							'city' => $user['city'],
							'province' => $user['province'],
							'country' => $user['country'],
							'img' => $user['headimgurl'],
							'create_time' => time(),
						]);
					$data = $user;
					$data['img'] = $user['headimgurl'];
				}
				return ajax(200,$data,'登录成功');
			}
			else {
				return ajax(100,'','登录失败');
			}
		}

		// 微信支付
		public function pay() {
			// dump($_SERVER);
			// exit();
			$id = input('oid'); // 订单ID

			$res = Db::name('order') -> where('id',$id) -> find();

			$appid = 'wxa1f004b280729c17';

			$mch_id = '1515790821';

			$notify_url = 'http://vfmanage.voofan.com/api/weixin/notify';

			$str = strtoupper(md5($this->createNonceStr()));

			$ip = $this -> get_client_ip();

			$sign = "appid=".$appid."&body=".$res['goods_name']."&mch_id=".$mch_id."&nonce_str=".$str."&notify_url=".$notify_url."&out_trade_no=".$res['order_no']."&scene_info=".'{"h5_info": {"type":"Wap","wap_url": "http://www.voofan.com","wap_name": "唯凡优品"}}'."&spbill_create_ip=".$ip."&total_fee=".($res['price'] * 100)."&trade_type=MWEB&key=06a834fca809160f545630c7121f959c";

			$sign = strtoupper(md5($sign));

			$wechat = [
			    'appid'     => $appid, // 公众账号ID
			    'mch_id'     => $mch_id, // 商户号
			    'nonce_str'    => $str, // 随机字符串
			    'sign'    => $sign, // 签名
			    'body'    => $res['goods_name'], // 商品描述
			    'out_trade_no' => $res['order_no'], // 商户订单号
			    'total_fee' => $res['price'] * 100, // 总金额 单位（分）
			    'spbill_create_ip' => $ip, // 终端IP
			    'notify_url' => $notify_url, // 通知地址
			    'trade_type' => 'MWEB', // 交易类型
			    'scene_info' => '{"h5_info": {"type":"Wap","wap_url": "http://www.voofan.com","wap_name": "唯凡优品"}}', // 场景信息
			];

			// dump($wechat);
			// exit;

			$data = $this -> toXml($wechat);

			$result = $this -> curl('https://api.mch.weixin.qq.com/pay/unifiedorder','POST',$data);

			$res = $this -> fromXml($result);
			
			if ($res['return_code'] == 'SUCCESS') {
				$redirect_url = urlencode('http://www.voofan.com/#/allOrder?type=待发货');
				$url = $res['mweb_url'].'&redirect_url='.$redirect_url;
				return ajax(200,$res['mweb_url']);
				// echo "<script>window.location.href='".$res['mweb_url']."'</script>";
				// exit;
			}
			else {
				return json('参数不全');
			}
		}

		// 微信提现
		public function tx() {
			$tx = new Tx();
			$tx -> sendMoney(1,'oRrdQt9YxqoXVIntnHv9b6QGfv1U');
			exit();
			$id = input('oid'); // 订单ID

			$res = Db::name('order') -> where('id',$id) -> find();

			$appid = 'wxa1f004b280729c17';

			$mch_id = '1515790821';

			$order_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

			$str = strtoupper(md5($this->createNonceStr()));

			$ip = $this -> get_client_ip();

			$sign = "amount=1&check_name=FORCE_CHECK&desc=提现&mch_appid=".$appid."&mchid=".$mch_id."&nonce_str=".$str."&openid=oRrdQt9YxqoXVIntnHv9b6QGfv1U&partner_trade_no=".$order_no."&spbill_create_ip=".$ip."&key=06a834fca809160f545630c7121f959c";

			$sign = strtoupper(md5($sign));

			$wechat = [
			    'mch_appid'     => $appid, // 公众账号ID
			    'mchid'     => $mch_id, // 商户号
			    'nonce_str'    => $str, // 随机字符串
			    'sign'    => $sign, // 签名
			    'openid'    => 'oRrdQt9YxqoXVIntnHv9b6QGfv1U', // 用户openid
			    'check_name'    => 'NO_CHECK', // NO_CHECK：不校验真实姓名;FORCE_CHECK：强校验真实姓名
			    'partner_trade_no' => $order_no, // 商户订单号
			    'amount' => 1, // 总金额 单位（分）
			    'spbill_create_ip' => $ip, // 终端IP
			    'desc' => '提现', // 备注
			];

			// dump($wechat);
			// exit;

			$data = $this -> toXml($wechat);

			$ch = curl_init();                                      //初始化curl
			curl_setopt($ch, CURLOPT_URL,'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers');                 //抓取指定网页
			curl_setopt($ch, CURLOPT_HEADER, 0);                    //设置header
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
			curl_setopt($ch, CURLOPT_POST, 1);                      //post提交方式
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);           // 增加 HTTP Header（头）里的字段 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch,CURLOPT_SSLCERT,'C:\phpStudy\WWW\msg\public\cert\apiclient_cert.pem'); //这个是证书的位置绝对路径
			curl_setopt($ch,CURLOPT_SSLKEY,'C:\phpStudy\WWW\msg\public\cert\apiclient_key.pem'); //这个也是证书的位置绝对路径
			$result = curl_exec($ch);                                 //运行curl
			curl_close($ch);

	        dump($result);
	        exit;
			// $result = $this -> curl('https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers','POST',$data);

			$res = $this -> fromXml($result);
			
			if ($res['return_code'] == 'SUCCESS') {
				dump($res);
			}
			else {
				return json('参数不全');
			}
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

	    /**
	     * 生成随机字符串
	     * @param int $length
	     * @return string
	     */
	    protected function createNonceStr($length = 6)
	    {
	        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	        $str = '';
	        for ($i = 0; $i < $length; $i++) {
	            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	        }
	        return $str;
	    }

	    /**
	     * 转为XML数据
	     * @param array $data 源数据
	     * @return string
	     */
	    protected function toXml($data)
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

	    /**
	     * 解析XML数据
	     * @param string $xml 源数据
	     * @return mixed
	     */
	    protected function fromXml($xml)
	    {
	        if (!$xml) {
	            throw new InvalidArgumentException('convert to array error !invalid xml');
	        }
	        libxml_disable_entity_loader(true);
	        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA), JSON_UNESCAPED_UNICODE), true);
	    }

	    /**
	       * CURL请求
	       * @param $url 请求url地址
	       * @param $method 请求方法 get post
	       * @param null $postfields post数据数组
	       * @param array $headers 请求header信息
	       * @param bool|false $debug 调试开启 默认false
	       * @return mixed
	       */
	    function curl($url, $method, $postfields = null, $headers = array(), $debug = false) {
	        $method = strtoupper($method);
	        $ci = curl_init();
	        /* Curl settings */
	        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	        curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
	        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
	        curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
	        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
	        switch ($method) {
	          case "POST":
	            curl_setopt($ci, CURLOPT_POST, true);
	            if (!empty($postfields)) {
	              $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
	              curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
	            }
	            break;
	          default:
	            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
	            break;
	        }
	        $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
	        curl_setopt($ci, CURLOPT_URL, $url);
	        if($ssl){
	          curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
	          curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
	        }
	        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
	        curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
	        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
	        $response = curl_exec($ci);
	        $requestinfo = curl_getinfo($ci);
	        if ($debug) {
	          echo "=====post data======\r\n";
	          var_dump($postfields);
	          echo "=====info===== \r\n";
	          print_r($requestinfo);
	          echo "=====response=====\r\n";
	          print_r($response);
	        }
	        curl_close($ci);
	        return $response;
	    }

	    /************** wyd *******************/

        function createNoncestr2($length =32)
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

        function curl2($param="",$url) {

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
            curl_setopt($ch,CURLOPT_SSLCERT,ROOT_PATH.'public\cert\apiclient_cert.pem'); //这个是证书的位置绝对路径
            curl_setopt($ch,CURLOPT_SSLKEY,ROOT_PATH.'public\cert\apiclient_key.pem'); //这个也是证书的位置绝对路径
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
        function get_client_ip2($type = 0,$adv=false) {
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







	    /**
	     *
	     * 获取支付结果通知数据
	     * return array
	     */
	    public function notify(){

//            //判断商户是微信账号还是手机  wyd 直接到账
//        $user_openid = Db::name('user') -> where('id',49) -> value('openid');
//        if($user_openid){
//            $this->sendMoney(16,$user_openid);
//        }else{
//            $alipayInfo = Db::name('uinfo') -> where('uid',49) -> find();
//            if(isset($alipayInfo['true_name']) && isset($alipayInfo['alipay'])){
//                $this->alipay($alipayInfo['alipay'],16,$alipayInfo['true_name']);
//            }
//        }
//        echo 3;die;
	        //获取通知的数据
	         //$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
	        $xml=file_get_contents('php://input', 'r');
	        $data = array();
	        if( empty($xml) ){
	            return false;
	        }
	        $data = $this->fromXml( $xml );
	        if( !empty($data['return_code']) ){
	            if( $data['return_code'] == 'FAIL' ){
	                return false;
	            }
	        }
	        if ($data['result_code'] = 'SUCCESS') {
	    		$file  = 'wxlog.txt';//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
	    		$content = $data['total_fee'] / 100;
	    		file_put_contents($file, $content.PHP_EOL,FILE_APPEND);
	        	// $res = Db::name('order') -> where('order_no',$data['out_trade_no']) -> update(['type' => 1]);



	    		// 返积分
	    		$res = Db::name('order') -> where('order_no',$data['out_trade_no']) -> find();

	    		if ($res['dpid'] != 0) {
	    			$phone = Db::name('category') -> where('id',$res['dpid']) -> value('phone');

	    			$duid = Db::name('category') -> where('id',$res['dpid']) -> value('cid');

                    Db::name('order') -> where('id',$res['id']) -> update(['type' => 1,'paytype' => 2,'paymoney' => $content,'paytime'=>time()]);

//	    			$send = send_sms($phone, 1, ['zh' => $res['tablenum'],'money' => $res['allp'],'all' => $res['price'],'name' => $res['goods_name']]);
                    $goods = explode(',',$res['goods_name']);
                    $nums = explode(',',$res['allnum']);
                    $info = '';
                    foreach ($goods as $key=>$good){
                        $info .= $good.' '.$nums[$key].'份 ';
                    }
	    			$send = send_sms($phone, 3, ['table' => $res['tablenum'],'info' => $info]);


                    //判断是否是第一次购买餐品
                    $foodOrder = Db::name('order') -> where('uid',$res['uid'])->where('paytime','gt',0)->where('dpid','gt',0) -> count();
                    if(isset($foodOrder) &&  $foodOrder <= 1){
                        $insert = [
                            'uid'=>$res['uid'],
                            'desc'=>'首餐奖励',
                            'money'=>$res['price'],
                            'month'=>12,
                            'type'=>0,
                            'time'=>time()
                        ];
                        Db::name('lcm') -> insert($insert);
                    }



	    			Db::name('record') -> insert([
	    				'uid' => $res['uid'],
	    				'money' => $res['price'],
	    				'cont' => '赠送积分',
	    				'type' => 1,
	    				'status' => 1,
	    				'time' => time(),
	    			]);

	    			Db::name('balance') -> where('uid',$res['uid']) -> setInc('jf',$res['price']);

//					Db::name('record') -> insert([
//						'uid' => $res['uid'],
//						'money' => $res['price'],
//						'cont' => '顾客购买商品',
//						'type' => 1,
//						'status' => 0,
//						'time' => time(),
//					]);



                    //获取手续费
//                    $fee = Db::name('tbl') -> where('id',1) -> value('bl');
                    //从商户列表里获取佣金比例
                    $fee = Db::name('category') -> where('id',$res['dpid']) -> value('commission');
                    if(!$fee){
                        $fee = 0;
                    }
//                    Db::name('balance') -> where('uid',$duid) -> setInc('balance',$res['price'] * (1-$fee));

                    //判断商户是微信账号还是手机  wyd 直接到账
                    $user_openid = Db::name('user') -> where('id',$duid) -> value('openid');

                    //判断是否打过款
                    $tx = Db::name('tx')->where('order_id',$res['id'])->find();
                    if(!$tx) {

                        if ($user_openid) {
                            //file_put_contents("wxtx.txt",'提现金额：'.$res['price'] * (1-$fee/100).PHP_EOL,FILE_APPEND);
                            $return = $this->sendMoney($res['price'] * (1 - $fee / 100), $user_openid);
                            if ($return) {
                                Db::name('tx')->insert(['uid' => $duid, 'order_id' => $res['id'], 'money' => $res['price'] * (1 - $fee / 100), 'way' => 2, 'type' => 1, 'time' => time(), 'remark' => '餐厅下单微信自动提现']);
                            }
                        } else {
                            $alipayInfo = Db::name('uinfo')->where('uid', $duid)->find();
                            if (isset($alipayInfo['true_name']) && isset($alipayInfo['alipay'])) {
                                $return = $this->alipay($alipayInfo['alipay'], $res['price'] * (1 - $fee / 100), $alipayInfo['true_name']);
                                if ($return) {
                                    Db::name('tx')->insert(['uid' => $duid, 'order_id' => $res['id'], 'money' => $res['price'] * (1 - $fee / 100), 'way' => 1, 'type' => 1, 'time' => time(), 'remark' => '餐厅下单支付宝自动提现']);
                                }
                            }
                        }
                    }

                    //手续费
                    $fees = $res['price'] * $fee/100;

                    Db::name('record') -> insert([
                        'uid' => $duid,
                        'sid' => $duid,
//					'money' => $res['price'],
                        'money' => $res['price'] * (1-$fee/100),
                        'cont' => '顾客购买商品（扣除手续费：'.$fees.'元）',
                        'type' => 1,
                        'status' => 0,
                        'time' => time(),
                    ]);


                    //判断是否是第一次购买餐品
                    $foodOrder = Db::name('order')
                        -> where('uid',$res['uid'])
                        ->where('paytime','gt',0)
                        ->where('dpid','gt',0) -> count();
                    if(isset($foodOrder) &&  $foodOrder <= 1){

                        $first_save_money = Db::name('category') -> where('id',$res['dpid']) -> value('first_save_money');
                        $first_fee = $first_save_money/100;
                        $insert = [
                            'uid'=>$res['uid'],
                            'desc'=>'首餐奖励',
                            'money'=>$res['price'] * $first_fee,
                            'month'=>12,
                            'type'=>0,
                            'time'=>time()
                        ];
                        Db::name('lcm') -> insert($insert);
                    }


                    $machine_code = Db::name('category') -> where('id',$res['dpid']) -> value('machine_code');
                    $print_times = Db::name('category') -> where('id',$res['dpid']) -> value('print_times');

                    //调用打印订单
                    if($machine_code){
                        for($i=0;$i<$print_times;$i++) {
                            $return = $this->printOrder($machine_code, $res['id']);
                        }
                    }

//	    			Db::name('balance') -> where('uid',$duid) -> setInc('balance',$res['price']);
	    		}
	    		else {

                    $rebate = new Rebate();

                    // $rebate -> rebate($data['out_trade_no']);
                    $rebate -> order($data['out_trade_no'],2,$content);  //防止二次提交订单

	    			$jf = Db::name('product') -> where('id',$res['pid']) -> value('jf');

		    		Db::name('record') -> insert([
		    			'uid' => $res['uid'],
		    			'money' => $jf,
		    			'cont' => '赠送积分',
		    			'type' => 1,
		    			'status' => 1,
		    			'time' => time(),
		    		]);

	    			Db::name('balance') -> where('uid',$res['uid']) -> setInc('jf',$jf);
	    		}


                //计算上级收益 wyd
                $point = Db::name('setting') -> where('key','pay_reward') -> value('value');
                $parentid = Db::name('user') -> where('id',$res['uid']) -> value('pid');
                if($point && isset($parentid)) {

                    //获取上级用户id
                    $adds = $res['price'] * ($point/100);
                    $add = sprintf("%.2f",$adds);
                    //更新余额
                    Db::name('balance')->where('uid',$parentid)->setInc('balance',$add);
                    //写入记录
                    Db::name('record') -> insert([
                        'uid' => $parentid,
                        'sid' => 0,
                        'money' => $add,
                        'cont' => '下级消费获得分成'.$add.'元',
                        'type' => 1,
                        'status' => 0,
                        'time' => time(),
                    ]);
                }

    			echo "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";

	   //  		if ($res !== false) {
				// 	echo "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
				// }
	    	}

	       // return $data;
            exit();
	    }

        public function testPrint()
        {
            $this->printOrder('4004668693',1596);
        }

        /**
         * 易联云打印机
         */
        public function printOrder($machine_code = '',$order_id)
        {
            if(!$machine_code){
                return true;
            }
            //4004668693     354584192661
            $config = new YlyConfig('1033305721', 'd94f92d0d94eb40a5b9ec379cdcf742c');
//            $client = new YlyOauthClient($config);
//            $token = $client->getToken();   //若是开放型应用请传授权码code

            //调取文本打印
//            $print = new PrintService('d5692fdd6e0a45bca65b69e246003a5f', $config);
//            $data = $print->index('4004668693','测试打印','1231223');
//            var_dump($data);



//            $client = new YlyOauthClient($config);
//            try {
//                $token = $client->getToken();
//            } catch (Exception $e) {
//                echo $e->getMessage() . "\n";
//                print_r(json_decode($e->getMessage(), true));
//                return;
//            }


            //查询订单信息
            $res = Db::name('order') -> where('id',$order_id) -> find();
            $shop_name = Db::name('category') -> where('id',$res['dpid']) -> value('name');

            $access_token = '57a057869efd4290b634246a06d59180';           //调用API凭证AccessToken 永久有效,请妥善保存.
//            $access_token = $token->access_token;           //调用API凭证AccessToken 永久有效,请妥善保存.
//            $refresh_token = $token->refresh_token;         //刷新AccessToken凭证 失效时间35天
//            $expires_in = $token->expires_in;               //自有型应用可忽略此回调参数, AccessToken失效时间30天
//            $machine_code = '4004668693';                             //机器码
            $origin_id = $res['order_no'];                                //内部订单号(32位以内)

            if (empty($machine_code)) {echo 'The machine_code cannot be empty';return;}

            if (empty($origin_id)) {echo 'The origin_id cannot be empty';return;}


            /**文本接口开始**/
            $print = new PrintService($access_token, $config);
//            $content = "<FS2><center>**#1 融创**</center></FS2>";
            $content = str_repeat('.', 32);
            $content .= "<FS2><center>--在线支付--</center></FS2>";
            $content .= "<FS><center>".$shop_name."</center></FS>";
            $content .= "订单时间:". date("Y-m-d H:i") . "\n";
            $content .= "订单编号:".$res['order_no']."\n";
            $content .= str_repeat('*', 14) . "商品" . str_repeat("*", 14);
            $content .= "<table>";

            $goods = explode(',',$res['goods_name']);
            $nums = explode(',',$res['allnum']);
            $prices = explode(',',$res['allp']);
            $total_price = 0;
            foreach ($goods as $key=>$good){
//                $info .= $good.' '.$nums[$key].'份 ';
                $content .= "<tr><td>".$good."</td><td>x".$nums[$key]."</td><td>".$prices[$key]."</td></tr>";
                $total_price += $nums[$key] * $prices[$key];
            }
            $you = isset($res['discount_money'])?$res['discount_money']:'0.00';
//            $content .= "<tr><td>肉包子</td><td>x3</td><td>5.96</td></tr>";
            $content .= "</table>";
            $content .= str_repeat('.', 32);
//            $content .= "<QR>菜单详情</QR>";
            $content .= "桌号:".$res['tablenum']."\n";
            $content .= "小计:￥".$res['paymoney']."\n";
            $content .= "折扣:￥".$you." \n";
            $content .= str_repeat('*', 32);
            $content .= "订单总价:￥ ".$total_price."\n";
            $content .= "备注:".$res['remark']."\n";
            $content .= "<FS2><center>**#1 完**</center></FS2>";

            try{
//                var_dump($print->index($machine_code, $content, $origin_id));
                $return =$print->index($machine_code, $content, $origin_id);
                return true;

            }catch (Exception $e) {
//                echo $e->getMessage();
                return false;
            }
            /**文本接口结束**/

        }


        // 支付宝
        public function alipay($account,$amount,$username) {
            $order_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

//            $res = $this -> userWithDraw($order_no,'863758424@qq.com',0.1,'吴亚丁');
            $res = $this -> userWithDraw($order_no,$account,$amount,$username);

//            if ($res == 1) {
//                Db::name('tx') -> insert(['uid'=>36,'money'=>1,'way'=>1,'type' => 1,'time' => time()]);
//
//                return 1;
//            }
            return true;
        }


        /**
         * @User 一秋
         * @param $id  提现数据id
         * @param $out_biz_no 编号
         * @param $payee_account 提现的支付宝账号
         * @param $amount 转账金额
         * @param $payee_real_name 账号的真实姓名
         * @return bool|Exception
         */
        public static function userWithDraw(
            $out_biz_no,$payee_account,$amount,$payee_real_name)
        {
            $payer_show_name = '用户餐厅付款';
            $remark = '提现到支付宝';
            $aop = new \AopClient();
            $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';//支付宝网关 https://openapi.alipay.com/gateway.do这个是不变的
            $aop->appId = '2018121262533273';//商户appid 在支付宝控制台找
            $aop->rsaPrivateKey = 'MIIEpAIBAAKCAQEAtVH8WFbXPn934w+OT/0MghUr0q8AgOo8jq31i2pNCjtL2UAblhj8Hylj8HtnFdAmFBIr1JLF8CYDJFKFFANGlcC+pjfLteHKqDgvuhYraGDWnkc91ovtiLe7C4HGuV9KKG8Q+EXCNeF34fRzcP+OfMa10b1ZvC7v5W57L4K/CeS5sFSaswx/wgcI6HMZMNu4+udcZdRE8YUOFYuEvi5TtGbZW97YpsbWWU412tdhmQhYGJv/+AbuqwbOmpzNkana5H7YfPhZIAMTZXVDanG0hD44Cs9nk1ueTL17hUrlwP5GVeNh37wzUvLDbpwmno98F4YAbVxknWh6/MiC56qYlQIDAQABAoIBADncyfye6d2F7ApHwpCAHme8vdlkA3MgTObzWLJ+38ruFUxlCuihXIPNP5lUqBbr79rj2Zziocv7NXF37APw76uGcNC2aYnHXlbEZ2apAH00ugiUj4VR7iF9EHGhnns1j4LLvsyb5lgvdYRj8BsVUrE68HbYSDSnfeLnmOQWg0RycdJT0V7C3cbSnFMMKPj0jiCUpTYyv2wjXfXPcd/YC/0JQ5fWZgkgbp1gXCOJ4FVqnjSogzYK6TxzYL1XfXcZ7TdJtZL6XcQPppRaW2YN2gXgFO+iW8JM9hKokilE+08WZDXCvrjv3zqt3x/MhDkMfBLcRzu2ooqVmH8/Zg8eWlkCgYEA2jp7k6LcfHxD62mADgIiqX4jGAVmrJkTb1canayOkiGIF6HqczJi0vp7I7MsWPCzyMq19afJrqwR3LepSc29uOFsUcmjH8qojkc6ETJr6n0oNzB+2kML0wJZccVH7oRWRJvuKAzqV8TSkA2seJV0S4Hp5iTNaqUXI9p8dK+TbYcCgYEA1LQh4yqsXujTpoV63XynAS0rNo8W/DfM+5YzgfIX3O4xt0JpvH28nSOkOEYACL1ZF+sVabwBCPhQnMfyFb1R5blyMCzuimjyGYwweffd1buodueXlFTuCTuSXviZIF1RYFgrFCBYLxpQ8fgJ5iCZeX9aH+oJxWTXXJuBPLohMAMCgYA6WHyFwntUxRybqj9Uf3mo+1KCr5798odhs1YoZeRQaaqrNt0qI4SMh++4YcUvmltXCeuQXGXtz4+PBYO7eciJz6tYSqLUcyiRsbVyt5pmvUOtTcf8rwRQTEaBxo8u1C8EdPQ9vQZCpRru7Nj5/Gt1bKyOnbRWGQGQ0m+H+xFdrQKBgQCRtymz94sIphZA8Raf34KFks0U3mgPt6aNAHQe5JwmdWPHuQ6426a4PC+HgaUKV7VOZnVP70UCQSLwlSWqcP1HFIk+qbltRsH8cIbRN9ZYUuvxMW0taBV+a7o1p7c3bdaO2zf/3Lys4Clt6s/VQKYDjxRN4sZ43UGQptOdp6GOwwKBgQCn/fhzURTB2xB+IG6gq8/1xThM4YHYybaBuoq7NGVjjeMFJkrSzWKq2Ajr2Dw7TM/4sZDWF3oAaQhdlsCCyqOgfD+6Zni5Pl1RMFO7RYo90sGM6OXCZk3iElUnLMrhqIOFjEK02qQrkpje/iiPTrcqpi5W9FVzG9eu+WPGKsUQ8Q==';//私钥 工具生成的
            $aop->alipayrsaPublicKey= 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiAHl9ZAQ1SyNRq1ECkTR5p0VHbafLy81v5CTkX+DFFvB4qgY0tZDpNolnKcKyKsr9u0nCqAZYC+SCR7hfMHP+aPar4kiYTkOOuG9LkobnuVzm3DskyARs524HAPxtupjjI/1VTcXYbjyDConB7b6nATrDcYYrcFGB2A7FEBaVT/bOIe1+fGYUvGfhUv4q0sIAMPICbbT3NWKaQ0jj2tLJjcU9AtyhikHKzpKXcDT/BzS+9QBZB3gUALPbLdyDQGiiVeRdvBkGyM1SBIrT/YyH5QJ53PDzogWXKuWhF9NpIKOcNjaq+0DNhMJA16ghqR2ULn5ZD6YZYNssbfHmfU97QIDAQAB';//支付宝公钥 上传应用公钥后 支付宝生成的支付宝公钥
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

            if(!empty($resultCode)&&$resultCode == 10000){
                //提现成功以后 更新表状态
                //并且记录 流水等等
//                Db::name('tx') -> where('id',$id) -> update(['type' => 1,'time' => time()]);
                file_put_contents("txlog.txt",'支付宝账号：'.$payee_account.','.'真实姓名：'.$payee_real_name.','.'转账金额：'.$amount.PHP_EOL,FILE_APPEND);
            }
            return true;
        }


        /** 微信提现测试
         * @param $total_amount
         * @param $re_openid
         * @param string $desc
         * @param string $check_name
         */
        public function wx_text(){
            $a = $this->sendMoney(5,'o8m6C53TfCFikq6yTub2gIrDDjBM');
            var_dump($a);
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
                'partner_trade_no'=> date('YmdHis').rand(1000, 9999),//商户订单号
                'openid'=> $re_openid,//用户openid
                'check_name'=>'NO_CHECK',//校验用户姓名选项,
                're_user_name'=> $check_name,//收款用户姓名
                'amount'=>$total_amount,//金额
                'desc'=> $desc,//企业付款描述信息
                'spbill_create_ip'=> $this->get_client_ip2(),//Ip地址
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

            $res = $this -> curl2($xml,$url);

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

	}
?>