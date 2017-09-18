# 91 Monitor
示例站点:www.91monitor.com (不安装的情况下，无法使用管理员账号登录，只能注册后使用)

**91 monitor**是一款专门为监控服务器状态的PHP开源系统。
特点概述：支持监听各种服务器端口，以及web站点 , 并且支持终端控制
### 环境
- PHP版本5.5.9以上（扩展：OpenSSL PHP Extension,PDO PHP Extension,Mbstring PHP Extension）
- Linux服务器
- Composer 工具
- MySQL 5.6以上

### 安装
- 克隆项目(确保有github账号，并且本地公钥已经在github账户，否则无法克隆，你也可以选择https方式的下载)

`
git clone git@github.com:123jixinyu/monitor.git
`

- 安装laravel依赖
进入项目根目录，例如我的项目目录为/home/wwwroot/monitor

`
cd /home/wwwroot/monitor  
`

`
composer install
`
- 在根目录新建.env文件并将.env.example文件内容复制到.env,并修改.env配置，建立相应数据库

`
cd /home/wwwroot/monitor
`

`
cp .env.example .env
`
建立数据库（例如新建一个名为monitor的数据库）
修改.env配置
> 配置数据库连接，分别是数据库类型、数据库地址、数据库名、数据库用户名、数据库密码。
>	
	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_DATABASE=monitor
	DB_USERNAME=root
	DB_PASSWORD=123456
>
>配置邮箱,分别是发件服务器类型，发件地址，发件端口，发件邮箱，发件邮箱密码，发件邮件加密类型(以下是QQ邮箱示例)
>  
	MAIL_DRIVER=smtp
	MAIL_HOST=smtp.qq.com
	MAIL_PORT=465
	MAIL_USERNAME=848280118@qq.com
	MAIL_PASSWORD=123456
	MAIL_ENCRYPTION=ssl

>调试模式,true为开启，false为关闭
>   
	APP_DEBUG=true



- 到项目根目录运行artisan命令初始化表以及生成应用key

`
cd /home/wwwroot/monitor
`

`
php artisan migrate
`

`
php artisan key:generate
`

- 初始化基本数据

`
cd /home/wwwroot/monitor
`

`
composer dump-autoload
`

`
php artisan db:seed
`

- 配置nginx映射到public 目录下，并且设置storage以及bootstrap目录读写权限。在public 目录下创建名为uploads的文件夹并赋予写入权限

>	 
		server
   		 {
        	listen 80;
        	server_name www.91monitor.com;
        	index index.html index.htm index.php;
        	root  /home/wwwroot/monitor/public;
			.....

- 将/usr/bin/php /home/wwwroot/monitor/artisan schedule:run 加到crontab中去,其中/home/wwwroot/monitor是你的项目目录。（/usr/bin/php是我的服务器php可执行文件路径，这里你们写自己的路径）

`* * * * * /usr/bin/php /home/wwwroot/monitor/artisan schedule:run`

- 安装后默认登录账户为admin@admin.com 密码123456（不安装情况下无法使用该账号）


## 反馈与建议
- 加QQ群:630418030  （备注填写:91monitor）
- 邮箱：<848280118@qq.com>

---------
感谢阅读这份帮助文档，欢迎加入群，大家可以一起交流沟通
