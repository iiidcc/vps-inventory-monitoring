## vpstest会话守护使用方法

修改路径 /app/index/daemon下的run_php_think_VpsTest.sh文件

将  WEB_ROOT="/www/wwwroot/vps.57hs.cn"

这一段的vps.57hs.cn改为你网站实际根目录的文件夹名即可 注意不是子目录什么的就是网站根目录

其他不用修改。

给这个文件一个执行权限
命令行直接复制粘贴
chmod +x /www/wwwroot/vps.57hs.cn/app/index/daemon/run_php_think_VpsTest.sh


## 宝塔计划任务设置

打开宝塔面板 -> 计划任务 -> 添加任务。

任务类型 -> Shell脚本 -> 名称随意填写

执行周期 -> 每N分钟 -> 1分钟

脚本内容：
bash /www/wwwroot/vps.57hs.cn/app/index/daemon/run_php_think_VpsTest.sh