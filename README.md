# 欢迎使用 vps-inventory-monitoring 监控系统

------

vps-inventory-monitoring是一个VPS库存监控系统 — 实时把握库存信息，折腾起来稍微有点费劲，但使用起来体验还是不错的，支持微信/TG频道推送。 **实时把握库存信息**   
[Demo](https://vps.57hs.cn)

vps-inventory-monitoring
原作者地址github：https://github.com/546669204/vps-inventory-monitoring  
（最后更新于19年2月，原作者的思维还是非常超前的这个项目25年了依然能打）

nodeloc大佬23年12月1日根据这个建议改进https://github.com/546669204/vps-inventory-monitoring/issues/3

改进后地址github：https://github.com/nodeloc/vps-inventory-monitoring

然后就是25年1月中旬，我这个小白全网到处抄了一下用gpt修修改改弄了个搬瓦工aff监控

这个是我自己个人使用的，都是前人种树后人乘凉感谢各位大佬~~

## 安装指南

宝塔面板、nginx、、PHP7.3、MySQL5.5

如果要使用docker安装，请安装好docker管理器和docker-compose

docker-compose安装命令：pip install docker–compose

新建好网站、添加好域名

## 常规部署：
下载：https://github.com/iiidcc/vps-inventory-monitoring/archive/refs/heads/main.zip

利用宝塔面板的远程下载，把源码下载到网站根目录，之后解压把所有的文件复制到网站根目录。

创建数据库导入数据库文件mysql.sql

修改数据库配置文件  [网站根目录]/app/database.example.php [需要重命名为database.php]

在网站设置中把网站的运行目录修改为public

网站设置中把伪静态设置为thinkphp

编辑app/index/config.php文件修改定时时间、域名等。然后打开域名访问即可。

这样已经可以打开域名访问了，但是需要自动检测vps服务商的产品更新是否有货等等，所以需要设置自动检测。

## 库存监控定时检测
VPS-Inventory-Monitoring提供cron+http 、php think vpstest 、go 代码编译运行三种运行监控的方式

推荐使用最简单的方法：php think vpstest 。

绍两种监控方法方法cron+http 、php think vpstestj如下：

第一种cron+http：定时任务设置
点击宝塔左侧定时计划，任务类型选择URL访问，时间设置每30分钟，URL：https://你的域名/index/index/test

第二种php think vpstestj：运行验证程序，间隔时间去app/index/config.php修改
screen -S vpstest //创建screen
cd [网站根目录]
php think VpsTest
CTRL A D //退出screen

重新进入查看可使用

screen -r vpstest


## Docker 

1.安装docker  
2.git clone https://github.com/iiidcc/vps-inventory-monitoring.git  
3.cd vps-inventory-monitoring  
4.docker-compose up -d  
5.访问 :7780


如有问题 请尝试
>docker-compose down  
docker-compose build  
docker-compose up -d 

docker的方式部署非常简单，复制命令回车运行即可。

-----

## 常见问题

1.添加页面出现404错误  ==> 设置Url ReWrite  

2.添加后不会检测       ==> 修改数据库xm_index的status为1(1视为通过审核)  

3.添加权限管理         ==> app/index/config.php    

4.设置后台运行验证程序  ==> 
>screen -S vpstest  //创建screen  
cd [网站根目录]  
php think VpsTest  
CTRL A D //退出screen  
然后关闭ssh即可
重新进入查看可使用  
screen -r vpstest

5.管理员设置
去路径/app/index/view/index编辑index.html文件然后删除一下首页注册的那段代码的注释。
接着注册一个账号后，去数据库找到xm_user表找到自己注册账户id，并编辑app/index/config.php文件，将adduid改成自己id。这样你新注册的账号就变为了管理员。

6.添加vps
你的域名/index/index/edit

----
## 函数说明
 - $curl["Code"] 返回状态码  
 - $curl["RequestHeader"] 请求头  
 - $curl["ResponseHeader"] 返回头  
 - $str 返回源代码
 - $value["stock"] 原库存状态
### 演示检测函数
```
if ($curl["Code"] != 200){ //首先判断状态码
    return false;
}
if (strpos($str,"MineCloud")==false){ //检测是否正常打开有无公司名字之类关键词
    return $value["stock"]; //返回原库存状态
}
if (strpos($str,"缺货中")!==false){ //检测是否含有缺货关键词
    return false; 
}
return true;
```
搬瓦工：
```
if ($curl["Code"] != 200){ 
    return false;
}
if (strpos($str,"Bandwagon")==false){ 
    return $value["stock"];
}
if (strpos($str,"Out of Stock")!==false){ 
    return false; 
}
return true;
```
---
## 更新日志
2019-02-25  
新增docker安装方式

2018-08-27  
更新thinkphp内核版本为5.0.20优化php7执行效率 感谢[@Blake-Bill](https://github.com/Blake-Bill)  

2018-06-01  
增加注册验证码  
增加多线程监测 更快的速度  

2018-04-07  
更新go_curl函数返回更多可用信息
添加调试功能

2018-03-23   
更新Telegram推送支持 频道推送和私人定制推送  
频道推送需后台设置sckey和频道链接  
配置文件 app/index/config.php    

2018-03-19  
更新命令行方式验证

2018-03-18  
根据[#3](https://github.com/546669204/vps-inventory-monitoring/issues/3)进行改进

1.个性化的产品来货推送  
2.跳转链接的简化  
3.增加筛选功能  
4.增加添加权限控制
