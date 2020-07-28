<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**编码转换
 *
 *
 * @param $value
 *
 * @author yzm
 */
function str_iconv (&$value)
{
    if ( !(is_numeric($value) || is_float($value)) ) {
        $value = (string)"\t" . $value;

        mb_convert_encoding($value, 'GBK');
    }
}


/**
 * 得到微妙.
 *
 * @return float
 *
 * @author yzm
 */
function microtime_float ()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/**
 *  获取客户端ip
 *
 * @param int $type
 * @return mixed
 */
function getclientip ($type = 0)
{
    $type = $type ? 1 : 0;
    static $ip = null;
    if ( $ip !== null ) return $ip[$type];
    if ( @$_SERVER['HTTP_X_REAL_IP'] ) {//nginx 代理模式下，获取客户端真实IP
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    } else if ( isset($_SERVER['HTTP_CLIENT_IP']) ) {//客户端的ip
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {//浏览当前页面的用户计算机的网关
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if ( false !== $pos ) unset($arr[$pos]);
        $ip = trim($arr[0]);
    } else if ( isset($_SERVER['REMOTE_ADDR']) ) {
        $ip = $_SERVER['REMOTE_ADDR'];//浏览当前页面的用户计算机的ip地址
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? [
        $ip,
        $long
    ] : [
        '0.0.0.0',
        0
    ];
    return $ip[$type];
}

/**
 * 发送get请求.
 *
 * @param $url
 * @param $timeout
 * @param array $data
 *
 * @author yzm.
 *
 * @return bool|mixed|null|string
 */
function http_get ($url, $timeout = 10, $data = [])
{
    $rst = null;
    if ( !empty($data) ) {
        $data = is_array($data) ? toUrlParams($data) : $data;
        $url .= (strpos($url, '?') === false ? '?' : '&') . $data;
    }
//    if (function_exists('file_get_contents') && !is_null($timeout)) {
//        $rst = file_get_contents($url);
//        debug('rst'.$rst);
//    } else {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    // https请求 不验证证书和hosts
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// 这个是重点。
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);


    $rst = curl_exec($ch);
    curl_close($ch);
//    }

    return $rst;
}

/**
 * 执行一个 HTTP 请求
 *
 * @param string $Url 执行请求的Url
 * @param mixed $Params 表单参数
 * @param string $Method 请求方法 post / get
 * @return array 结果数组
 */
function sendRequest ($Url, $Params, $Method = 'post')
{

    $Curl = curl_init();//初始化curl

    if ( 'get' == $Method ) {//以GET方式发送请求
        curl_setopt($Curl, CURLOPT_URL, "$Url?$Params");
    } else {//以POST方式发送请求
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($Curl, CURLOPT_POSTFIELDS, $Params);//设置传送的参数
    }

    curl_setopt($Curl, CURLOPT_HEADER, false);//设置header
    curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
    //curl_setopt($Curl, CURLOPT_CONNECTTIMEOUT, 3);//设置等待时间

    $Res = curl_exec($Curl);//运行curl

    curl_close($Curl);//关闭curl

    return $Res;
}

/**
 * 格式化字节大小
 * @param  number $size 字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes ($size, $delimiter = '')
{
    $units = [
        'B',
        'KB',
        'MB',
        'GB',
        'TB',
        'PB'
    ];
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}


/**
 * 发送post请求.
 *
 * @param $url 地址
 * @param $args 参数
 * @param $timeout 过期时间 秒
 *
 * @author yzm
 *
 * @return mixed
 */
function http_post ($url, $args, $timeout = 30)
{
    $_header = [//       'Content-Type: application/json; charset=utf-8',
                //        'Content-Length: ' . strlen($args)
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $_header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    $ret = curl_exec($ch);
    curl_close($ch);

    return $ret;
}

function dbCreateIn ($params, $is_str = false)
{
    if ( !$params ) {
        return 0;
    }
    $rst = is_string($params) ? "{$params}" : $params;
    if ( is_array($params) ) {
        $params = array_filter(array_unique($params));
        $rst = '';
        foreach ($params as $val) {

            $rst .= (is_numeric($val) && !$is_str ? $val : "'{$val}'") . ',';
        }
        $rst = trim($rst, ',');
    }
    return $rst;
}

/**
 * 随机生成编码.
 *
 * @author
 *
 * @param $len 长度.
 * @param int $type 1:数字 2:字母 3:混淆
 * @return string
 */
function rand_code ($len, $type = 1)
{
    $output = '';
    $str = [
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'q',
        'r',
        's',
        't',
        'u',
        'v',
        'w',
        'x',
        'y',
        'z',
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z'
    ];
    $num = [
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9'
    ];

    switch ($type) {
        case 1:
            $chars = $num;
            break;
        case 2:
            $chars = $str;
            break;
        default:
            $chars = array_merge($str, $num);
    }

    $chars_len = count($chars) - 1;
    shuffle($chars);

    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $chars_len)];
    }

    return $output;
}

/**
 * 打印数组.
 *
 * @param $arr
 */
function p ($arr)
{
    //header('content-type:text/html;charset=utf8');
    echo '<pre>' . print_r($arr, true);
}

/**
 * 多位数组排序.
 *
 * @param $arr
 * @param $key
 * @param int $sort_order
 * @param int $sort_type
 *
 * @return array 排好的数组
 */
function arrayMultiSort ($arr, $key, $sort_order = SORT_DESC, $sort_type = SORT_NUMERIC)
{
    if ( is_array($arr) ) {
        foreach ($arr as $array) {
            if ( is_array($array) ) {
                $key_arrays[] = $array[$key];
            }
        }
        array_multisort($key_arrays, $sort_order, $sort_type, $arr);
    }
    return $arr;
}

function arrayColumnHasVal ($ary, $k)
{
    $a = [];
    foreach ($ary as $row) {
        if ( !empty($row[$k]) ) $a[] = $row[$k];
    }
    return $a;
}


function arrayColumnReindex ($ary, $k = 0)
{
    $a = [];
    foreach ($ary as $row) {
        $row = (array)$row;
        if ( $k ) {
            $a[$row[$k]] = $row;
        } else {
            $a[] = $row;
        }
    }
    return $a;
}

/**
 * 与客户端调试打印调试信息.
 *
 * @param $data
 * @param bool|false $op_file
 */
