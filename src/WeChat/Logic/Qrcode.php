<?php
//+-------------------------------------------------------------------------
//| Man barricades against himself.
//+-------------------------------------------------------------------------
//| Author:Janas <janmas@126.com> Time:2020/05/02 14:26
//+-------------------------------------------------------------------------
//| 二维码相关（暂时不可用）
//+-------------------------------------------------------------------------

namespace WeChat\Logic;

use WeChat\Exception\QrcodeException;
use WeChat\Tool\Http;

class Qrcode extends Base
{

    //二维码的几种状态  'QR_SCENE','QR_STR_SCENE','QR_LIMIT_SCENE','QR_LIMIT_STR_SCENE'

    /**
     * 生成二维码
     * @param string $action_name
     * @param string $expire_seconds
     * @param array $action_info
     * @return mixed
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function createQrcode($action_name='QR_SCENE',$scene_id=0,$scene_str='',$expire_seconds=30){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s',$this->getToken());

        $data['action_name'] = $action_name;
        $data['expire_seconds'] = $expire_seconds;
        if(!empty($scene_str) && $scene_id > 0 ){
            $data['action_info'] = [
                'scene_id' => $scene_id,
                'scene_str' => $scene_str
            ];

        }

        $res = json_decode(Http::post($url,json_encode($data,JSON_UNESCAPED_UNICODE)),true);
        if($res['errcode'] != 0){
           throw new QrcodeException('二维码生成失败',$res['errcode'],__FILE__,__LINE__);
        }
        return $res;

    }

    /**
     * 根据ticket获取二维码
     * @param $ticket
     * @return mixed
     */
    public function getQrcodeByTicket($ticket){
        $url = sprintf('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=%s',$ticket);
        return Http::get($url);
    }
}