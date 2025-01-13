#!/bin/bash

SCREEN_NAME="vpstest"
WEB_ROOT="/www/wwwroot/vps.57hs.cn"
PHP_CMD="php think VpsTest"

# 检查是否有名为 'vpstest' 的 screen 会话
if screen -list | grep -q "$SCREEN_NAME"; then
    echo "VPS库存监控会话 '$SCREEN_NAME' 已经运行了,无需进行任何操作."
else
    echo "VPS库存监控会话 '$SCREEN_NAME' 未运行，立即重新启动."
    
    # 创建新的 screen 会话并运行程序
    screen -S "$SCREEN_NAME" -dm bash -c "
        cd $WEB_ROOT && $PHP_CMD
        exec bash
    "
    echo "VPS库存监控会话 '$SCREEN_NAME'启动成功,正在后台进程守护中."
fi