function debugLog ($data, $op_file = true, $filename = 'debug')
{
    $data = is_array($data) ? var_export($data, true) : $data;
    if ( $op_file ) {
        file_put_contents(/*\Yii::$app->basePath .*/ "./../logs/{$filename}.txt", date('Y/m/d H:i:s', time()) . " \t输出结果:" . $data . "\r\n\r\n", FILE_APPEND);
    } else {
        $data = [
            'error' => 100,
            'msg'   => $data
        ];
        // 返回JSON数据格式到客户端 包含状态信息
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
    }
}

/**
 * 二维数据计算和.
 *
 * @author lyc
 *
 * @param $arr
 * @param $key
 * @return int
 */
function arrayMultiSum ($arr, $key)
{
    $sum = 0;
    if ( is_array($arr) ) {
        foreach ($arr as $array) {
            $sum += $array[$key];
        }
    }
    return $sum;
}


/**
 * 下载远程文件.
 *
 * @param $url
 * @param $path
 *
 * @author yzm
 *
 * @return bool true false
 */
function download ($url, $path = null)
{
    $file = http_get($url);

    if ( empty($path) ) return $file;

    $basedir = dirname($path);
    if ( !is_dir($basedir) ) mkdir($basedir);

    // 直接写入文件
    file_put_contents($path, $file);

    return file_exists($path);
}


/**
 * 获取文件大小,以kb为单位.
 *
 * @author yzm
 *
 * @param $path 文件路径
 * @return float
 */
function getFilesize ($path)
{
    return ceil(filesize($path));
}

/**
 * 获取图片信息.
 *
 * @author lyc
 *
 * @param $img 图片地址
 * @return array
 */
function getImageInfo ($img)
{
    $img_info = getimagesize($img);
    return [
        $img_info[0],
        $img_info[1],
        getFilesize($img) / 1000
    ];
}


/**
 * 导入Excel.
 *
 * @author lyc
 *
 * @param $fileName
 * @param string $encode
 * @return array
 * @throws Exception
 * @throws PHPExcel_Exception
 */
