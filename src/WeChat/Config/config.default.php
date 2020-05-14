<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 设置微信相关接口的配置信息
//+-----------------------------------------------------------
!defined('DS')?define('DS',DIRECTORY_SEPARATOR):'';


/**
 * 配置信息
 * 注意：目录分隔符不要使用“/”、"\"  请使用定义的DS常量
 */
return $config = [
    //小程序或者公众号应用ID
    'appid'                => '',
    //小程序或者公众号开发者密钥
    'appsecret'            => '',
    //除app外的支付需要此参数 商户号
    'mch_id'               => '',
    //app支付需要  商户号
    'partnerid'            => '',
    //商户号接口安全密钥
    'api_key'              => '',
    //商户证书 退款等需要
    'sslcert'              => '',
    //商户证书 退款等需要
    'sslkey'               => '',
    //消息推送模板id
    'message_templet_id'   => '',
    //access_token本地存储文件路径,
    'access_token_storage' => 'wxinfo' . DS . 'access_token.php',
    //access_token的存储方式 IO存入文件 Cache存入缓存 Other由其他接管存储及验证本程序包只负责获取
    'access_token_style'   => 'IO',
    //js_ticket本地存储文件路径
    'js_ticket_storage'    => 'wxinfo' . DS . 'js_ticket.php',
    //同access_token #TODO acces_token及js_ticket存储方式处理暂时没有
    'js_ticket_style'      => 'IO',
    //用户授权回调
    'oauth_notify'         => '',
    //支付通知回调
    'pay_notify'           => '',
    //wxjs授权域名
    'auth_domain'          => '',
    //日志状态
    'log_status'           => true,
    //日志位置  会在该目录下生成yy-mm/dd.log
    'log_path'             =>  'runtime' . DS . 'log' .DS ,
    //日志文件最大字节数 超过最大限制日志文件分片 （单位字节）
    'log_file_max_size'    => 1024*1024,
];