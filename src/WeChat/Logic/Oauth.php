<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 微信用户授权相关
//+-----------------------------------------------------------

namespace WeChat\Logic;

use WeChat\Exception\OauthException;
use WeChat\Tool\Http;
class Oauth extends Base
{
    private $appid='';
    private $notify = '';

    /**
     * 跳转到授权页面
     */
    public function getAuth(){
        $url = sprintf("https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=%d#wechat_redirect",$this->config->get('appid'),$this->config->get('oauth_notify'),rand(111,999));
        header('Location:'.$url);
    }

    /**
     * 根据cod获取openid和access_token
     * @param $code
     * @param string $key 获取指定的元素 为空则返回数组 非空则返回指定键的值
     * @return mixed
     */
    public function oauthCallBack($code,$key=''){
        $url = sprintf(' https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code',$this->config->get('appid'),$code);
        $res = json_decode(Http::get($url),true);
        if(array_key_exists('errcode',$res) && $res['errcode'] != 0 ){
            throw new OauthException($res['errmsg'],$res['errcode'],__FILE__,_LINE__);
        }
        if(empty($key)){
            return $res;
        }
        return $res[$key];
    }

    /**
     * 刷新access_token
     * @param $refresh_token
     * @param string $key 获取指定返回值 为空则获取全部
     * @return mixed
     */
    public function refreshToken($refresh_token,$key=''){
        $url = sprintf('https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s',$this->config->get('appid'),$refresh_token);
        $res = json_decode(file_get_contents($url),true);
        if(array_key_exists('errcode',$res) && $res['errcode'] != 0 ){
            throw new OauthException($res['errmsg'],$res['errcode'],__FILE__,_LINE__);
        }
        if(empty($key)){
            return $res;
        }

        return $res[$key];
    }

    /**
     * 获取用户基本信息
     * @param $openid
     * @param $access_token
     * @param string $key 获取用户指定信息 为空则获取全部
     * @return mixed
     */
    public function getUserInfo($openid,$access_token,$key=''){
        $url = sprint('tps://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN',$openid,$access_token);
        $res = json_decode(file_get_contents($url),true);
        if(empty($key)){
            return $res;
        }

        return $res[$key];
    }

}