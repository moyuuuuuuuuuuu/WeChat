<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 推送模板消息
//+-----------------------------------------------------------

namespace WeChat\Logic\CustomerService;


use WeChat\Exception\MessageException;
use WeChat\Logic\Base;
use WeChat\Tool\Http;

class TemplateMessage extends Base
{
    /**
     * 设置所属行业
     * @param $data
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function setIndustry($data){
        $url = sprintf(' https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=%s',$this->getToken());

        $res = Http::post($url,$data);
        #TODO:设置行业没有返回值
    }

    /**
     * 获取设置的行业信息
     * @return mixed
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function getIndustry(){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token=%s',$this->getToken());
        return json_decode(Http::get($url),true);

    }

    /**
     * 获取模板id
     * @param $template_id_short
     * @return mixed
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function getTempletId($template_id_short){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=%s',$this->getToken());
        $res = json_decode(Http::post($url,['template_id_short'=>$template_id_short]),true);

        if($res['errcode'] != 0){
            throw new MessageException('',$res['errcode'],__FILE__,__LINE__);
        }

        return $res;
    }

    /**
     * 获取模板列表
     * @return false|string
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function getTempletList(){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=%s',$this->getToken());

        return file_get_contents($url);
    }

    /**
     * 删除模板
     * @param $templet_id
     * @return bool
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function deleteTemplet($templet_id){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token=%s',$this->getToken());
        $res = json_decode(HTTP::post($url,['templet_id'=>$templet_id]),true);
        if($res['errcode'] != 0 ){
            throw new MessageException('',$res['errcode'],__FILE__,__LINE__);
        }
        return true;
    }

    /**
     * 发送模板消息
     * @param $data
     * @param $touser
     * @param $templetId
     * @param $url
     * @param string $topcolor
     * @return mixed
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function sendMessage($data,$touser,$templetId,$url,$topcolor='FF0000'){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=%s',$this->getToken());

        $templet['touser'] =  $touser;
        $templet['data'] = $data;
        $templet['topcolor'] = $topcolor;
        $templet['url'] = $url;
        $templet['template_id'] = $templetId;
        $res = json_decode(Http::post($url,$templet),true);

        if($res['errcode'] != 0 ){
            throw new MessageException('',$res['errcode'],__FILE__,__LINE__);
        }
        return $res;
    }


}