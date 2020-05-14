<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 设置微信相关接口的配置信息
//+-----------------------------------------------------------

return $config = [
    'appid'                => 'wx48385d850b546ea4',
    'appsecret'            => 'ec398fa70d76fbb029650582325822e3',
    'mch_id'               => '1497041092',
    'partnerid'            => '',                           //app支付需要
    'api_key'              => 'zheshiapianquanmiyao',       //商户号接口安全密钥
    'sslcert'              => '',
    'sslkey'               => '',
    'message_templet_id'   => '',       //消息推送模板id
    'access_token_storage' => 'wxinfo' . DIRECTORY_SEPARATOR . 'access_token.php',     //access_token本地存储文件路径,
    'js_ticket_storage'    => 'wxinfo' . DIRECTORY_SEPARATOR . 'js_ticket.php',      //js_ticket本地存储文件路径
    'oauth_notify'         => '',       //用户授权回调
    'pay_notify'           => '',       //支付通知回调
    'auth_domain'          => '',       //wxjs授权域名
    'log_status'           => true,
    'sys_error_log_path'   => 'log' . DIRECTORY_SEPARATOR  . 'error.log',       //日志文件
    'log_path'             =>  'runtime' . DIRECTORY_SEPARATOR . 'log' .DIRECTORY_SEPARATOR ,
    'log_file_max_size'    => 1024*1024,//日志文件最大字节数
];