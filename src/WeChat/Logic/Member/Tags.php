<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 用户标签
//+-----------------------------------------------------------


namespace WeChat\Logic\Member;

use WeChat\Exception\TagsException;
use WeChat\Logic\Base;
use WeChat\Tool\Http;

class Tags extends Base
{
    /**
     * 创建公众号标签
     * @param string $name
     * @return mixed
     * @throws TagsException
     */
    public function create($name=''){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/tags/create?access_token=%s',$this->getToken());
        $tags['tag'] = ['name'=>$name];

        $result = json_decode(Http::post($url,json_encode($tags,JSON_UNESCAPED_UNICODE)),true);

        if(array_key_exists('errcode',$result) && $result['errcode'] != 0 ){
            throw new TagsException('',$result['errcode'],__FILE__,__LINE__);
        }

        return $result['tag']['id'];
    }

    /**
     * 获取当前公众号的所有标签
     * @return mixed
     * @throws TagsException
     */
    public function get(){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/tags/get?access_token=%s',$this->getToken());
        $result = json_decode(Http::get($url),true);

        if(array_key_exists('errcode',$result) && $result['errcode'] != 0){
            throw new TagsException('',$result['errcode'],__FILE__,__LINE__);
        }
        return $result['tags'];
    }


    /**
     * 修改标签
     * @param $id 标签id
     * @param $name 修改后的标签名
     * @return bool
     * @throws TagsException
     */
    public function update($id,$name){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/tags/update?access_token=%s',$this->getToken());
        $data = ['tag'=>['id'=>$id,'name'=>$name]];
        $result = jsoN_decode(Http::post($url,json_encode($data)),true);

        if(array_key_exists('errcode',$result) && $result['errcode'] != 0){
            throw new TagsException('',$result['errcode'],__FILE__,__LINE__);
        }
        return true;
    }

    /**
     * 删除标签
     * @param $id 标签id
     * @return bool
     * @throws TagsException
     */
    public function del($id){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/tags/delete?access_token=%s',$this->getToken());
        $data = ['tag'=>['id'=>$id]];

        $result = json_decode(Http::post($url,json_encode($data)),true);

        if(array_key_exists('errcode',$result) && $result['errcode'] != 0){
            throw new TagsException('',$result['errcode'],__FILE__,__LINE__);
        }
        return true;
    }

    /**
     * 获取某标签下的用户
     * @param $tag_id 标签id
     * @param string $openid 从哪个用户开始获取 为空则查询全部
     * @return mixed
     * @throws TagsException
     */
    public function getUserUnderTag($tag_id,$openid=''){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token=%s',$this->getToken());

        $data = ['tagid'=>$tag_id,'next_openid'=>$openid];

        $result = json_decode(Http::post($url,json_encode($data)),true);
        if(array_key_exists('errcode',$result) && $result['errcode'] != 0){
            throw new TagsException('',$result['errcode'],__FILE__,__LINE__);
        }

        return $result;
    }

    /**
     * 批量为用户打上标签
     * @param array $openid
     * @param $tag_id
     * @return bool
     * @throws TagsException
     */
    public function putTagForUsers($openid=[],$tag_id){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=%s',$this->getToken());

        $data = [
            'openid_list' => $openid,
            'tagid' => $tag_id
        ];

        $result = json_decode(Http::post($url,json_encode($data)),true);

        if(array_key_exists('errcode',$result) && $result['errcode'] != 0){
            throw new TagsException('',$result['errcode'],__FILE__,__LINE__);
        }
        var_dump($result);
        return true;
    }

    /**
     * 批量取消标签
     * @param array $openid
     * @param $tag_id
     * @return bool
     * @throws TagsException
     */
    public function cancelTagForUsers($openid=[],$tag_id){
        $url = sprintf('https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token=%s',$this->getToken());

        $data = [
            'openid_list' => $openid,
            'tagid' => $tag_id
        ];

        $result = json_decode(Http::post($url,json_encode($data)),true);

        if(array_key_exists('errcode',$result) && $result['errcode'] != 0){
            throw new TagsException('',$result['errcode'],__FILE__,__LINE__);
        }
        return true;
    }

    /**
     * 获取某用户的标签
     * @param string $openid
     * @return mixed
     * @throws TagsException
     */
    public function getTagByOpenid($openid = ''){
        $url = sprintf(' https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=%s',$this->getToken());

        $data = ['openid'=>$openid];

        $result = json_decode(Http::post($url,json_encode($data)),true);

        if(array_key_exists('errcode',$result)){
            throw new TagsException('',$result['errcode'],__FILE__,__LINE__);
        }

        return $result;
    }
}