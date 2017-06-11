<?php
return [
    //总开关，是否开启监控
    'monitor' => true,
    'http' => [
        'times' => 1,//连续超过多少次开始报警
        'code'=>400,//http返回码警戒值
        'urls' => [//需要监控的url
            'http://www.123jixinyu.com',
            'http://www.51myd.com/tet',
        ]
    ],
    'sphinx' => [
        'times' => 3,//连续超过多少次开始报警
    ],
    'mysql' => [
        'times' => 1,//连续超过多少次开始报警
    ],
    '',
    'from' => '848280118@qq.com',
    'to' => [
        '848280118@qq.com'
    ],

];