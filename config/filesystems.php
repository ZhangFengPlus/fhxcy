<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

        'oss' => [
            'driver' => 'oss',
            'access_id' => env('OSS_ACCESS_ID', ''),
            'access_key' => env('OSS_ACCESS_KEY', ''),
            'bucket' => env('OSS_BUCKET', ''),
            // OSS 外网节点或自定义外部域名
            'endpoint' => env('OSS_ENDPOINT', ''),
            // 如果isCName为true, getUrl会判断cdnDomain是否设定来决定返回的url，
            // 如果cdnDomain未设置，则使用endpoint来生成url，否则使用cdn
            'cdnDomain' => env('OSS_CDNDOMAIN', ''),
            'ssl' => env('OSS_SSL', false),
             // 是否使用自定义域名,
             // true: 则Storage.url()会使用自定义的cdn或域名生成文件url，
             // false: 则使用外部节点生成url
            'isCName' => env('OSS_ISCNAME', false),
            'debug' => env('OSS_DEBUG', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 上传目录目录配置
    |--------------------------------------------------------------------------
    */

    'uploadpath' => [
        //商家
        'business' => [
            'savepath' => 'public/business',     //本地磁盘保存目录
            'accesspath' => 'storage/business', //本地磁盘访问目录
            'osspath' => '/business',            //oss磁盘目录
        ],
        //商品
        'goods' => [
            'savepath' => 'public/goods',     //本地磁盘保存目录
            'accesspath' => 'storage/goods', //本地磁盘访问目录
            'osspath' => '/goods',            //oss磁盘目录
        ],
        //广告
        'banner' => [
            'savepath' => 'public/banner',     //本地磁盘保存目录
            'accesspath' => 'storage/banner', //本地磁盘访问目录
            'osspath' => '/banner',            //oss磁盘目录
        ],
        //标签
        'label' => [
            'savepath' => 'public/label',     //本地磁盘保存目录
            'accesspath' => 'storage/label', //本地磁盘访问目录
            'osspath' => '/label',            //oss磁盘目录
        ],
        //订单
        'order' => [
            'savepath' => 'public/order',     //本地磁盘保存目录
            'accesspath' => 'storage/order', //本地磁盘访问目录
            'osspath' => '/order',            //oss磁盘目录
        ],
        //用户
        'user' => [
            'savepath' => 'public/user',     //本地磁盘保存目录
            'accesspath' => 'storage/user', //本地磁盘访问目录
            'osspath' => '/user',            //oss磁盘目录
        ],
        //用户
        'category' => [
            'savepath' => 'public/category',     //本地磁盘保存目录
            'accesspath' => 'storage/category', //本地磁盘访问目录
            'osspath' => '/category',            //oss磁盘目录
        ],
        //帖子
        'posts' => [
            'savepath' => 'public/posts',     //本地磁盘保存目录
            'accesspath' => 'storage/posts', //本地磁盘访问目录
            'osspath' => '/posts',            //oss磁盘目录
        ],
    ],
];