function importExcel ($fileName, $encode = 'utf-8')
{
    $excelData = [];

    if ( !file_exists($fileName) ) {
        return $excelData;
    }

    header("Content-type:text/html;charset={$encode}");
    $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($fileName);

    $objWorksheet = $objPHPExcel->getActiveSheet();
    $highestRow = $objWorksheet->getHighestRow();
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

    for ($row = 2; $row <= $highestRow; $row++) {
        for ($col = 0; $col < $highestColumnIndex; $col++) {
            $excelData[$row][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
        }
    }

    return $excelData;
}

/**
 * 导出excel.
 *
 * @author lyc
 *
 * @param  [type] $fileName   [文件名]
 * @param  [type] $arr_field  [excel 的title字段]
 * @param  [type] $arr_list   [ 导出的数组数据]
 * @param  [type] $k_time   [ 要格式化转换时间的字段]
 * @param  [type] $array_keys [要导出数组的 键名  keys]
 * @param  [type] $model array[列=>宽度]
 * @param  [type] $title sheet名称
 * @param  [type] $statistics 统计头数组 array(array（"A1数据","B1数据"...）...)
 * @param  [type] $list_title_index 列表头的行数
 * @param  [type] $style=array("A1"=>array("align"=>"center,left,right","weight"=>'bold'),"height"=array("3"=>"25"..))
 */
function exportExcel ($fileName, $arr_field, $arr_list, $array_keys, $k_time = 'createtime', $model = [], $title = null, $statistics = [], $list_title_index = 1, $style = [])
{
    // 加载PHPExcel.php
    header('Content-type:text/html;charset=utf-8');
    if ( empty($arr_list) || !is_array($arr_list) ) {
        echo '<script>
                        alert("数据必须是数组，且不能为空！");
                        history.go("-1");
                    </script>';
        exit;
    }
    if ( empty($fileName) ) {
        exit('文件名不能为空');
    }
    // 设置文件名
    $date = date("Y_m_d", time());
    $fileName .= "_{$date}.xlsx";
    //新建
    $resultPHPExcel = new \PHPExcel();
    $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp; //保存在php://temp
    $cacheSettings = [' memoryCacheSize ' => '80MB'];
    \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    $countList = count($arr_list);
    $countField = count($arr_field);
    $abc = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z'
    ];
    //头部统计
    if ( !empty($statistics) ) {
        foreach ($statistics as $index => $value) {
            foreach ($value as $k => $v) {
                $resultPHPExcel->getActiveSheet()->setCellValue($abc[$k] . ($index + 1), $v);
            }
        }
    }
    // 设置文件title
    for ($i = 0; $i < $countField; $i++) {
        $resultPHPExcel->getActiveSheet()->setCellValue($abc[$i] . $list_title_index, $arr_field[$i]);
    }
    // 设置单元格内容
    for ($i = 0; $i < $countList; $i++) {
        for ($o = 0; $o < $countField; $o++) {
            if ( $array_keys[$o] == $k_time ) {
                $resultPHPExcel->getActiveSheet()->setCellValue($abc[$o] . ($i + $list_title_index + 1), date('Y-m-d H:i:s', $arr_list[$i][$array_keys[$o]]));
            } else {
                $resultPHPExcel->getActiveSheet()->setCellValue($abc[$o] . ($i + $list_title_index + 1), @$arr_list[$i][$array_keys[$o]]);
            }
        }
    }
    //设置sheet的title
    if ( !empty($title) ) {
        $resultPHPExcel->getActiveSheet()->setTitle($title);
    }
    //设置列宽度
    if ( count($model) > 0 ) {
        foreach ($model as $k => $v) {
            $resultPHPExcel->getActiveSheet()->getColumnDimension($k)->setWidth($v);
        }
    } else {
        for ($o = 0; $o < $countField; $o++) {
            $resultPHPExcel->getActiveSheet()->getColumnDimension($abc[$o])->setAutoSize(true);
        }
    }
    //设置样式
    if ( count($style) > 0 ) {
        foreach ($style as $k => $arr) {
            foreach ($arr as $key => $value) {
                //行的高度
                if ( $k == 'height' ) {
                    $resultPHPExcel->getActiveSheet()->getRowDimension($key)->setRowHeight($value);
                }
                //文字对齐方式
                if ( $key == 'align' ) {
                    if ( $value == 'center' ) {
                        $resultPHPExcel->getActiveSheet()->getStyle($k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//左右居中
                        $resultPHPExcel->getActiveSheet()->getStyle($k)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
                    }
                }
                //加粗
                if ( $key == 'weight' ) {
                    if ( $value == 'bold' ) {
                        $resultPHPExcel->getActiveSheet()->getStyle($k)->getFont()->setBold(true);
                    }
                }

            }
        }
    }
    //设置导出文件名
    $outputFileName = $fileName;
    $xlsWriter = new \PHPExcel_Writer_Excel2007($resultPHPExcel);
    $xlsWriter->setOffice2003Compatibility(true);
    ob_end_clean(); //清除缓冲区  避免乱码
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header('Content-Disposition:inline;filename="' . $outputFileName . '"');
    header("Content-Transfer-Encoding: binary");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Pragma: no-cache");
    $xlsWriter->save("php://output");
    exit;
}

/**
 * 用户fputcsv导出excel.
 *
 * @param $file_name 文件名称
 * @param $heads 头列表
 * @param $data 数据
 * @param string $title2
 * @param string $data2
 *
 * @author yzm
 */
function exportCsv ($file_name, $heads, $data = [], $title2 = [], $data2 = [])
{
    // 不限定时间
    set_time_limit(0);
    // 内存限定
    ini_set('memory_limit', '1024M');
    // 输出Excel文件头
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename = {$file_name}" . ".csv");
    header('Cache-Control: max-age=0');

    // 打开PHP文件句柄，php://output 表示直接输出到浏览器
    $fp = fopen('php://output', 'a');
    fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // 添加 BOM

    /*第一个数组*/
    // 输出Excel列名信息
    array_walk($heads, 'str_iconv');
    // 将数据通过fputcsv写到文件句柄
    fputcsv($fp, $heads);

    // 输出Excel内容
    foreach ($data as $one) {
        array_walk($one, 'str_iconv');
        fputcsv($fp, $one);
    }


    // 空格换行
    fputcsv($fp, ['']);
    fputcsv($fp, ['']);
    fputcsv($fp, ['']);

    /*第二个数组*/
    // 输出Excel列名信息
    array_walk($title2, 'str_iconv');

    // 将数据通过fputcsv写到文件句柄
    fputcsv($fp, $title2);

    // 输出Excel内容
    foreach ($data2 as $row) {
        array_walk($row, 'str_iconv');
        fputcsv($fp, $row);
    }

    fclose($fp);
    exit;
}


/**
 * 数组转换成xml.
 *
 * @author lyc
 *
 * @param $arr 数组
 *
 * @return string xml结果
 */
function arrayToXml ($arr)
{
    $xml = "<xml>";
    foreach ($arr as $key => $val) {
        if ( is_numeric($val) ) {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";

        } else
            $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
    }
    $xml .= "</xml>";
    return $xml;
}

/**
 * 将xml转为数组.
 *
 * @param $xml xml数据
 *
 * @return array|mixed|stdClass
 */
function xmlToArray ($xml)
{
    //将XML转为array
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
}


//获取汉字首字母
function getfirstchar ($s0)
{
    $fchar = ord($s0{0});
    if ( $fchar >= ord("A") and $fchar <= ord("z") ) return strtoupper($s0{0});
    $s1 = iconv("UTF-8", "gb2312", $s0);
    $s2 = iconv("gb2312", "UTF-8", $s1);
    if ( $s2 == $s0 ) {
        $s = $s1;
    } else {
        $s = $s0;
    }
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ( $asc >= -20319 and $asc <= -20284 ) return "A";
    if ( $asc >= -20283 and $asc <= -19776 ) return "B";
    if ( $asc >= -19775 and $asc <= -19219 ) return "C";
    if ( $asc >= -19218 and $asc <= -18711 ) return "D";
    if ( $asc >= -18710 and $asc <= -18527 ) return "E";
    if ( $asc >= -18526 and $asc <= -18240 ) return "F";
    if ( $asc >= -18239 and $asc <= -17923 ) return "G";
    if ( $asc >= -17922 and $asc <= -17418 ) return "I";
    if ( $asc >= -17417 and $asc <= -16475 ) return "J";
    if ( $asc >= -16474 and $asc <= -16213 ) return "K";
    if ( $asc >= -16212 and $asc <= -15641 ) return "L";
    if ( $asc >= -15640 and $asc <= -15166 ) return "M";
    if ( $asc >= -15165 and $asc <= -14923 ) return "N";
    if ( $asc >= -14922 and $asc <= -14915 ) return "O";
    if ( $asc >= -14914 and $asc <= -14631 ) return "P";
    if ( $asc >= -14630 and $asc <= -14150 ) return "Q";
    if ( $asc >= -14149 and $asc <= -14091 ) return "R";
    if ( $asc >= -14090 and $asc <= -13319 ) return "S";
    if ( $asc >= -13318 and $asc <= -12839 ) return "T";
    if ( $asc >= -12838 and $asc <= -12557 ) return "W";
    if ( $asc >= -12556 and $asc <= -11848 ) return "X";
    if ( $asc >= -11847 and $asc <= -11056 ) return "Y";
    if ( $asc >= -11055 and $asc <= -10247 ) return "Z";

    return null;
}

/**
 * 获取天的问候语.
 *
 * @author lyc
 *
 * @return string
 */
function getDayReeting ()
{
    // 以上海时区为标准
    date_default_timezone_set('Asia/Shanghai');

    $rst = '晚上好';
    $h = date("H");

    if ( $h < 11 ) {
        $rst = '早上好';
    } else if ( $h < 13 ) {
        $rst = '中午好';
    } else if ( $h < 17 ) {
        $rst = '下午好';
    }

    return $rst;
}


/**
 * 系统非常规MD5加密方法
 *
 * @param  string $str 要加密的字符串
 * @return string
 */
function userMd5 ($str, $auth_key = '')
{
    if ( !$auth_key ) {
        $auth_key = '' ?: '>=diMf;Sbduzn@!NBa~Hpl_@&IeG_w]O&ieZtiDffKTh]pK".doZ`wd,T$$:,Ka(';
    }
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}

/**
 * 生成随机字符串，不生成大写字母
 * @param $length
 * @return null|string
 */
function getRandChar ($length)
{
    $str = null;
    $strPol = "0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;

    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
    }

    return $str;
}


/**
 * 删除当前文件夹和文件
 * @param  [type]  $path   [description]
 * @param  boolean $delDir [description]
 * @return [type]          [description]
 */
