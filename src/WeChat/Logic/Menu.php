<?php
//+-------------------------------------------------------------------------
//| Man barricades against himself.
//+-------------------------------------------------------------------------
//| Author:Janas <janmas@126.com> Time:2020/05/02 9:26
//+-------------------------------------------------------------------------
//| 微信菜单相关接口
//+-------------------------------------------------------------------------


namespace WeChat\Logic;


use WeChat\Exception\MenuException;
use WeChat\Tool\Http;

class Menu extends Base
{
    /**
     * 创建微信菜单
     * @param $data string json_encode之后的数组信息
     * @return bool
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function build(String $data){
        if(empty($data)){
            throw new MenuException('请传入菜单参数');
        }
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s',$this->getToken());
        $res = json_decode(Http::post($url,json_encode($data)),true);
        if($res['errcode'] != 0){
            throw new MenuException($res['errmsg'],$res['errcode'],__FILE__,__LINE__);
        }

        return true;
    }

    /**
     * 查询公众号菜单
     * @return bool|mixed
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function query(){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token=%s',$this->getToken());
        $res = json_decode(Http::get($url),true);
        if(!is_null($res)){
            return $res;
        }
        throw new MenuException($res['errmsg'],$res['errcode'],__FILE__,__LINE__);
    }

    /**
     * 删除公众号菜单
     * @return bool
     * @throws MenuException
     */
    public function delete(){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=%s',$this->getToken());
        $res = json_decode(Http::get($url),true);

        if($res['errcode'] != 0){
            throw new MenuException($res['errmsg'],$res['errcode'],__FILE__,__LINE__);
        }

        return true;
    }
}