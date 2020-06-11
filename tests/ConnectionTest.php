<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use MarwaDB\Connections\Connection;
use MarwaDB\Connections\Interfaces\ConnectionInterface;
use MarwaDB\Connections\Exceptions\NotFoundException;
use MarwaDB\Connections\Exceptions\InvalidException;
use MarwaDB\Connections\Exceptions\ConnectionException;

final class ConnectionTest extends TestCase
{
    /**
     *
     */
    protected $conn;
    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $config=[
            'default'=>
                [
                    'driver' => "mysql",
                    'host' => "localhost",
                    'port' => 3306,
                    'database' => "test",
                    'username' => "root",
                    'password' => "",
                    'charset' => "utf8mb4",
                ],
            'sqlSrv'=>
                [
                    'driver' => "mysql",
                    'host' => "127.0.0.1",
                    'port' => 3306,
                    'database' => "test",
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
    protected function setUp() : void
    {
        $this->conn = Connection::getInstance($this->config);
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testConnectionInterface() : void
    {
        $this->assertInstanceOf(ConnectionInterface::class, $this->conn);
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testSingletonConnectionClass() : void
    {
        $this->assertEquals($this->conn, Connection::getInstance($this->config));
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testDatabaseConnectionStatusIsString() : void
    {
        $this->assertIsString($this->conn->status());
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testGetHostReturn() : void
    {
        $this->assertEquals('localhost', $this->conn->getHost());
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testSetFetchModeFunction() : void
    {
        $this->conn->setFetchMode('object');
        $this->assertEquals(5, $this->conn->getFetchMode());
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testFetchModeReturnInterfaceEqualToConnectionInterface() : void
    {
        $this->assertInstanceOf(ConnectionInterface::class, $this->conn->setFetchMode('object'));
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testToSetConnectionSpecificName() : void
    {
        $this->conn->connection('sqlSrv');
        //$this->assertIsArray($this->conn->getConnection());
        $this->assertEquals('127.0.0.1', $this->conn->getConnection()['host']);
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testExceptionEmptyQuery() : void
    {
        $this->expectException(InvalidException::class);
        $this->conn->query('');
    }
}