function delDirAndFile ($path, $delDir = true)
{
    if ( $delDir && !is_dir($path) ) {
        return true;
    }

    $handle = opendir($path);
    if ( $handle ) {
        while (false !== ($item = readdir($handle))) {
            if ( $item != "." && $item != ".." ) is_dir("$path/$item") ? delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
        }
        closedir($handle);
        if ( $delDir ) return rmdir($path);
    } else {
        if ( file_exists($path) ) {
            return unlink($path);
        } else {
            return false;
        }
    }
}


/**
 * 获取图片数组最大的一张
 * @param  [type] $pngs [description]
 * @return [type]       [description]
 */
function maxpng ($pngs)
{
    $temp = 0;
    foreach ($pngs as $key => $value) {
        $png = intval(filesize($value));
        if ( $temp < $png ) {
            $temp = $png;
            $k = $key;
        }
    }
    return $pngs[$k];
}


/**
 * 递归移动文件及文件夹.
 *
 * @param [string] $source 源目录或源文件
 * @param [string] $target 目的目录或目的文件
 * @return boolean true
 */
function moveFile ($source, $target)
{
    // 如果源目录/文件不存在返回false
    if ( !file_exists($source) ) return false;

    // 如果要移动文件
    if ( filetype($source) == 'file' ) {
        $basedir = dirname($target);
        if ( !is_dir($basedir) ) mkdir($basedir); //目标目录不存在时给它创建目录
        copy($source, $target);
        unlink($source);

    } else { // 如果要移动目录

        if ( !file_exists($target) ) mkdir($target); //目标目录不存在时就创建

        $files = []; //存放文件
        $dirs = []; //存放目录
        $fh = opendir($source);

        if ( $fh != false ) {
            while ($row = readdir($fh)) {
                $src_file = $source . '/' . $row; //每个源文件
                if ( $row != '.' && $row != '..' ) {
                    if ( !is_dir($src_file) ) {
                        $files[] = $row;
                    } else {
                        $dirs[] = $row;
                    }
                }
            }
            closedir($fh);
        }

        foreach ($files as $v) {
            copy($source . '/' . $v, $target . '/' . $v);
            unlink($source . '/' . $v);
        }

        if ( count($dirs) ) {
            foreach ($dirs as $v) {
                moveFile($source . '/' . $v, $target . '/' . $v);
            }
        }
    }

    return true;
}

/**
 * 转换为url参数.
 *
 * @author yzm
 *
 * @param $params
 * @return string
 */
function toUrlParams ($params)
{
    $buff = "";

    if ( empty($params) ) return $buff;

    foreach ($params as $k => $v) {
        if ( !is_array($v) ) {
            $buff .= $k . "=" . urlencode($v) . "&";
        }
    }

    $buff = trim($buff, "&");

    return $buff;
}


/**
 * 读取socket数据.
 *
 * @author yzm
 *
 * @param $socket
 * @param bool|true $isDividePkg
 * @return array|null|string
 */
function socketRead ($socket, $isDividePkg = true)
{
    $rst = null;

    $buf = socket_read($socket, 8192);
    if ( $isDividePkg ) {
        $_buf = @json_decode($buf, true);
        $rst = !empty($_buf) ? [
            $_buf['error'],
            $_buf['msg'],
            @$_buf['content']
        ] : $buf;
    } else {
        $rst = $buf;
    }

    return $rst;
}

/**
 * Notes:是否手机
 * User: lyc
 * Date: 2019/1/11
 * Time: 10:20
 * @return bool
 */
function isMobile ()
{
    $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $useragent_commentsblock = preg_match('|/(.*?/)|', $useragent, $matches) > 0 ? $matches[0] : '';
    function CheckSubstrs ($substrs, $text)
    {
        foreach ($substrs as $substr) if ( false !== strpos($text, $substr) ) {
            return true;
        }
        return false;
    }

    $mobile_os_list = [
        'Google Wireless Transcoder',
        'Windows CE',
        'WindowsCE',
        'Symbian',
        'Android',
        'armv6l',
        'armv5',
        'Mobile',
        'CentOS',
        'mowser',
        'AvantGo',
        'Opera Mobi',
        'J2ME/MIDP',
        'Smartphone',
        'Go.Web',
        'Palm',
        'iPAQ'
    ];
    $mobile_token_list = [
        'Profile/MIDP',
        'Configuration/CLDC-',
        '160×160',
        '176×220',
        '240×240',
        '240×320',
        '320×240',
        'UP.Browser',
        'UP.Link',
        'SymbianOS',
        'PalmOS',
        'PocketPC',
        'SonyEricsson',
        'Nokia',
        'BlackBerry',
        'Vodafone',
        'BenQ',
        'Novarra-Vision',
        'Iris',
        'NetFront',
        'HTC_',
        'Xda_',
        'SAMSUNG-SGH',
        'Wapaka',
        'DoCoMo',
        'iPhone',
        'iPod'
    ];
    $found_mobile = CheckSubstrs($mobile_os_list, $useragent_commentsblock) || CheckSubstrs($mobile_token_list, $useragent);
    if ( $found_mobile ) {
        return true;
    } else {
        return false;
    }
}

/**
 * 是否是微信,如果是则返回微信版本.
 *
 * @author yzm
 *
 * @return bool
 */
function isWeiXin ()
{
    $rst = false;
    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if ( strpos($user_agent, 'MicroMessenger') !== false ) {
        // 获取版本号
        preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);
        $rst = @$matches[2];
    }

    return $rst;
}


/**
 * 获取省市基础信息
 *
 * 优先从淘宝获取,获取不到再从新浪获取.
 *
 * @author yzm
 *
 * @param $ip
 * @return array
 */
function getPCInfoByIp ($ip)
{
    $taobao = "http://ip.taobao.com/service/getIpInfo.php?ip={$ip}";
    $sina = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$ip}";

    $rest = @json_decode(http_get($taobao, 5), true);
    if ( $rest && !empty($rest['data']) ) {
        $rst = [
            'p'     => $rest['data']['region'],
            'pcode' => $rest['data']['region_id'],
            'c'     => $rest['data']['city'],
            'ccode' => $rest['data']['city_id'],
        ];
    } else {
        $rest = @json_decode(http_get($sina, 5), true);
        $rst = [
            'p'     => @$rest['province'],
            'pcode' => 0,
            'c'     => @$rest['city'],
            'ccode' => 0,
        ];
    }

    return $rst;
}


