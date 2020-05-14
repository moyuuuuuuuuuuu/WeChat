<?php
//+-------------------------------------------------------------------------
//| Man barricades against himself.
//+-------------------------------------------------------------------------
//| Author:Janas <janmas@126.com> Time:2020/05/05 14:32
//+-------------------------------------------------------------------------
//| 客服账号相关接口
//+-------------------------------------------------------------------------


namespace WeChat\Logic\CustomerService;

use WeChat\Exception\AccountException;
use WeChat\Logic\Base;
use WeChat\Tool\Http;

class Account extends Base
{

    /**
     * 添加客服账号
     * @param string $kf_account 客服账号
     * @param string $nickname 客服昵称
     * @param string $password 客服账号密码
     * @return bool
     */

    public function createAccount($kf_account='',$nickname='',$password=''){
        $url = sprintf('https://api.weixin.qq.com/customservice/kfaccount/add?access_token=%s',$this->getToken());
        $data = [
            'kf_account' => $kf_account,
            'nickname' => $nickname,
            'password' => $password
        ];

        $res = json_decode(Http::post($url,json_encode($data,JSON_UNESCAPED_UNICODE)),true);

        if($res['errcode']!= 0){
            throw new AccountException('',$res['errcode'],__FILE__,__LINE__);
        }

        return true;

    }

    /**
     * 修改客服接口
     * @param string $kf_account
     * @param string $nickname
     * @param string $password
     * @return bool
     */
    public function updateAccount($kf_account='',$nickname='',$password=''){
        $url = sprintf('https://api.weixin.qq.com/customservice/kfaccount/update?access_token=%s',$this->getToken());

        $data = [
            'kf_account' => $kf_account,
            'nickname' => $nickname,
            'password' => $password
        ];

        $res = json_decode(Http::post($url,json_encode($data,JSON_UNESCAPED_UNICODE)),true);

        if($res['errcode']!= 0){
            throw new AccountException('',$res['errcode'],__FILE__,__LINE__);
        }

        return true;
    }

    /**
     * 删除客服账号
     * @param string $kf_account
     * @param string $nickname
     * @param string $password
     * @return bool
     */
    public function delAccount($kf_account='',$nickname='',$password=''){
        $url = sprintf('https://api.weixin.qq.com/customservice/kfaccount/del?access_token=%s',$this->getToken());

        $data = [
            'kf_account' => $kf_account,
            'nickname' => $nickname,
            'password' => $password
        ];

        $res = json_decode(Http::post($url,json_encode($data,JSON_UNESCAPED_UNICODE)),true);

        if($res['errcode']!= 0){
            throw new AccountException('',$res['errcode'],__FILE__,__LINE__);
        }

        return true;
    }

    /**
     * 获取所有客服账号
     * @return mixed
     */
    public function getAccounts(){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=%s',$this->getToken());

        $res = json_decode(Http::get($url),true);

        if(array_key_exists('errcode',$res) && $res['errcode'] != 0 ){
            throw new AccountException('',$res['errcode'],__FILE__,__LINE__);
        }

        return $res;
    }

}