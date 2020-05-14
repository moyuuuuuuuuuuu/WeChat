<?php
//+-------------------------------------------------------------------------
//| Man barricades against himself.
//+-------------------------------------------------------------------------
//| Author:Janas <janmas@126.com> Time:2020/05/04 16:59
//+-------------------------------------------------------------------------
//| 客服消息接口
//+-------------------------------------------------------------------------


namespace WeChat\Logic\CustomerService;

use WeChat\Exception\MessageException;
use WeChat\Logic\Base;
use WeChat\Tool\Http;

class Message extends Base
{

    /**
     * 发送客服消息
     * @param $touser 接收人openid
     * @param string $msgType 客服消息类型
     * @param $data
     *              msgType text
     *              $data string 文本消息
     *              msgType voice
     *              $data string 语音的media_id
     *              msgType image
     *              $data string 图片的media_id
     *              msgType
     * @return bool
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function sendMessage($touser,$msgType='text',$data){
        if(empty($touser)){
            throw new MessageException('请传入接收者openid');
        }

        if(empty($data)){
            throw new MessageException('请传入发送的消息');
        }
        $data = $this->checkArg($msgType,$data);
        $url = sprintf('https://api.weixin.qq.com/customservice/kfaccount/add?access_token=%s',$this->getToken());

        $result = json_decode(Http::post($url,json_encode($data,JSON_UNESCAPED_UNICODE)),true);

        if($result['errcode'] != 0 ){
            throw new MessageException('',$result['errcode'],__FILE__,__LINE__);
        }
        return $result;
    }

    /**
     * 组合不同消息类型需要的json数据包
     * @param $msg_type
     * @param $data
     * @return mixed
     */
    private function checkArg($msg_type,$data){
        switch($msg_type){
            case 'news':
                $content['news'] = ['articles'=>[$data]];
                break;
            case 'voice':
                $content['voice'] = ['media_id'=>$data];
                break;
            case 'video':
                $content['video'] = $data;
                break;
            case 'music':
                $content['music'] = $data;
                break;
            case 'msgmenu':
                $content['msgmenu']  = [$data];
                break;
            case '':
                $content['image'] = ['media_id'=>$data];
                break;
            default:
                $content['text'] = ['content'=>$data];
                break;
        }
        return $content;
    }
}