<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 获取jsticket
//+----------------------------------------------------------

namespace WeChat\Logic;


use WeChat\Exception\TicketException;
use WeChat\Tool\{File,Http};

class Ticket extends Base
{

    public function getTicket(){
        $ticket = $this->getLocalJsTicket();
        if($ticket && $ticket['expires_in'] > time()){
            return $ticket['js_ticket'];
        }
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi',$this->getToken());
        $res = json_decode(Http::get($url),true);
        if(array_key_exists('errcode',$res) && $res['errcode'] != 0){
            throw new TicketException('',$res['errcode'],__FILE__,__LINE__);
        }
        return $this->storageJsTicket($res);

    }
    #TODO 逻辑不通多看文档

    /**
     * @return mixed
     * @throws TicketException
     */
    public function buildSign(){
        $data['ticket'] = $this->getTicket();
        $data['url'] = $this->config->get('auth_domain');
        $data['noncestr'] = rand(111111,999999);
        $data['timestaamp'] = time();

        $js_ticket = '';
        ksort($data);
        foreach($data as $key=>$value){
            $js_ticket .= $key . "=" . $value;
        }
        $data['signature'] = sha1($js_ticket);
        return $data;
    }

    private function getLocalJsTicket(){
        $storage = $this->config->get('js_ticket_storage');
        if(is_file($storage) && @filesize($storage)>0){
            return json_decode(substr(file_get_contents($storage),13),true);
        }else{

            if(!is_file($storage)) File::touchFile($storage);
            return false;
        }
    }

    private function storageJsTicket($res){
        $json = [
            'js_ticket' => $res['ticket'],
            'expires_in' => time()+700
        ];
        $json = '<?php exit;?>' . json_encode($json);
        file_put_contents($this->config->get('js_ticket_storage'),$json);
        return $res['ticket'];
    }

}