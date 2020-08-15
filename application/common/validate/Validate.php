<?php
namespace app\common\validate;


/**
 * 验证器.
 *
 * Class Validator
 */
class  Validate
{
    /**
     * 正则.
     *
     * @var array
     */
    private static $regex_map = [
        'mobile'   => '/^1[34578]{1}\d{9}$/',
        'realName' => '/^[\x{4e00}-\x{9fa5}]{2,10}$|^[a-zA-Z\s]*[a-zA-Z\s]{2,20}$/isu',
        'idCard'   => '/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/',
        'passWord' => '/^[a-zA-Z0-9]{6,16}$/',
        'isUrl'    => '/^((http|https):\/\/).*/',
        'giftKey'  => '/^[a-zA-Z0-9- ]{4,}$/',
        'name'     => '/^[a-zA-Z][a-zA-Z0-9]{5,15}$/',
        'cardNu'   => '/^(\d{16}|\d{19}|\d{17})$/',
        'email'    => '/^[a-z0-9]+([._-][a-z0-9]+)*@([0-9a-z]+\.[a-z]{2,14}(\.[a-z]{2})?)$/i',
        'poInt'   => '/^[0-9]*[1-9][0-9]*$/',
    ];


    /**
     * 是否是一个用户名.
     *
     * @author lyc
     *
     * @param $mobile
     * @return bool
     */
    public static function isUserName ($name)
    {
        if (!is_string($name)) {
            return false;
        }
        return preg_match(self::$regex_map['name'], $name) ? true : false;
    }


    /**
     * 是否是一个手机号.
     *
     * @author yzm
     *
     * @param $mobile
     * @return bool
     */
    public static function isMobile ($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match(self::$regex_map['mobile'], $mobile) ? true : false;
    }


    /**
     * 是否是一个真实姓名.
     *
     * @author lyc
     *
     * @param $realName
     * @return bool 返回类型
     */
    public static function isUser ($realName)
    {
        if (!is_string($realName)) {
            return false;
        }
        return preg_match(self::$regex_map['realName'], $realName) ? true : false;
    }

    /**
     * 是否是一个真实身份证号.
     *
     * @author lyc
     *
     * @param $realName
     * @return bool 返回类型
     */
    public static function isCard ($idCard)
    {
        if (!is_string($idCard)) {
            return false;
        }
        return preg_match(self::$regex_map['idCard'], $idCard) ? true : false;
    }

    /**
     * 密码.
     *
     * @author lyc
     *
     * @param $realName
     * @return bool 返回类型
     */
    public static function isPassWord ($passWord)
    {
        if (!is_string($passWord)) {
            return false;
        }
        return preg_match(self::$regex_map['passWord'], $passWord) ? true : false;
    }

    /**
     * 是否是一个有效的url.
     *
     * @author yzm
     *
     * @param $url
     * @return bool
     */
    public static function isUrl ($url)
    {
        if (!is_string($url) || empty($url)) return false;

        return preg_match(self::$regex_map['isUrl'], $url) ? true : false;
    }

    /**
     * 验证是否是一个礼包key.
     *
     * @param $str
     * @return bool
     */
    public static function isGiftKey ($str)
    {
        if (empty($str)) return false;

        return preg_match(self::$regex_map['giftKey'], trim($str)) ? true : false;
    }

    /**
     * 验证是否是身份证号码.
     *
     * @param $str
     * @return bool
     */
    public static function isCardNu ($isCardNu)
    {
        if (!is_string($isCardNu) || empty($isCardNu)) return false;

        return preg_match(self::$regex_map['cardNu'], $isCardNu) ? true : false;
    }

    /**
     * 验证是否是邮箱.
     *
     * @param $str
     * @return bool
     */
    public static function isEmail ($isEmail)
    {
        if (!is_string($isEmail) || empty($isEmail)) return false;

        return preg_match(self::$regex_map['email'], $isEmail) ? true : false;
    }

    /**
     * 验证是否是正整数.
     *
     * @param $str
     * @return bool
     */
    public static function isPoInt ($isPoInt)
    {
        if (!is_int($isPoInt) || empty($isPoInt)) return false;

        return preg_match(self::$regex_map['poInt'], $isPoInt) ? true : false;
    }
}