/**
 * 返回数据统计的百分比显示结果
 * @param $preNum    昨天的
 * @param $preTwoNum 前天的
 * @return string
 * 红色表示上升  stat-percent font-bold text-danger
 * 绿色表示下降  stat-percent font-bold text-info
 * 其他情况表示为灰色 stat-percent font-bold
 */
function getPercent ($preNum, $preTwoNum, $isReturn = 0)
{
    $htmlClass = 'stat-percent font-bold text-danger';
    $floatNum = 0;
    if ( !$preTwoNum || !$preNum || ($preTwoNum == $preNum) ) {
        $htmlClass = 'stat-percent font-bold';
    }

    if ( !empty($preTwoNum) && $preTwoNum != '0.00' ) {
        $floatNum = round((($preNum - $preTwoNum) / $preTwoNum), 2);
    }

    if ( $floatNum < 0 ) {
        $floatNum = abs($floatNum);
        $htmlClass = 'stat-percent font-bold text-info';
    }
    $floatNum = $floatNum * 100;
    if ( $isReturn ) {
        return $floatNum . '%';
    }
    return "<div class='$htmlClass'>" . $floatNum . '%' . '</div>';
}

/**
 * 生成订单号.
 *
 * @author yzm.
 *
 * @param $uid
 * @return string
 */
function makeOrderNo ($uid)
{
    return mt_rand(10, 99) . sprintf('%010d', time() - 946656000) . sprintf('%03d', (float)microtime() * 1000) . sprintf('%03d', (int)$uid % 1000);
}


/**
 * 格式化数量.
 *
 * @author yzm
 *
 * @param  number $count 个数
 * @param  string $delimiter 数字和单位分隔符
 *
 * @return string 格式化后的带单位的大小
 */
function formatCount ($count, $delimiter = '')
{
    if ( $count < 1000 ) return $count;

    $count = $count / 1000;
    $units = [
        '千+',
        '万+',
        '十万+',
        '百万+',
        '千万+'
    ];

    for ($i = 0; $count >= 10 && $i < 5; $i++) $count /= 10;

    return round($count, 2) . $delimiter . $units[$i];
}


/**
 * 友好的时间显示
 *
 * @author yzm
 *
 * @param int $sTime 待显示的时间
 * @param string $type 类型. normal | mohu | full | ymd | other
 * @param string $alt 已失效
 *
 * @return string
 */
function friendly_date ($sTime, $type = 'normal', $alt = 'false')
{
    if ( !$sTime ) return '';
    //sTime=源时间，cTime=当前时间，dTime=时间差

    $cTime = time();
    $dTime = $cTime - $sTime;
    $dDay = intval(date("z", $cTime)) - intval(date("z", $sTime));
    $dYear = intval(date("Y", $cTime)) - intval(date("Y", $sTime));

    //normal：n秒前，n分钟前，n小时前，日期
    switch ($type) {
        case 'normal':
            if ( $dTime < 60 ) {
                if ( $dTime < 10 ) {
                    return '刚刚';
                } else {
                    return intval(floor($dTime / 10) * 10) . "秒前";
                }
            } else if ( $dTime < 3600 ) {
                return intval($dTime / 60) . "分钟前";
                //今天的数据.年份相同.日期相同.
            } else if ( $dYear == 0 && $dDay == 0 ) {
                //return intval($dTime/3600)."小时前";
                return '今天' . date('H:i', $sTime);
            } else if ( $dYear == 0 ) {
                return date("m月d日 H:i", $sTime);
            } else {
                return date("Y-m-d H:i", $sTime);
            }
            break;
        case 'mohu':
            if ( $dTime < 60 ) {
                return $dTime . "秒前";
            } else if ( $dTime < 3600 ) {
                return intval($dTime / 60) . "分钟前";
            } else if ( $dTime >= 3600 && $dDay == 0 ) {
                return intval($dTime / 3600) . "小时前";
            } else if ( $dDay > 0 && $dDay <= 7 ) {
                return intval($dDay) . "天前";
            } else if ( $dDay > 7 && $dDay <= 30 ) {
                return intval($dDay / 7) . '周前';
            } else if ( $dDay > 30 ) {
                return intval($dDay / 30) . '个月前';
            }
            break;
        case 'full':
            return date("Y-m-d , H:i:s", $sTime);
            break;
        case 'ymd':
            return date("Y-m-d", $sTime);
            break;
        default:
            if ( $dTime < 60 ) {
                return $dTime . "秒前";
            } else if ( $dTime < 3600 ) {
                return intval($dTime / 60) . "分钟前";
            } else if ( $dTime >= 3600 && $dDay == 0 ) {
                return intval($dTime / 3600) . "小时前";
            } else if ( $dYear == 0 ) {
                return date("Y-m-d H:i:s", $sTime);
            } else {
                return date("Y-m-d H:i:s", $sTime);
            }
            break;
    }
}

/**
 * 时间差值
 * @param $begin_time
 * @param $end_time
 * @return string
 */

function getTimeDiff ($begin_time, $end_time)
{
    if ( $begin_time < $end_time ) {
        $starttime = $begin_time;
        $endtime = $end_time;
    } else {
        $starttime = $end_time;
        $endtime = $begin_time;
    }
    $timediff = $endtime - $starttime;
    $days = intval($timediff / 86400);
    $remain = $timediff % 86400;
    $hours = intval($remain / 3600);
    $remain = $remain % 3600;
    $mins = intval($remain / 60);
    $secs = $remain % 60;
    return $days . '天' . $hours . '小时' . $mins . '分';
}


/**
 * 数组分页函数.
 *
 * @author lyc
 *
 * @param $array 查询出来的所有数组
 * @param int $page 当前第几页
 * @param int $count每页多少条数据
 * @return array [需要的数据,总页数,总记录数]
 */
function arrayPage ($array, $page = 1, $count = 10)
{
    global $totalPage;

    // 判断当前页面是否为空 如果为空就表示为第一页面
    $page = (empty($page) || $page <= 1) ? 1 : $page;

    // 计算每次分页的开始位置
    $start = ($page - 1) * $count;

    $total = count($array);

    // 计算总页面数
    $totalPage = ceil($total / $count);

    // 拆分数据
    $list = array_slice($array, $start, $count);

    return [
        $list,
        $totalPage,
        $total
    ];
}


/**
 * 反引用一个使用 addcslashes()转义的字符串
 *
 * @author yzm
 *
 * @param $params
 *
 * @return array
 */
