---

# 🎉 欢迎使用 **VPS库存监控系统** 🎉

---

## 🧐 关于 **VPS库存监控系统**

**VPS库存监控系统** 是一个实时监控 **VPS** 库存信息的工具，让您可以时刻掌握 VPS 库存状态。虽然搭建过程稍微复杂一些，但使用起来非常便捷。系统支持 **微信** 和 **Telegram** 频道推送，确保您第一时间收到库存变化通知。

**实时把握库存信息** ➡️ [Demo](https://vps.57hs.cn)

- **项目原地址**：[vps-inventory-monitoring](https://github.com/546669204/vps-inventory-monitoring)  
  （更新于 2019年2月，原作者的思想超前，这个项目即使到 2025 年依然有强大生命力！）

- **改进版地址**：[vps-inventory-monitoring by nodeloc](https://github.com/nodeloc/vps-inventory-monitoring)  
  —— 由 **nodeloc** 大佬在 2023年12月 进行改进，感谢其为社区贡献！  
  （**NodeLoc** 是一个提供服务器相关服务的站点，大家都知道！）

---

## 📖 安装指南

本监控系统支持在 **宝塔面板**、**Docker** 等环境下运行，以下是安装步骤。

### 环境要求
- **宝塔面板**、**nginx**  
- **PHP 7.3**  
- **MySQL 5.5**

### 安装步骤

#### 1. 常规部署

1. **下载源码**  
   下载源码： [vps-inventory-monitoring.zip](https://github.com/iiidcc/vps-inventory-monitoring/archive/refs/heads/main.zip)

2. **上传并解压**  
   使用宝塔面板的远程下载功能，将源码文件下载到网站根目录，解压后复制到该目录。

3. **创建并导入数据库**  
   创建数据库并导入 `mysql.sql` 文件。

4. **修改数据库配置**  
   修改数据库配置文件：  
   `app/database.example.php` → `app/database.php`

5. **修改网站根目录**  
   在宝塔面板网站设置中，将运行目录设置为 `public`。

6. **设置伪静态规则**  
   将伪静态设置为 **thinkphp**。

7. **修改配置**  
   编辑 `app/index/config.php` 文件，修改定时检查间隔时间、域名等相关配置。

8. **访问测试**  
   完成上述步骤后，使用浏览器打开网站，检查是否能正常访问。

#### 2. 设置自动库存检测

为了实时监控库存情况，我们需要设置定时任务来定期检查：

**推荐使用：** `php think vpstest` 命令。

**具体步骤：**

- 使用 `cron+http` 或 `php think vpstest` 设置定时任务。

##### 监控方法 1：通过 **cron+http** 设置定时任务

1. 打开宝塔面板，进入 **计划任务** 页面。
2. 选择任务类型为 **URL访问**。
3. 设置定时执行 **每30分钟**，URL 地址为：  
   `https://你的域名/index/index/test`

##### 监控方法 2：通过 **php think vpstest** 设置定时任务

1. 创建 `screen` 会话：
   ```bash
   screen -S vpstest
   ```

2. 切换到网站根目录：
   ```bash
   cd [网站根目录]
   ```

3. 运行命令：
   ```bash
   php think VpsTest
   ```

4. 按下 **CTRL + A**，然后按 **D** 退出 `screen`。

5. 重新进入 `screen` 查看进程：
   ```bash
   screen -r vpstest
   ```

---

## 🛠️ **vpstest 会话守护使用方法**

为了确保 `VpsTest` 命令在后台一直运行，可以使用以下脚本来守护这个进程。

### 步骤：

1. **修改路径**  
   修改脚本 `/app/index/daemon/run_php_think_VpsTest.sh` 中的 `WEB_ROOT`，将其设置为实际的 **网站根目录**，如：  
   `WEB_ROOT="/www/wwwroot/vps.57hs.cn"`

2. **给脚本赋予执行权限：**  
   ```bash
   chmod +x /www/wwwroot/vps.57hs.cn/app/index/daemon/run_php_think_VpsTest.sh
   ```

3. **设置宝塔计划任务**

   - 打开宝塔面板 -> **计划任务** -> **添加任务**。
   - 任务类型选择 **Shell脚本**。
   - 设置执行周期为 **每1分钟**，然后填入脚本路径：
     ```bash
     bash /www/wwwroot/vps.57hs.cn/app/index/daemon/run_php_think_VpsTest.sh
     ```

---

## 📦 **Docker 部署方式**

1. 安装 **Docker**：
   如果你使用 Docker，按照以下步骤进行部署。

2. 克隆仓库：
   ```bash
   git clone https://github.com/iiidcc/vps-inventory-monitoring.git
   ```

3. 进入目录：
   ```bash
   cd vps-inventory-monitoring
   ```

4. 启动 Docker Compose：
   ```bash
   docker-compose up -d
   ```

5. 访问 Docker 容器：  
   访问 `http://<服务器IP>:7780` 即可看到运行的系统。

---

## ❓ **常见问题解答**

1. **添加页面出现404错误**  
   - **解决方法：** 设置 **URL ReWrite**。

2. **添加后不会检测**  
   - **解决方法：** 修改数据库中的 `xm_index` 表的 `status` 字段为 `1`（表示通过审核）。

3. **权限管理**  
   - **解决方法：** 修改 `app/index/config.php` 文件进行设置。

4. **后台运行验证程序**  
   - **步骤：**
     ```bash
     screen -S vpstest   # 创建 screen 会话
     cd [网站根目录]    # 切换到网站根目录
     php think VpsTest   # 运行命令
     CTRL + A, D         # 退出 screen 会话
     ```
     - 重新进入 `screen` 会话：
     ```bash
     screen -r vpstest
     ```

5. **管理员设置**  
   - 访问 `app/index/view/index`，编辑 `index.html` 文件，删除注册部分的注释。
   - 在数据库 `xm_user` 表中找到自己注册的账号 ID，然后编辑 `app/index/config.php` 文件，设置 `adduid` 为该 ID，将新注册的账号设为管理员。

6. **添加 VPS**  
   - 访问 `http://你的域名/index/index/edit` 添加 VPS。

---

## 📝 **函数说明**

- `$curl["Code"]` - 返回状态码  
- `$curl["RequestHeader"]` - 请求头  
- `$curl["ResponseHeader"]` - 返回头  
- `$str` - 返回源代码  
- `$value["stock"]` - 原库存状态  

### 🧪 演示检测函数
```php
if ($curl["Code"] != 200) {  // 首先判断状态码
    return false;
}
if (strpos($str, "MineCloud") === false) {  // 检测是否正常打开
    return $value["stock"];  // 返回原库存状态
}
if (strpos($str, "缺货中") !== false) {  // 检测是否含有缺货关键词
    return false; 
}
return true;
```

### 🌐 搬瓦工检测
```php
if ($curl["Code"] != 200) { 
    return false;
}
if (strpos($str, "Bandwagon") === false) { 
    return $value["stock"];
}
if (strpos($str, "Out of Stock") !== false) { 
    return false; 
}
return true;
```

---

## 📅 **更新日志**

- **2019-02-25**  
  新增 Docker 安装方式。

- **2018-08-27**  
  更新 ThinkPHP 内核为 5.0.20，优化 PHP 7 执行效率。

- **2018-06-01**  
  增加注册验证码，支持多线程监测，提高检测速度。

- **2018-04-07**  
  更新 `go_curl` 函数，返回更多可用信息并新增调试功能。

- **2018-03-23**  
  更新 Telegram 推送支持，支持频道推送和私人定制推送。  

- **2018-03-19**  
  更新命令行方式验证。

- **2018-03-18**  
  基于[#3](https://github.com/546669204/v

ps-inventory-monitoring/issues/3)进行改进。

---

希望这篇文档能够帮助您顺利安装和使用 **VPS库存监控系统**！如有任何问题，欢迎随时联系！😊