#### 微信部分接口

    
##### 使用前准备
    若不想每次实例化之前都要传入配置项可以把src/WrChat/Config/config.default.php
    修改为cofig.php并写入相应的参数。
    
    当然你也可以在每次实例化之前定义需要的配置并传入类中，
    eg:
        $config = [
            'appid' => '123123',
            'appsecret => 'zxczxczxczxczxc'
        ];
        
        $access_token  = (new Token($config))->getAccessToken();
    注：access_token以及js_ticket暂时只支持I/O操作存储
##### 使用    
    具体用法见test/index.php
    
###### 反馈与建议
    邮箱：janmas@qq.com