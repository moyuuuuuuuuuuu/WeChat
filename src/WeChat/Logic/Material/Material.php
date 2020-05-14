<?php
//+-------------------------------------------------------------------------
//| Man barricades against himself.
//+-------------------------------------------------------------------------
//| Author:Janas <janmas@126.com> Time:2020/05/05 15:19
//+-------------------------------------------------------------------------
//| 素材库相关接口
//+-------------------------------------------------------------------------


namespace WeChat\Logic\Material;


use WeChat\Exception\MaterialException;
use WeChat\Logic\Base;
use WeChat\Tool\Http;

class Material extends Base
{

    /**
     * 上传临时素材
     * @param string $type
     * @param string $media
     * @return mixed
     */
    public function upload($media='',$type='image'){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/media/upload?access_token=%s&type=%s',$this->getToken(),$type);

        $data = [
            'media'=> new \CURLFile(realpath($media)),
        ];
		$data['form-data'] = $data;
        $result = json_decode(Http::post($url,$data),true);
        if(is_null($result)){
            throw new MaterialException('上传临时素材失败','',__FILE__,__LINE__);
        }
        return $result;
    }

    /**
     * 获取素材
     * @param string $media
     * @return mixed
     * @throws MaterialException
     * @throws \WeChat\Exception\AccessTokenException
     */
    public function getMedia($media = ''){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s',$this->getToken(),$media);

        $res = Http::get($url);
        return $res;
    }
}