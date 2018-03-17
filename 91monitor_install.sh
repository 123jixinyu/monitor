#!/bin/bash

while :
do
    read -p "请确定当前目录为项目根目录，是否开始安装91monitor [Y/y/N/n]:" choice
    if [[ $choice != 'Y' && $choice != 'y' && $choice != 'N' && $choice != 'n' ]]; then 
	echo '输入错误，请输入[Y/y/N/n]'
	continue;
    fi	

    if [[ $choice == 'Y'  ||  $choice == "y" ]]; then
	break;
    else
	echo '已取消安装';
    	exit;
    fi 
done
echo '开始检测环境';
if test -e './public' -a -e './composer.json' ; then
    echo 'OK........当前目录为根目录';
else
    echo 'FAIL......当前目录非根目录,请确保已经克隆代码：git clone https://github.com/123jixinyu/monitor.git';
    exit;
fi

if command -v composer >/dev/null 2>&1 ; then
    echo 'OK........composer已安装';
else
    echo 'FAIL......请安装composer: http://docs.phpcomposer.com/01-basic-usage.html';
    exit;
fi


if command -v php >/dev/null 2>&1 ; then
    echo 'OK........php已安装';
else
    echo 'FAIL......请安装php: http://php.net/';
    exit;
fi

if command -v git >/dev/null 2>&1 ; then
    echo 'OK........git已安装';
else
    echo 'FAIL......请安装git';
    exit;
fi

phpversion=`php -r "echo (phpversion() -'5.5.9')>0;"`
if [ $phpversion > 0 ]; then
    echo 'OK........php版本大于5.5.9';
else
    echo 'FAIL......请确保当前php版本大于5.5.9';
    exit;
fi 

if [[ `php -m | grep pdo_mysql` &&  `php -m | grep openssl` && `php -m | grep mbstring` ]]; then
    echo 'OK........必要扩展已安装 pdo_mysql,openssl,mbstring';
else
    echo 'FAIL......请确认是否安装了必要扩展：pdo_mysql,openssl,mbstring';
fi

if test -e '.env' ; then 
    echo 'OK........env文件已存在'
else
    echo 'FAIL......您尚未建立env文件，请参考文档：https://github.com/123jixinyu/monitor';
    exit;
fi


if test -e 'vendor' ; then
    echo 'OK........你已安装了库文件'
else
    echo '开始安装类库'
    `composer install --ignore-platform-reqs`
fi

if [ `grep '^APP_KEY=.\{5,\}' .env` ] ;then
    echo 'OK........你已配置了密钥'
else
    echo '开始生成密钥：php artisan key:generate'
    `php artisan key:generate > /dev/null 2&1`
fi

echo '开始生成数据库表结构: php artisan migrate'
`php artisan migrate`

echo '开始生成基础数据: php artisan db:seed'
`composer dump-autoload`
`php artisan db:seed`


