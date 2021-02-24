<?php
define('XUANDB',C('db_name'));
define('NOWIP','http://120.78.208.218');
define('XUANIP','http://api.ymxte.com');
define('XUANXM','http://api.ymxte.com');
define('XUANIMG','http://api.ymxte.com');
define('ERRORNUM','5');//错误密码次数;
return array(



    //'配置项'=>'配置值'
    'URL_MODEL' => '2',
    'db_type'  => 'mysql',
    'db_user'  => 'root',
    'db_pwd'   => 'yfm123',
    'db_host'  => 'localhost',
    'db_port'  => '3306',
    'db_prefix'  => 'lyx_',
    'db_name'  => 'yimei',
    'db_charset'=>    'utf8mb4',
    'DEFAULT_FILTER' => 'htmlspecialchars',

    /*    'ALIPAY_CONFIG'          => array(
            'partner'            => '2016112803472455', // partner 从支付宝商户版个人中心获取
            //'seller_email'       => '0755@tm0755.com', // email 从支付宝商户版个人中心获取
            'sign_type'          => strtoupper(trim('RSA')), // 可选md5  和 RSA
            'input_charset'      => strtolower('utf-8'), // 编码 (固定值不用改)
            'transport'          => 'http', // 协议  (固定值不用改)
            //'cacert'             => VENDOR_PATH.'Alipay/cacert.pem',  // cacert.pem存放的位置 (固定值不用改)
            'cacert'             => getcwd().'\\cacert.pem', // cacert.pem存放的位置 (固定值不用改)
            'service'         => 'http://47.92.70.33/yousong/Home/alipay/notifyurl', // 异步接收支付状态通知的链接
            //'return_url'         => 'http://baijunyao.com/Api/Alipay/alipay_return', // 页面跳转 同步通知 页面路径 支付宝处理完请求后,当前页面自 动跳转到商户网站里指定页面的 http 路径。 (扫码支付专用)
            //'show_url'           => 'http://baijunyao.com/User/Order/index', // 商品展示网址,收银台页面上,商品展示的超链接。 (扫码支付专用)
            'private_key'   => '-----BEGIN RSA PRIVATE KEY-----
    MIICXAIBAAKBgQCsMF5MH+8HzzELrErpx+jgcn6aJJYS8C/j4CnfqNKNbzlNaQZiz0hxzrHFNDXOXmhxhNqqAAYWw9L8pn6UpW86nDtH5g/CelNLpiXYLWqnLIXEx2n5JAuYS9DyBvmu4+fCUoFq5osJpO/e8InwnG1U8kGBC0dOJNm25g1em+/gQwIDAQABAoGARBndMb5vi2cmvrcl6dBnCl4+NDjEIjlct4OxDAR5Qfb7cuJW5D2XKWvWY2iC31v3cu5YjWP1BIvupn4zEhdcJevmLcV2lhAsSIzBNHXLHeWkeFaKe6fTjuFujSK5OrLWm6i+DSvkRxSe1SAi0B2IT4PouN5h6JhcAdynVv8Q+gECQQDbltDv00ud3SN1ok+h1UWUgx6LFvzAR2M4r4kmbZH8aeT12hAksI1zwDDKAoJdpHgsx/8LyfQxTdIsfPPvwUUdAkEAyL1+v4RAbxXVdA1RpD1TNL9ZzQwC2I3wSOJfOZxBw0qFgbGRVeiFHJWrAAz1gs9e3PBfy2Shb2RxwV/d96Oc3wJAQn1UHooYJ2DCT+gpvJLbUrCxGuSG+6GiBZQBL0WWIpvd3CN/J9zdt+LF4yUHXFtkmAqmy2cHLbamoRnkswAOcQJBALQ4nJCo1Yhl28tjIccIX6ldmevjOrLdS7rDsaZxSQFh6Fa16rtsFAXDwVA8UjbsokhNblOWtvDhBtgLj6aCt5UCQDm4Fqj9EiORCRvuE5QyFvn6s8hDY4PPLgjYCI+Wpxw7Wy/EaGg1XNTDaZwEzeq4B5ru9hXjA/35Qt52COTJak4=
    -----END RSA PRIVATE KEY-----
    ', //移动端生成的私有key文件存放于服务器的 绝对路径 如果为MD5加密方式；此项可为空 (移动支付专用)
            'alipay_public_key'    => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB', //移动端生成的公共key文件存放于服务器的 绝对路径 如果为MD5加密方式；此项可为空 (移动支付专用)
        ),*/


    'ALIPAY_CONFIG' => array(
        'appid' =>'2019071665832736',//商户密钥
        'rsaPrivateKey' =>'MIIEpAIBAAKCAQEAzd0jqLNGO/B9TeCJsjo3Z6ZTPO1jIwVW8HEKRRFUTyP2urHLjyKKtRKI6+9QB3cocetwpxUlrk7Au/AB2jsbtGBov1iIdM/E4/ol7UXcYQfy29OtS5KV520BdFx2OGpPc/M8rqaIFkhKPzkOOhagov9+5Be3mBYTdD1kjUYnfRBo4qH4roJs+iXaRMgQi/okrhlXzg6Nvan2eVK/VQ1oYO2zdUBZ71hpJgq7WwDhSTWMahpMH9Z+WGGRvpePP3octnbLk5uR/2TmEJCFBn81bLlKSoaN7C1WZzlo17w4H/xptoBoDbvHjJGlXAKvScJ5CLK8vzaatvkE6oYkYwN98QIDAQABAoIBAFt2PYqNI84rmbBI60RpAwak6eRu35XGoIqw3kwzV87VfqARsAvG3/N06apRJ7A5a5m5bdJ93cDkslKNXnVTv4pTLxacQfwL90EGeWVCnZfqjSbHk/24gatRTc0h61BDQ0uTrMk3qcykj4ApXD3IodPpSboTI3mJjVkekZcGrTKtlLxeT6zJTErh9bQE9zy7H6Nl+fdFfjp2aCX0B+87nDCTwt0gv5AGvUjJP54BreOntJxO4Z7JKglM1vsyyppLBrgSCPMIRuenu+yV0aDg1yHLPpeBW1s6Ni32xCgAuQQnT6vmiPbHoPVju8MVFKQYge4CQA4yDiLzSNvv8MzfcxUCgYEA58DXRIOYSyPry1g5Bdy/Ur68JipLmwlMJOBqLBqQrsPMfYRSM+cylBYHoG9qOK51kBS1Q5o/u6PpxjhQfms6DPkc0T4xHxVn3QBv7kRWfuHVB7em9J8M9+WnGZVmsRBtUzG91nylp22HmGW6PI3auowspScUfIvfxKGk4loOYhcCgYEA42bjfQXLZO2IT89fMVrZQm8v64v5jS6xwq7F3WV8EhrSfbQOnJzxYDOx08oxhhnucx1rc8DeA2/s+441yD2cAmIybkZRWRMYQC2OiExOL1lzROok+EcAjAM4dADee3BN8t+drZfeZQHepsOdjKtQL3Xm2UxYIjiyQw+gnuGgzTcCgYEAjlcG4HHiy+PYIyCghVU7vVqgvOAlZ6eiONQM4eG/E1f29PCcfHx3uDR/oq5Lk1yzoDbXzhmjI2BdaP2Vks/Q677lUC7ehLTlAfwOw0z9wxh5ey/1PzGhCXDn3PLCC+aG/x4B9wa/x8GiEpwIcsC9ou4NTbJfBs/yNBcD6nJdeykCgYEAhCe8V2ExtkMRMAi9Msqwbpp9h+9+JR2Y8yJtHWCoPUFXSPSGdWAEKzt/wizEoUGawU28r7XLkMXQPNWFzgx4CS6WTl4RUqn1Mv8G0ZE+8ueSRN+qgpuI5tXMTiZfJe+7bUWltkgHWD5A7jvK6QB6hkqH0Ys7wQ+gEbcYSp2nvPsCgYBHiibwiBujJMKU8DH0L8ztljZ/A7iFpt7pm8wxvRih9NtQ/OFOvVCcPD3FcH63VNSyy97sJyo6V0WC0MhplJdASPHWOJuIqFEo6EIE25cXaUUdORKZBljT2qfqLoVIgyltRNAArGFTONedyAN7MtM2iJU15ciiLq17yGWcWa5ayQ==',//私钥
        'alipayrsaPublicKey'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoLvloSu/kGSpIT/367yEhmYFU0SJFH9r0skdrgYYcnTCkm1e1/goK+AojeAL9B+iju7O6kYRkMqnIRp4fZiMeUSgpisgLTpCtd+H+Eaj1stOx0AqH11G9c4Yg5KAJMCwDgWvVma1d87wT8JFfldi87S2TBykbrdA3XNcBtYp73Axyi8qways0UJ2TxXIRNkZY6x1lzTGMIF4yXLqonTS6HX62cCjvVMdHDBJT5oAy5k9NeoL7CKxuUGp7m3IHuLWk1BtBavdHx5m09u7LbdAsuE02z8RUF9KAV7n5PiqyzGVQhMZj/I5sIufgZGJtGRThOEfPGDdCjk8FVsmFNp8RQIDAQAB',//公钥
        'charset'=>strtolower('utf-8'),//编码
        'notify_url' =>'http://api.ymxte.com/Home/alipay/notifyurl',//回调地址(支付宝支付成功后回调修改订单状态的地址)
        'payment_type' =>1,//(固定值)
        'seller_id' =>'',//收款商家账号
        'sign_type' => 'RSA2',//签名方式
        'timestamp' =>date("Y-m-d H:i:s"),
        'version'   =>"1.0",//固定值
        'url'       => 'https://openapi.alipay.com/gateway.do',//固定值
        'method'    => 'alipay.trade.app.pay',//固定值
    ),

    define('WEB_HOST', 'http://api.ymxte.com'),
    /*微信支付配置*/
    'WxPayConf_pub'=>array(
        'APPID' => 'wx2ef72694c9c2be9e',
        'MCHID' => '1536455781',
        'KEY' => 'YIMEIRIIFUVJCLMGJFIR90212K3MCXTE',
        'APPSECRET' => '613f70e66cdf686d4b3d24eb68fba51f',
        'JS_API_CALL_URL' => WEB_HOST.'/Home/wxpay/js_api_call',
        'SSLCERT_PATH' => WEB_HOST.'/ThinkPHP/Library/Vendor/Weixinpay/cacert/apiclient_cert.pem',
        'SSLKEY_PATH' => WEB_HOST.'/ThinkPHP/Library/Vendor/Weixinpay/cacert/apiclient_key.pem',
        'NOTIFY_URL' =>  WEB_HOST.'/Home/wxpay/notify_url',
        'CURL_TIMEOUT' => 30
    ),

    /*融云配置*/
    'RongCloudConf'=>array(
        'key' => 'uwd1c0sxuqtr1',
        'secret' => 'UMhxy9kKEYrHM'
    )

);