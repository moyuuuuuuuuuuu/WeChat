#### 微信部分接口

    
##### 使用前准备
    如果你是在框架中使用该插件请先把/Config/config.default.php复制到框架的配置文件夹中并改名为janas-wechatapi.php
    
    若不是在框架中请把/Config/config.default.php改名为config.php
    
    当然你也可以在每次实例化之前定义需要的配置并传入类中，
    eg:
        $config = [
            'appid' => '123123',
            'appsecret => 'zxczxczxczxczxc'
        ];
        
        $access_token  = (new Token($config))->getAccessToken();
    注：access_token以及js_ticket暂时只支持文件存储
##### 使用    
    具体用法见test/index.php
    
###### 反馈与建议
    邮箱：janmas@qq.com