function __stripcslashes ($params)
{
    $_arr = [];

    foreach ($params as $key => $val) {
        $_arr[$key] = stripcslashes($val);
    }

    return $_arr;
}


/**
 * 格式化搜索时间.
 *
 * @author yzm
 *
 * @param bool|true $is_now
 * @return array [开始时间,结束时间,相差的天数]
 */
function getSearchDate ($is_now = true)
{
    $sdate = /*\Yii::$app->request->get('start_time');*/
    $edate = /*\Yii::$app->request->get('end_time');*/

    // 昨天时间戳
    $yestoday = date('Y-m-d', strtotime('-1 day'));
    $stime = strtotime($yestoday . ' 00:00:00');
    $etime = $is_now ? time() : strtotime($yestoday . ' 23:59:59');

    if ( $sdate && $edate ) {
        $stime = strtotime($sdate . ' 00:00:00');
        $etime = strtotime($edate . ' 23:59:59');
    } else if ( $sdate ) {
        $stime = strtotime($sdate . ' 00:00:00');
        $etime = strtotime("+1 month", $stime);
    } else if ( $edate ) {
        $etime = strtotime($edate . ' 23:59:59');
        $stime = strtotime("-1 month", $stime);
    }

    // 相差的天数
    $differ_day = ceil(($etime - $stime) / 86400);

    return [
        $stime,
        $etime,
        $differ_day
    ];
}


/**
 * 信息处理函数,结束进程.
 *
 * @author yzm
 */
function sig_func ()
{
    echo "SIGCHLD \r\n";

    pcntl_waitpid(-1, $status, WNOHANG);
}

/**
 * Notes:验证银行卡
 * User: lyc
 * Date: 2018/12/28
 * Time: 14:22
 * @param $no
 */
function isCardNu ($no)
{

    $arr_no = str_split($no);
    $last_n = $arr_no[count($arr_no) - 1];
    krsort($arr_no);
    $i = 1;
    $total = 0;
    foreach ($arr_no as $n) {
        if ( $i % 2 == 0 ) {
            $ix = $n * 2;
            if ( $ix >= 10 ) {
                $nx = 1 + ($ix % 10);
                $total += $nx;
            } else {
                $total += $ix;
            }
        } else {
            $total += $n;
        }
        $i++;
    }
    $total -= $last_n;
    $total *= 9;
    if ( $last_n == ($total % 10) ) {
        return true;
    }
    return false;
}

/**
 * 陌生刘：💻
 * Notes:去除html 样式 带截取的
 * User: lyc
 * Date: 2019/4/17
 * Time: 15:21
 * @param $string
 * @param string $sublen
 * @return string|string[]|null
 */
function cutstr_html ($string, $sublen = '')
{
    $string = strip_tags($string);
    $string = preg_replace('/\n/is', '', $string);
    $string = preg_replace('/ |　/is', '', $string);
    $string = preg_replace('/&nbsp;/is', '', $string);
    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);

    if ( count($t_string[0]) - 0 > $sublen && !empty($sublen) ) $string = join('', array_slice($t_string[0], 0, $sublen)) . "…";
    return $string;
}


/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
function object_to_array ($obj)
{
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if ( gettype($v) == 'resource' ) {
            return;
        }
        if ( gettype($v) == 'object' || gettype($v) == 'array' ) {
            $obj[$k] = (array)object_to_array($v);
        }
    }

    return $obj;
}

/**
 * 对银行卡号进行掩码处理
 * @param  string $bankCardNo 银行卡号
 * @return string             掩码后的银行卡号
 */
function formatBankCardNo ($bankCardNo)
{
//截取银行卡号前4位
    $prefix = substr($bankCardNo, 0, 3);
//截取银行卡号后4位
    $suffix = substr($bankCardNo, -4, 4);

    $maskBankCardNo = $prefix . " **** " . $suffix;


    return $maskBankCardNo;
}


/**
 * Notes:支付MD5加密
 * User: lyc
 * Date: 2019/2/26
 * Time: 16:25
 * @param $data
 * @return string
 */
function makeToken_md5 ($params)
{
    if ( !empty($params) ) {
        $p = ksort($params);
        if ( $p ) {
            $str = [];
            foreach ($params as $k => $val) {
                $str [$k] = $val;
            }
            return $str;
        }
    }
    return '参数错误';
}

//自定义ascii排序
function ASCII ($params = [])
{
    if ( !empty($params) ) {
        $p = ksort($params);
        if ( $p ) {
            $str = '';
            foreach ($params as $k => $val) {
                $str .= $k . '=' . $val . '&';
            }
            $strs = rtrim($str, '&');
            return strtoupper(md5($strs . '&key=' /*. Yii::$app->params['pay_']['key']*/));
        }
    }
    return '参数错误';
}


function curl_file_get_contents ($durl)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $durl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

/*查询sqlser 需要转译*/
function sqlser ($addslashes)
{
    return "'" . $addslashes . "'";
}

/**
 * Notes:数组转化字符串
 * User: lyc
 * Date: 2019/3/5
 * Time: 10:42
 * @param $dates
 * @return string
 */
function logs ($dates)
{
    $logInfo = '';
    if ( !is_array($dates) ) return $dates;
    foreach ($dates as $k => $item) {
        $logInfo .= $k . ' => ' . $item . '<br>';
    }
    return $logInfo;
}

/**
 * Notes:大文件读取 使用生成器
 * User: lyc
 * Date: 2019/3/8
 * Time: 18:17
 * @param $fileName
 * @return Generator
 */
function readYieldFile ($fileName)
{
    $handle = fopen($fileName, 'rb');
    while (!feof($handle)) {//feof 测试文件指针是否到了文件结束的位置
        yield fgets($handle);
    }
    fclose($handle);
}

/**
 * Notes:文件大小
 * User: lyc
 * Date: 2019/3/8
 * Time: 18:18
 * @param $bytes
 * @return string
 */
function formatBytes ($bytes)
{
    if ( $bytes < 1024 ) {
        return $bytes . "b";
    } else if ( $bytes < 1048576 ) {
        return round($bytes / 1024, 2) . 'KB';
    }
    return round($bytes / 1048576, 2) . 'MB';
}

/**
 * 🍰陌生刘：小生|落了💻
 * Notes:加减密
 * User: lyc
 * Date: 2019/3/21
 * Time: 10:53
 * @param $string
 * @param string $operation
 * @param string $key
 * @param int $expiry
 * @return bool|string
 */
