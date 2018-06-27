#!/bin/bash

echo '开始检测环境';


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

phpversion=`php -r "echo strnatcmp(phpversion(),'5.5.9') >= 0;"`
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

if test -e './public' -a -e './composer.json' ; then
    echo 'OK........当前目录为根目录';
else
    echo 'FAIL......目录非法'
fi


while :
do
    read -p "是否开始安装91monitor [Y/y/N/n]:" choice
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

if test -e '.env' ; then 
    echo 'OK........env文件已存在'
else
    if test -e '.env.example'; then
        `cp ./.env.example ./.env`;
        echo "OK........已将.env.example文件复制到.env"
    else
        echo 'FAIL.......env.example文件不存在'
        exit
    fi
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

config_env(){
    key=$1;
    desc=$2;
    read -p $desc value
    
    if [[ $value = 'N' ]]; then
        echo '你已取消安装！';
        exit
    fi
    if [[ $value != "" ]]; then
         sed -i "" "s/$key=\(.*\)/$key=$value/g" ".env"
    fi
}
config_env  "DB_HOST" "请输入配置数据库地址(输入N终端配置):"
config_env  "DB_DATABASE" "请输入数据库名:"
config_env  "DB_USERNAME" "请输入数据库用户名:"
config_env  "DB_PASSWORD" "请输入数据库密码:"
config_env  "MAIL_DRIVER" "请输入邮箱类型（例如：smtp）:"
config_env  "MAIL_HOST" "请输入邮箱服务器地址(例如：mailtrap.io):"
config_env  "MAIL_PORT" "请输入邮箱端口（例如：2525）:"
config_env  "MAIL_USERNAME" "请输入邮箱:"
config_env  "MAIL_PASSWORD" "请输入邮箱密码:"
config_env  "MAIL_ENCRYPTION" "请输入邮箱加密方式(例如：ssl,如果没有则直接回车):"


echo '开始生成数据库表结构: php artisan migrate'
`php artisan migrate`
echo '开始生成基础数据: php artisan db:seed'
`composer dump-autoload`
`php artisan db:seed`


