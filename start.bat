:: 找本机IP
@echo off
for /f "tokens=4" %%a in ('route print^|findstr 0.0.0.0.*0.0.0.0') do (
 set IP=%%a
)

:: 将网址进行复制
echo http://%IP%:8000 |clip 

:: 启动服务
php -c ./ -S %IP%:8000
