<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 用户管理
//+-----------------------------------------------------------


namespace WeChat\Logic\Member;

use WeChat\Exception\UserException;
use WeChat\Logic\Base;
use WeChat\Tool\Http;

class User extends Base
{

    /**
     * 获取用户基本信息（包括UnionID机制）
     * @param $openid openid
     * @param string $key 需要获取的返回值键名
     * @param string $lang
     * @return mixed
     * @throws UserException
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function getUserInfo($openid='',$key='',$lang='zh_CN'){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=%s',$this->getToken(),$openid,$lang);
        $result = json_decode(Http::get($url),true);
        if(array_key_exists('errcode',$result)){
            throw new UserException('',$result['errcode']);
        }
        if($key && array_key_exists($key,$result)){
            return $result[$key];
        }
        return $result;
    }

    /**
     * 批量获取用户基本信息
     * @return mixed
     * @throws UserException
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function getUsersInfo($data=[]){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=%s',$this->getToken());
        $data['user_list'] = $data;
        $result = json_decode(Http::post($url,json_encode($data)),true);
        if(array_key_exists('errcode',$result) && $result['errcode'] != 0){
            throw new UserException('',$result['errcode']);
        }

        return $result;
    }

    /**
     * 获取用户列表
     * @param string $openid
     * @return mixed
     * @throws UserException
     */
    public function getUserList($openid=''){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/user/get?access_token=%s&next_openid=%s',$this->getToken(),$openid);

        $result = json_decode(file_get_contents($url),true);

        if(array_key_exists('errcode',$result)){
            throw new UserException('',$result['errcode'],__FILE__,__LINE__);
        }
        return $result;
    }

    /**
     * 黑名单
     * @param string $openid
     * @return mixed
     * @throws UserException
     */
    public function blackList($openid=''){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist?access_token=%s',$this->getToken());
        $data  = ['begin_openid'=>$openid];
        $result = json_decode(Http::post($url,json_encode($data)),true);

        if(array_key_exists('errcode',$result)){
            throw new UserException('',$result['errcode'],__FILE__,__LINE__);
        }

        return $result;
    }

}