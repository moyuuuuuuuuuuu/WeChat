<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 获取access_token
//+-----------------------------------------------------------
namespace WeChat\Logic;

use WeChat\Tool\{File,Http};
use WeChat\Exception\AccessTokenException;

class Token extends Base
{
    protected static $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';

    /**
     * 获取access_token
     * @return mixed
     * @throws AccessTokenException
     */
    public function getAccessToken(){
        //获取本地存储的access_token
        $token = $this->getLocalToken();
        if($token && $token['expires_in'] > time()){
            return $token['access_token'];
        }else{
            self::$url = sprintf(self::$url,$this->config->get('appid'),$this->config->get('appsecret'));
            $res = Http::get(self::$url);
            $res = json_decode($res,true);
            if(!array_key_exists('errcode',$res)){
                $this->storageAccessToken($res);
                return $res['access_token'];
            }else{
                throw new AccessTokenException($res['errmsg'],$res['errcode'],__FILE__,__LINE__);
            }
        }
    }


    /**
     * 获取本地access——token
     * @return bool
     */
    private function getLocalToken(){
        $storage = $this->config->get('access_token_storage');
        if(is_file($storage) && @filesize($storage) > 0 ){
            $handle = fopen($storage,'r+');
            $token = json_decode(substr(fread($handle,filesize($storage)),13),true);
            // $token = json_decode(substr(file_get_contents($storage),13),true);
            fclose($handle);
            return $token;
        }else{
            if(!is_file($storage)) File::touchFile($storage);
            return false;
        }
    }

    /**
     * 存储access——token到本地
     * @param $data
     */
    private function storageAccessToken($data){
        $storage = $this->config->get('access_token_storage');
        $data['expires_in'] = time() + 7000;
        $json = '<?php exit;?>' . json_encode($data);
        file_put_contents($storage,$json);

    }
}
