<?php
return [
    'app' => [
        'addpass' => false, //公共添加 true为指定用户可添加 false 为所有人都可以添加 不过需要审核
        'adduid' => "11", //添加功能用户UID [,]分隔 如 1,3,5
        'testtick' => 5, //命令行定时执行时间 秒为单位 60*60 即3600秒
        'host' => 'https://vps.57hs.cn', //这里修改成自己的域名
        'tgchannel' => '', //telegram 频道地址
        'tgchannelsckey' => ' ', // 邀请https://t.me/nodeloc_bot进入频道 设置为管理员 然后在频道发送 /start 获取sckey 填入
        'bot_token' => '',
        'testdebug' => false, //是否输出调试信息到文件
        'captcha' => false,//启动验证码
    ],
];