function authCode ($string, $operation = ENCODE, $expiry = 0)
{
    $key = params('AUTH_KEY');
    $ckey_length = 10;     //密码在原有的基础上（0为原有）再增长 看心情增长

    $key = md5($key ? $key : '9e13yK8RN2M0lKP8CLRLhGs468d1WMaSlbDeCcI');
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));

    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = 100;

    $rndkey = [];
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = @$box[$i];
        @$box[$i] = $box[$j];
        @$box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = @$box[$a];
        @$box[$a] = $box[$j];
        @$box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ( $operation == 'DECODE' ) {
        if ( (substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16) ) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}


/**
 * PHP比robots更彻底地禁止蜘蛛抓取指定路径代码 By 张戈博客
 * 原文地址：https://zhang.ge/5043.html
 * 申   明：原创代码，转载请注保留出处，谢谢合作！
 * 使用说明：将一下代码添加到主题目录的functions.php当中即可。
 */
function Deny_Spider_Advanced ()
{
    $UA = $_SERVER['HTTP_USER_AGENT'];
    $Request_uri = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
    $Spider_UA = '/(spider|bot|)/i'; //定义需要禁止的蜘蛛UA，一般是spider和bot
    //禁止蜘蛛抓取的路径，可以参考自己的robots内容，每个关键词用分隔符隔开，需注意特殊字符的转义
    $Deny_path = '/\?replytocom=(\d+)|\?p=(\d+)|\/feed|\/date|\/wp-admin|wp-includes|\/go|comment-page-(\d+)/i';
    //如果检测到UA为空，可能是采集行为

    if ( !$UA ) {
        header("Content-type: text/html; charset=utf-8");
    } else {
        //如果发现是蜘蛛，并且抓取路径匹配到了禁止抓取关键词则返回404
        if ( preg_match_all($Spider_UA, $UA) && preg_match_all($Deny_path, $Request_uri) ) {
            //header('HTTP/1.1 404 Not Found');
            //header("status: 404 Not Found");
            header('HTTP/1.1 403 Forbidden'); //可选择返回404或者403（有朋友说内链404对SEO不太友好）
            header("status: 403 Forbidden");
        }
    }
}

/**
 * 陌生刘：💻
 * Notes:多个csv导成一个
 * User: lyc
 * Date: 2019/5/9
 * Time: 16:17
 * @param $dirName
 * @param $targetFile
 */
function mergeCSV ($dirName, $targetFile)
{
    //打开待操作的文件夹句柄
    $handle1 = opendir($dirName);
    //遍历文件夹
    while (($res = readdir($handle1)) !== false) {
        if ( $res != '.' && $res != '..' ) {
            //如果是文件，提出文件内容，写入目标文件
            if ( is_file($dirName . '/' . $res) ) {
                $fileName = $dirName . '/' . $res;
                //读
                $handle2 = fopen($fileName, 'r');
                if ( $str = fread($handle2, filesize($fileName)) ) {
                    fclose($handle2);
                    $handle3 = fopen($targetFile, 'a+');
                    if ( fwrite($handle3, $str) ) {
                        fwrite($handle3, "\n");
                        fclose($handle3);
                    }
                }
            }
            //如果是文件夹，继续调用mergeCSV方法
            if ( is_dir($dirName . '/' . $res) ) {
                $newDirName = $dirName . '/' . $res;
                mergeCSV($newDirName, $targetFile);
            }
        }
    }
}


//利用三元表达式，求三个数哪个最大 中 小
function mini ($a, $b, $c)
{
    $m = ($a >= $b) ? ($a >= $c ? $a : $c) : ($b >= $c ? $b : $c);
    $n = ($a <= $b) ? ($a <= $c ? $a : $c) : ($b <= $c ? $b : $c);
    $arr_1 = [
        $a,
        $b,
        $c
    ];
    $arr_2 = [
        $m,
        $n
    ];

    $arr_2 = array_flip($arr_2);
    foreach ($arr_1 as $key => $val) {
        if ( isset($arr_2[$val]) ) {
            unset($arr_1[$key]);
        }
    }

    return [
        $m,
        /*大*/
        array_values($arr_1)[0],
        /*中*/
        $n,
        /*小*/
    ];
}

/**
 * 陌生刘：💻
 * Notes:
 * User: lyc
 * Date: 2019/5/13
 * Time: 11:52
 * @param $file_name
 * @param $data1
 * @param array $data2
 * @param array $data3
 * @return bool
 * @throws PHPExcel_Exception
 * @throws PHPExcel_Reader_Exception
 * @throws PHPExcel_Writer_Exception
 */
