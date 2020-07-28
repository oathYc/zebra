<?php
namespace app\common\utils;

/**
 * User: nickbai
 * Date: 2017/10/23 13:33
 * Email: 1902822973@qq.com
 */

require './../extend/PHPMailer_v5.1/class.phpmailer.php';
require './../extend/PHPMailer_v5.1/class.smtp.php';

class Mail
{
    public static function sendMail ($mails, $title, $content)
    {

        $toemail = $mails;
        $mail = new \PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->CharSet = 'utf8';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = "SMTP.aliyun.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'moshengliu@aliyun.com';
        $mail->Password = 'Liu123456';
        $mail->FromName = '圣城科技问题反馈';
        $mail->setFrom("moshengliu@aliyun.com","圣城科技问题反馈");// 设置发件人信息，如邮件格式说明中的发件人,
        $mail->addAddress($toemail,'尊敬的客户');// 设置收件人信息，如邮件格式说明中的收件人
//        $mail->addReplyTo("17628090501@163.com","Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        $mail->Subject = $title;// 邮件标题
        $mail->Body = $content;
        $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
        if($mail->send()){// 发送邮件
            return true;
        }
        return false;
//        else{
//            return false;
//        }
    }
}