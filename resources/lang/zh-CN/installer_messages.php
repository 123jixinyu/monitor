<?php

return [

    /**
     *
     * Shared translations.
     *
     */
    'title' => 'Laravel安装程序',
    'next' => '下一步',
    'finish' => '安装',
    'back' => '上一步',
    'forms' => [
        'errorTitle' => '发生错误:',
    ],


    /**
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'title' => '欢迎来到Laravel安装程序',
        'message' => '欢迎来到安装向导.',
        'templateTitle' => "欢迎",
        'next' => '下一步去检查所需扩展',
    ],


    /**
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'title' => '环境要求',
        'templateTitle' => '第一步 | 环境要求',
        'next' => '检查权限',
    ],


    /**
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'title' => '权限',
        'templateTitle' => '第二步 | 检查文件权限',
        'next' => '下一步去配置环境',
    ],


    /**
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'title' => '环境设置',
        'save' => '保存 .env',
        'success' => '.env 文件保存成功.',
        'errors' => '无法保存 .env 文件, 请手动创建它.',
        'menu' => [
            'templateTitle' => '第三步 | 设置运行环境',
            'title' => '环境设置',
            'desc' => '请选择使用何种方式来配置 <code>.env（laravel环境配置文件）</code> 文件.',
            'wizard-button' => '表单填写',
            'classic-button' => '文本编辑',
        ],
        'wizard' => [
            'templateTitle' => '第三步 | 环境设置 | 面板向导',
            'title' => '面板向导 <code>.env</code> ',
            'tabs' => [
                'environment' => '环境',
                'database' => '数据库',
                'application' => '应用'
            ],
            'form' => [
                'name_required' => '必须选择应用名称',
                'app_name_label' => '应用名称',
                'app_name_placeholder' => '应用名称',
                'app_environment_label' => '应用环境',
                'app_environment_label_local' => 'Local',
                'app_environment_label_developement' => 'Development',
                'app_environment_label_qa' => 'Qa',
                'app_environment_label_production' => 'Production',
                'app_environment_label_other' => 'Other',
                'app_environment_placeholder_other' => 'Enter your environment...',
                'app_debug_label' => 'App Debug',
                'app_debug_label_true' => 'True',
                'app_debug_label_false' => 'False',
                'app_log_level_label' => '应用日志等级',
                'app_log_level_label_debug' => 'debug',
                'app_log_level_label_info' => 'info',
                'app_log_level_label_notice' => 'notice',
                'app_log_level_label_warning' => 'warning',
                'app_log_level_label_error' => 'error',
                'app_log_level_label_critical' => 'critical',
                'app_log_level_label_alert' => 'alert',
                'app_log_level_label_emergency' => 'emergency',
                'app_url_label' => '应用地址',
                'app_url_placeholder' => '应用地址',
                'db_connection_label' => '数据库连接',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => '数据库地址',
                'db_host_placeholder' => '数据库地址',
                'db_port_label' => '数据库端口',
                'db_port_placeholder' => '数据库端口',
                'db_name_label' => '数据库名称',
                'db_name_placeholder' => '数据库名称',
                'db_username_label' => '数据库用户名',
                'db_username_placeholder' => '数据库用户名',
                'db_password_label' => '数据库密码',
                'db_password_placeholder' => '数据库密码',

                'app_tabs' => [
                    'more_info' => '更多',
                    'broadcasting_title' => '广播, 缓存, 会话session, &amp; Queue',
                    'broadcasting_label' => '数据库密码',
                    'broadcasting_placeholder' => '数据库密码',
                    'cache_label' => '缓存驱动',
                    'cache_placeholder' => '缓存驱动',
                    'session_label' => '会话session驱动',
                    'session_placeholder' => '会话session驱动',
                    'queue_label' => '队列驱动',
                    'queue_placeholder' => '队列驱动',
                    'redis_label' => 'Redis 驱动',
                    'redis_host' => 'Redis 地址',
                    'redis_password' => 'Redis 密码',
                    'redis_port' => 'Redis 端口',

                    'mail_label' => '邮件',
                    'mail_driver_label' => '邮件驱动',
                    'mail_driver_placeholder' => '邮件驱动',
                    'mail_host_label' => '邮件地址',
                    'mail_host_placeholder' => '邮件地址',
                    'mail_port_label' => '邮件服务器端口',
                    'mail_port_placeholder' => '邮件服务器端口',
                    'mail_username_label' => '邮箱用户名',
                    'mail_username_placeholder' => '邮箱用户名',
                    'mail_password_label' => '邮箱密码',
                    'mail_password_placeholder' => '邮箱密码',
                    'mail_encryption_label' => '邮箱加密',
                    'mail_encryption_placeholder' => '邮箱加密',

                    'pusher_label' => 'Pusher',
                    'pusher_app_id_label' => 'Pusher App Id',
                    'pusher_app_id_palceholder' => 'Pusher App Id',
                    'pusher_app_key_label' => 'Pusher App Key',
                    'pusher_app_key_palceholder' => 'Pusher App Key',
                    'pusher_app_secret_label' => 'Pusher App Secret',
                    'pusher_app_secret_palceholder' => 'Pusher App Secret',
                ],
                'buttons' => [
                    'setup_database' => '创建数据库',
                    'setup_application' => '建立应用',
                    'install' => '安装',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => '第三步 | 环境设置 | 文本编辑',
            'title' => '文本编辑',
            'save' => '保存.env',
            'back' => '回退',
            'install' => '保存并安装',
        ],
    ],


    /**
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => '完成',
        'finished' => '应用已成功安装.',
        'exit' => '点击退出',
    ],
];
