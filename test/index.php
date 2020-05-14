<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------

/*
 * 客服类
 * 客服账号添加、修改、删除、获取全部客服账号、客服消息推送（图文、文字、音视频）
 *
 * 菜单类
 * 微信公众号菜单添加、查询、删除
 *
 * 支付类
 * 微信支付、查询订单、退款、查询退款
 *
 * 用户类
 * 获取用户基本信息、批量获取用户基本信息、获取用户列表、黑名单、
 *
 * 用户标签类
 * 创建公众号标签、获取当前公众号的所有标签、修改标签、删除标签、获取某标签下的用户、批量为用户打上标签、批量取消标签、获取某用户的标签
 *
 * 素材类
 * 上传临时素材、获取素材
 *
 * 模板消息
 *
 * 网页授权
 *
 * 获取access——token
 *
 * 获取js_ticket
 *
*/
spl_autoload_register(function ($name) {
    if($name != 'WeChat\Tool\Exception'){
        $name = str_replace('\\',DIRECTORY_SEPARATOR,$name);
        require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR .  $name . '.php';
    }
});

use Wechat\Logic\CustomerService\Message;
use WeChat\Logic\CustomerService\Account;
use WeChat\Logic\Material\Material;
use WeChat\Logic\Member\User;
use WeChat\Logic\Member\Tags;
use WeChat\Logic\Pay\Refund;
use WeChat\Logic\Pay\Query;
use WeChat\Logic\Pay\Pay;
use WeChat\Logic\Ticket;
use WeChat\Logic\Qrcode;
use WeChat\Logic\Oauth;
use WeChat\Logic\Token;
use WeChat\Logic\Menu;
use WeChat\Logic\Test;

try{
    $res = (new Material())->upload('a.png');
}catch (\WeChat\Exception\MaterialException $e){
    echo $e->getMessage();
}
exit;
/**
 * 微信退款申请
 */
try{
    /*$data = [
        'out_trade_no' => '',   //微信订单号
        'total_free' => '',     //标价金额
        'refund_fee' => '',     //退款金额
        'out_refund_no' => '',  //商户退款单号
    ];
    $res = (new Refund())->refund($data);*/

    //查询退款
    $data = [
        'out_trade_no' => '',
    ];
    $res = (new Refund())->refundquery($data);

}catch (\WeChat\Exception\PayException $e){
    echo $e->getMessage();
}
exit;
/**
 * 微信各场景支付
 * 支付未经过测试
 */

try{
    $data = [
        'openid' => '',
        'trade_type' => 'JSAPI',
        'total_fee' => 100.00,//（单位：元）,
        'out_trade_no' => '',
        'body' => '',
        'spbill_create_ip' => ''
    ];
    $res = (new Pay())->unifiedorder($data);

    //查询订单
    $data = [
        'out_trade_no' => '',
    ];
    $res = (new Query())->query($data);
    //关闭订单
    $res = (new Pay())->closeorder($data);
}catch(\WeChat\Exception\PayException $e){
    echo $e->getMessage();
}
exit;


/**
 * 创建用户标签
 */
try{
//新增标签
    $res = (new Tags())->create('asdasd');

//修改标签
    $r = (new Tags)->update(101,'测试修改');

//获取标签
    $re = (new Tags())->get();

//删除标签
    $a = (new Tags)->del(102);

//获取标签下的所有用户
   $res1 = (new Tags())->getUserUnderTag(102);

//为用户批量设置标签
    $res = (new Tags())->putTagForUsers(['oSEbI1Xhf5QmTXdRtKSs4QPG7aKE'],102);

//批量取消用户标签
    $er = (new Tags)->cancelTagForUsers(['oSEbI1Xhf5QmTXdRtKSs4QPG7aKE'],102);

}catch (\WeChat\Exception\TagsException $e){
    echo $e->getMessage();
}
exit;
/**
 * 用户相关
 */
try{
    //获取用户基本信息
    $user = (new User())->getUserInfo('oSEbI1Xhf5QmTXdRtKSs4QPG7aKE');

    //批量获取用户基本信息
    $data = [['openid' => 'oSEbI1Xhf5QmTXdRtKSs4QPG7aKE','lang'=>'zh_CN']];
    $user_list = (new User())->getUsersInfo($data);

    //获取用户列表
    $ulist = (new User)->getUserList();
    //黑名单列表
    $black = (new User)->blackList();
    var_dump($black);
}catch (\WeChat\Exception\UserException $e){
    echo $e->getMessage();
}
exit;
/**
 * 生成二维码
 */
try{
    $result = (new Qrcode())->createQrcode('QR_SCENE','456789','new World');
    $qrcode = (new Qrcode())->getQrcodeByTicket($result['ticket']);
    //$qrcode就是二维码可写入图片格式文件后直接访问
    file_put_contents('a.png',$qrcode);
}catch (\WeChat\Exception\QrcodeException $e){
    echo $e->getMessage();
}
exit;

/**
 * 添加客服账号
 */
try{
//    获取所有客服账号
    $res = (new Account())->getAccounts();
}catch(\WeChat\Exception\AccountException $e){
    echo $e->getMessage();
}
exit;

/**
 * 发送客服消息
 */
try{
    $openid = 'oSEbI1Xhf5QmTXdRtKSs4QPG7aKE';
    $res = (new Message())->sendMessage($openid,'text','test');
    var_dump($res);
}catch (\WeChat\Exception\MessageException $e){
    echo $e->getMessage();
}
exit;
/**
 * 添加菜单
 */
try{
    $str = '';
    $menus = (new Menu)->build($str);
    exit(json_encode($menus));
//    (new Menu())->build($str);
}catch (\WeChat\Exception\MenuException $e){
    echo $e->getMessage();
}catch (\Exception $e){
    echo $e->getMessage();
}
exit();

/**
 * 网页授权相关
 */

try{
    //跳转授权
    (new Oauth())->getAuth();
    //根据code换取openid和access_token
    $code = '';

    $arr = (new Oauth())->oauthCallBack($code);

    $openid = $arr['openid'];
    $access_token = $arr['access_token'];
    $user = (new Oauth())->getUserInfo($openid,$access_token);

}catch(\WeChat\Exception\UserException $e){
    echo $e->getMessage();
}
exit;
/**
 * 获取js_ticket和签名
 */
try{
    var_dump($js_sign = (new Ticket())->buildSign());
    echo $js_ticket = (new Ticket)->getTicket();
}catch(\WeChat\Exception\TicketException $e){
    echo $e->getMessage();
}
exit;

/**
 * 获取access_token
 */
try{
//    $config = ['appid' => '123123','appsecret'=>'ec398fa70d76fbb029650582325822e3','access_token_storage'=>'wxinfo/access_token.php'];
    $str = (new Token($config))->getAccessToken();
    echo $str;
}catch (\WeChat\Exception\AccessTokenException $e){
    echo $e->getMessage();
}
exit;