# smtctools
智慧同城工具箱

##Jwt

```
JSON WEB TOKEN
来自https://github.com/F21/jwt
```

##Sensitive.php
```
    /**
     * 检查是否包含敏感词
     * @param $content
     * @return bool
     */
    public function checkSensiveExist($content)
    {
        $checkedWords = "关键词,关键词1";
        $instance = Sensitive::getInstance();
        $instance->addSensitiveWords($checkedWords);
        return $instance->isExist($content);
    }

    /**
     * 替换敏感词
     * @param $content
     * @return string
     */
    public function checkSensiveReplace($content)
    {
        $replaceWords = "关键词,关键词1";
        $instance = Sensitive::getInstance();
        $instance->addSensitiveWords($replaceWords);
        return $instance->replaceWords($content);
    }
```


##DmPay.php
```

$config = [
    //签名方式,默认为RSA2(RSA2048)
    'sign_type' => "RSA2",
    //支付宝公钥
    'alipay_public_key' => "",
    //商户私钥
    'merchant_private_key' => "",
    //编码格式
    'charset' => "UTF-8",
    //支付宝网关
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
    //应用ID
    'app_id' => "",
    //异步通知地址,只有扫码支付预下单可用
    'notify_url' => "http://www.baidu.com",
    //最大查询重试次数
    'MaxQueryRetry' => "10",
    //查询间隔
    'QueryDuration' => "3"
];

try {
    $params = new PayParams();
    $params->addGoods('apple-01', 'iphone', 1, 300);
    $params->setOutTradeNo(time());
    $params->setTotalAmount(1);
    $params->setSubject("洗车服务");
    $params->setBody("洗车费用30元");
    $content = $params->getBizContent();
    $pay = new DmPay($config);
    $arr = $pay->execute($content);
    print_r($arr);
} catch (\Exception $e) {
    var_dump($e->getMessage());
}
```