function Excel ($file_name, $data1, $data2 = [], $data3 = [])
{
    require_once '.././PHPExcel-1.8/Classes/PHPExcel.php';

    // 不限定时间
    set_time_limit(0);
    // 内存限定
    ini_set('memory_limit', '1024M');

    /* @实例化 */
    $obpe = new \PHPExcel();

    /* @func 设置文档基本属性 */
    $obpe_pro = $obpe->getProperties();
    $obpe_pro->setCreator('lyc')//设置创建者
    ->setLastModifiedBy(date('Y-m-d'))//设置时间
    ->setTitle('Office 2007 XLSX  Document')//设置标题
    ->setSubject('Office 2007 XLSX  Document')//设置备注
    ->setDescription('document for Office 2007 XLSX, generated using PHP classes.')//设置描述
    ->setKeywords('office 2007 openxml php')//设置关键字 | 标记
    ->setCategory('file');//设置类别


    /* 设置宽度 */
    //$obpe->getActiveSheet()->getColumnDimension()->setAutoSize(true);
    //$obpe->getActiveSheet()->getColumnDimension('B')->setWidth(10);

    //设置当前sheet索引,用于后续的内容操作
    //一般用在对个Sheet的时候才需要显示调用
    //缺省情况下,PHPExcel会自动创建第一个SHEET被设置SheetIndex=0
    //设置SHEET
    if ( empty($data1) ) return false;
    $obpe->setactivesheetindex(0);
    $obpe->getActiveSheet()->setTitle('总销相关');
    //写入多行数据

    foreach ($data1 as $k => $v) {

        if ( count($v) !== 6 ) return false;

        $k = $k + 1;
        /* @func 设置列 */
        $obpe->getactivesheet()->setcellvalue('A' . $k, $v[0]);
        $obpe->getactivesheet()->setcellvalue('B' . $k, $v[1]);
        $obpe->getactivesheet()->setcellvalue('C' . $k, $v[2]);
        $obpe->getactivesheet()->setcellvalue('D' . $k, $v[3]);
        $obpe->getactivesheet()->setcellvalue('E' . $k, $v[4]);
        $obpe->getactivesheet()->setcellvalue('F' . $k, $v[5]);
    }

    if ( !empty($data2) ) {

        $obpe->createSheet();
        $obpe->setactivesheetindex(1);
        $obpe->getActiveSheet()->setTitle('UserId相关');
        //写入多行数据
        foreach ($data2 as $k => $v) {

            if ( count($v) !== 3 ) return false;
            $k = $k + 1;
            /* @func 设置列 */
            $obpe->getactivesheet()->setcellvalue('A' . $k, $v[0]);
            $obpe->getactivesheet()->setcellvalue('B' . $k, $v[1]);
            $obpe->getactivesheet()->setcellvalue('C' . $k, $v[2]);
        }
    }
    if ( !empty($data3) ) {

        $obpe->createSheet();
        $obpe->setactivesheetindex(2);
        $obpe->getActiveSheet()->setTitle('邀请码相关');
        //写入多行数据
        foreach ($data3 as $k => $v) {

            if ( count($v) !== 2 ) return false;
            $k = $k + 1;
            /* @func 设置列 */
            $obpe->getactivesheet()->setcellvalue('A' . $k, $v[0]);
            $obpe->getactivesheet()->setcellvalue('B' . $k, $v[1]);
        }
    }
    //写入类容
    $obwrite = \PHPExcel_IOFactory::createWriter($obpe, 'Excel5');

    //ob_end_clean();
    //保存文件
    //$obwrite->save('mulit_sheet.xls');

    //直接在浏览器输出
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Content-Type:application/force-download');
    header('Content-Type:application/vnd.ms-execl');
    header('Content-Type:application/octet-stream');
    header('Content-Type:application/download');
    header("Content-Disposition: attachment;filename = {$file_name}" . ".xlsx");
    header('Content-Transfer-Encoding:binary');
    $obwrite->save('php://output');
}

/**
 * 陌生刘：💻
 * Notes:时间格式是否正确
 * User: lyc
 * Date: 2019/5/30
 * Time: 12:21
 * @param $date
 * @return bool
 */
function dateTime ($date = '')
{

    if ( !empty($date) && date('Ymd', strtotime($date)) == $date ) {
        return '_' . $date;
    }
    return '_' . date('Ymd', time());
}

/**
 * 陌生刘：💻
 * Notes:时间格式是否正确
 * User: lyc
 * Date: 2019/5/30
 * Time: 12:21
 * @param $date
 * @return bool
 */
function dateYmdTime ($date = '')
{

    if ( !empty($date) && date('Ymd', strtotime($date)) == $date ) {
        return $date;
    }
    return date('Ymd', time());
}

/**
 * 陌生刘：💻
 * Notes:二分查找（数组里查找某个元素）
 * User: lyc
 * Date: 2019/6/4
 * Time: 12:32
 * @param $array
 * @param $low
 * @param $high
 * @param $k
 * @return int
 */
function bin_sch ($array, $low, $high, $k)
{
    if ( $low <= $high ) {
        $mid = intval(($low + $high) / 2);

        if ( $array[$mid] == $k ) {
            return $mid;
        } else if ( $k < $array[$mid] ) {
            return bin_sch($array, $low, $mid + 1, $k);
        } else {
            return bin_sch($array, $mid + 1, $high, $k);
        }
    }
    return -1;
}

/**
 * 陌生刘：💻
 * Notes:获取当前时间的 毫秒
 * User: lyc
 * Date: 2019/6/6
 * Time: 17:33
 * @param string $format
 * @param null $utimestamp
 * @return false|string
 */
function udate ($format = 'u', $utimestamp = null)
{

    if ( is_null($utimestamp) )

        $utimestamp = microtime(true);

    $timestamp = floor($utimestamp);

    $milliseconds = round(($utimestamp - $timestamp) * 1000);

    return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);

}


// 应用公共文件
/**
 * 删除目录以及其下的文件
 * @param $directory
 * @return bool
 */
function removeDir($directory)
{
    if (false == is_dir($directory)) {
        return false;
    }

    $handle = opendir($directory);
    while (false !== ($file = readdir($handle))) {
        if ('.' != $file && '..' != $file) {
            is_dir("$directory/$file") ? removeDir("$directory/$file") : @unlink("$directory/$file");
        }
    }

    if (readdir($handle) == false) {
        closedir($handle);
        rmdir($directory);
    }

    return true;
}


/**
 * @param    string      $string 加密内容
 * @param    string      $operation 加密动作
 * @param    string      $key 私钥
 * @param    int         $expiry 有效时间秒
 * @return   string      加密串
 */
function ucAuthCode($string, $operation = 'DECODE', $expiry = 0)
{
    $key = config('appIdKey');
    $ckey_length = 10;
    $key = md5($key);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    for($i = 0; $i <= 255; $i++)
    {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    for($j = $i = 0; $i < 256; $i++)
    {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for($a = $j = $i = 0; $i < $string_length; $i++)
    {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE')
    {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16))
        {
            return substr($result, 26);
        }else{
            return '';
        }
    }else{
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}


/**
 * [将Base64图片转换为本地图片并保存]
 * @E-mial wuliqiang_aa@163.com
 * @TIME   2017-04-07
 * @WEB    http://blog.iinu.com.cn
 * @param  [Base64] $base64_image_content [要保存的Base64]
 * @param  [目录] $path [要保存的路径]
 */
function base64_image_content($base64_image_content,$path){
    //匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        $new_file = $path."/".date('Ymd',time())."/";

        if(!file_exists($new_file)){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0777);
        }
        $new_file = $new_file.time().".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
            return $new_file;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

/**
 * 陌生刘：💻
 * Notes:删除空格
 * User: lyc
 * Date: 2019/7/30
 * Time: 10:50
 * @param $str
 * @return mixed
 */
function trimAll ($str)
{
    $oldChar = [
        " ",
        "　",
        "\t",
        "\n",
        "\r"
    ];
    $newChar = [
        "",
        "",
        "",
        "",
        ""
    ];
    return str_replace($oldChar, $newChar, $str);
}