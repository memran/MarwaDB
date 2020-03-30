<?php
return [
	'debug' => TRUE,
	'timeout' => 3600,
	'database' =>
		[
			'default'=>
				[
					'driver' => "mysql",
					'host' => "localhost",
					'port' => 3306,
					'database' => "rbc",
					'username' => "root",
					'password' => "",
					'charset' => "utf8mb4"
				],
			'sqlSrv'=>
				[
					'driver' => "mysql",
					'host' => "localhost",
					'port' => 3306,
					'database' => "rbc",
					'username' => "root",
					'password' => "",
					'charset' => "utf8mb4",
					// 'options' =>
					// [
				 //    	'PDO::ATTR_DEFAULT_FETCH_MODE' => 'PDO::FETCH_ASSOC'
					// ]
				]
		]

];

?>
