<?php
use PHPUnit\Framework\TestCase;
use MarwaDB\Connection;

final class ConnectionTest extends TestCase
{

	protected $conn;
	protected $config=[
			'default'=>
				[
					'driver' => "mysql",
					'host' => "localhost",
					'port' => 3306,
					'database' => "rbc",
					'username' => "root",
					'password' => "",
					'charset' => "utf8mb4",
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
					'options' =>
					[
				    	'PDO::ATTR_DEFAULT_FETCH_MODE' => 'PDO::FETCH_ASSOC'
					]
				]
			];
		//setup database object
	 	protected function setUp()
	    {
	        $this->conn = new Connection($this->config);
	    }

		public function testConnectionClass()
		{
			$this->assertInstanceOf(Connection::class,$this->conn);
		}

		public function testConnectFunction()
		{
				$pdo=$this->conn->connect();
				$this->assertInstanceOf(PDO::class, $pdo);
		}
}

