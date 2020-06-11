<?php
return [
    'debug' => true,
    'timeout' => 3600,
    'database' =>
        [
            'default'=>
                [
                    'driver' => "mysql",
                    'host' => "localhost",
                    'port' => 3306,
                    'database' => "test",
                    'username' => "root",
                    'password' => "",
                    'charset' => "utf8mb4",
                    'returnType'=> 'object'
                ],
            'sqlSrv'=>
                [
                    'driver' => "mysql",
                    'host' => "localhost",
                    'port' => 3306,
                    'database' => "test",
                    'username' => "root",
                    'password' => "",
                    'charset' => "utf8mb4"
                ]
        ]

];