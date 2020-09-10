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
        $hostUrl = config('hostUrl');
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

        $access_token = $oauth2["access_token"];

        $openid = $oauth2['openid'];
        $get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" .$access_token. "&openid=".$openid;

        $userinfo = $this->getJson($get_user_info_url);

        file_put_contents("./uploads/invite_user.txt",json_encode($userinfo).PHP_EOL,FILE_APPEND);

        $unionid=$userinfo["unionid"];

        if($unionid!=''&&$inviterCode){
            //查看有么有该邀请用户
            $inviter = db('member')->where('inviteCOde',$inviterCode)->find();
            if($inviter){
                //查看该用户是否已经注册了
                $hadUser = db('member')->where('unionid',$unionid)->find();
                if(!$hadUser){
                    $password = 123456;
                    $inviteCode = \app\common\model\Share::getInviteCode();
                    $params = [
                        'phone'=>'',
                        'password'=>md5($password),
                        'real_pass'=>$password,
                        'username'=>'',
                        'nickname'=>'',
                        'createTime'=>time(),
                        'money'=>0,
                        'openid'=>$openid,
                        'unionid'=>$unionid,
                        'avatar'=>'',
                        'inviteCode'=>$inviteCode,
                        'inviterCode'=>$inviterCode,
                    ];
                    db('member')->insert($params);
                    //邀请新人奖励
                    \app\common\model\Share::shareReward($inviter['id'],'','邀请新人奖励',4);
                }
            }

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
}