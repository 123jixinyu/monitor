# 91 Monitor
示例站点:www.91monitor.com

**91 monitor**是一款专门为监控服务器状态的PHP开源系统。
特点概述：支持监听各种服务器端口，以及web站点 , 并且支持终端控制

### 安装
1. 克隆项目
>
`
git clone git@github.com:123jixinyu/monitor.git
`
>
2. 安装laravel依赖
>
`
composer install
`
>
3. 拷贝 .env.example到.env,并修改.env配置，建立相应数据库
>
4. 运行artisan命令初始化表以及生成应用key
>
`
php artisan migrate
`
<br/>
`
php artisan key:generate
`
>
5. 初始化基本数据
>
`
composer dump-autoload
`
<br/>
`
php artisan db:seed
`
>
6. 配置nginx映射到public 目录下，并且设置storage以及bootstrap目录权限。在public 目录下创建名为uploads的文件夹并赋予写入权限
>
7. 将/usr/bin/php /home/wwwroot/monitor/artisan schedule:run 加到crontab中去,其中/home/wwwroot/monitor是你的项目目录。
>
`* * * * * /usr/bin/php /home/wwwroot/monitor/artisan schedule:run`
>
8. 默认登录账户为admin@admin.com 密码123456
>

## 反馈与建议
- QQ群: 630418030
- 邮箱：<848280118@qq.com>

---------
感谢阅读这份帮助文档，欢迎加入群，大家可以一起交流沟通


