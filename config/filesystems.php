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

    'default' => env('FILESYSTEM_DRIVER', 'public'),

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
    | Supported Drivers: "local", "ftp", "sftp", "s3"
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
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'permissions' => [
                'dir' => [
                    'public' => 0777
                ],
            ],
        ],

        'providers' => [
            'driver' => 'local',
            'root' => base_path('app/Providers'),
            'visibility' => 'public',
        ],

        'database' => [
            'driver' => 'local',
            'root' => base_path('database'),
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],
        'test' => [
            'driver' => 'local',
            'root' => storage_path('framework/testing/disks'),
        ],
        'storage_input' => [
            'driver' => 'local',
            'root' => storage_path('app/public/input'),
        ],
        'storage_output' => [
            'driver' => 'local',
            'root' => storage_path('app/public/output'),
        ],
        'custom_folder_input_local' => [
            'driver' => 'local',
//            'root' => 'F:/input',
            'root' => \Helper\Common::getConfigURLFileAI('custom_folder_input_local'),
        ],
        'custom_download_output_local' => [
            'driver' => 'local',
//            'root' => 'F:/output',
            'root' => \Helper\Common::getConfigURLFileAI('custom_download_output_local'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
        public_path('upload') => storage_path('app/public/upload'),
    ],

];
