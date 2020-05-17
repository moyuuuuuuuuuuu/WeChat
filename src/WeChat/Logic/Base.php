<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//|基类（注册默认配置）
//+-----------------------------------------------------------
namespace WeChat\Logic;
use WeChat\Tool\Config;
use WeChat\Tool\{Log, Http, File, Tool, Xml};

class Base
{

    protected static $config = [];

    protected static $register = [
        'config' => Config::class,
        'log' => Log::class,
        'tool' => Tool::class,
    ];

    protected static $object = [];

    public function __construct($config=[])
    {
        if(version_compare(PHP_VERSION,'7.2.0','<'))  die('PHP最低版本7.2.0');
        if(empty(self::$object)){
            $this->register();
            if(!empty($config)) $this->config->set($config);
        }
    }

    private function register(){
        foreach(self::$register as $key=>$value){
            if(array_key_exists($key,self::$object)){
                continue;
            }

            if(method_exists($value,'register')){
                self::$object[$key] = $value::instance()->register();
            }else if (method_exists($value,'instance')){
                self::$object[$key] = $value::instance();
            }else{
                self::$object[$key] = (new $value);
            }
        }
    }

    /**
     * 获取token
     * @return mixed
     * @throws \WeChat\Exception\AccessTokenException
     */
    protected function getToken(){
        return  (new Token())->getAccessToken();
    }

    #TODO：待做接口 见文档 https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Get_the_WeChat_server_IP_address.html
    # 用户管理、账号管理、素材管理、消息管理

    public function __set($key,$value){
        $this->$key = $value;
    }

    public function __get($key){
        return self::$object[$key];
    }

}