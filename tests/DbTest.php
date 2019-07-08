<?php
use PHPUnit\Framework\TestCase;
use MarwaDB\DB;

final class DbTest extends TestCase
{
	protected $db;
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

        $this->db = new DB($this->config);
    }

    //check database instance
	public function testCanBeCreatedDb()
	{
		 $this->assertInstanceOf(
		            DB::class,
		            $this->db
		        );

	}

	/**
	 * check raw PDO Connection
	 * */
	public function testRawPDO()
	{
			$pdo= $this->db->getPDO();
			$this->assertInstanceOf(PDO::class,$pdo);
	}

	public function testConnection()
	{
		$db=$this->db->connection();

 		$this->assertInstanceOf(
		            DB::class,
		            $db
		        );
	}

	public function testConnectionNotNull()
	{
		$this->db->connection('sqlSrv');
		$name=$this->db->getConnection()->defaultConn;
 		$this->assertEquals(
		            $name,
		            "sqlSrv"
		        );
	}

	public function testConnectionNull()
	{
		$this->db->connection();
		$name=$this->db->getConnection()->defaultConn;
 		$this->assertEquals(
		            $name,
		            "default"
		        );
	}

	public function testRawQueryFunction()
	{
		$result = $this->db->rawQuery('SELECT * FROM system');
		$rows=$this->db->count();
		$this->assertEquals(
		            $rows,
		            2
		        );
	}

	public function testSelectQueryFunction()
	{
		$result = $this->db->select('SELECT * FROM system');
		$rows=$this->db->count();
		$this->assertEquals(
		            $rows,
		            2
		        );
	}

	public function testInsertQueryFunction()
	{
		$id=rand(10,100);
		$member='Member'.$id;
		$result = $this->db->insert('INSERT INTO rule VALUES ("'.$id.'","'.$member.'","Member")');
		$rows=$this->db->count();
		$this->assertEquals(
		            $rows,
		            1
		        );
	}
	public function testUpdateQueryFunction()
	{

		$result = $this->db->update('UPDATE rule SET permission="Administrator"');
		$rows=$this->db->count();
		$this->assertEquals(
		            $rows,
		            1
		        );
	}

	public function testDeleteQueryFunction()
	{

		$result = $this->db->delete('DELETE FROM rule');
		$rows=$this->db->count();
		$this->assertEquals(
		            $rows,
		            1
		        );
	}

	public function testTransactionFunction()
	{
		$test=$this->db;
		$this->db->transaction(function() use ($test)
		{
			$result=$test->rawQuery('SELECT now()');
		});
	}

}
