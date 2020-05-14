<?php
//+-------------------------------------------------------------------------
//| Just do it !
//+-------------------------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-------------------------------------------------------------------------
//| 
//+-------------------------------------------------------------------------

namespace WeChat\Logic;

use WeChat\Logic\CustomerService\Account;
use WeChat\Logic\CustomerService\Message;
use WeChat\Logic\CustomerService\TemplateMessage;
use WeChat\Logic\Material\Material;
use WeChat\Logic\Member\Tags;
use WeChat\Logic\Member\User;
use WeChat\Logic\Pay\Pay;
use WeChat\Logic\Pay\Query;
use WeChat\Logic\Pay\Refund;

class Container extends Base
{
    private static $bind = [
        'pay' => Pay::class,
        'query' => Query::class,
        'refund' => Refund::class,

        'tags' => Tags::class,
        'user' => User::class,

        'material' => Material::class,

        'account' => Account::class,
        'message' => Message::class,
        'templetmessage' => TemplateMessage::class,

        'token' => Token::class,
        'ticket' => Ticket::class,
        'qrcode' => Qrcode::class,
        'oauth' => Oauth::class,
        'menu' => Menu::class,
    ];

    public function get($name,$options=[]){
        return new self::$bind[$name]($options);
    }

    protected function getToken(){
        return  (new Token())->getAccessToken();
    }
}