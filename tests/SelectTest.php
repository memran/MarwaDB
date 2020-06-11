<?php
 use PHPUnit\Framework\TestCase;
use MarwaDB\DB;
use MarwaDB\Builders\Common\Sql\InvalidTableException;
use MarwaDB\Builders\Common\Sql\Select;

final class SelectTest extends TestCase
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $select;
    /**
     * Undocumented function
     *
     * @return void
     */
    protected function setUp() : void
    {
        $this->select = new Select();
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testFromTable()
    {
        $this->select->setFrom('users');
        $this->assertEquals('users', $this->select->getFrom());
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testFromTableException()
    {
        $this->expectException(InvalidTableException::class);
        $this->select->setFrom('');
        $this->select->getFrom();
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testAddColFunction()
    {
        $this->select->setFrom('users');
        $this->select->addCol('*');
        $this->assertStringContainsString('*', $this->select->getCols());
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testStringToCols()
    {
        $this->select->setFrom('users');
        $this->select->addCols('username,password AS pass,status');
        $this->assertStringContainsString('username', $this->select->getCols());
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testTableName()
    {
        $this->select->setFrom('users');
        $this->assertStringContainsString('users', $this->select->getCols());
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testSetMultpleFromString()
    {
        $this->select->setFrom('users');
        $this->select->setFrom('role');
        $this->assertEquals('users,role', $this->select->getFrom());
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testFromTableAliasName()
    {
        $this->select->setFrom('users as U');
        $this->select->setFrom('role');
        $this->assertEquals('users as U,role', $this->select->getFrom());
    }
   
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testAddDistinctSelect()
    {
        $this->select->setFrom('users');
        $this->select->addDistinct();
        $this->assertEquals('DISTINCT', trim($this->select->getDistinct()));
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testLimitSql()
    {
        $this->select->setFrom('users');
        $this->select->setLimit(25);
        $this->assertEquals('LIMIT 25', $this->select->getLimit());
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function testOffsetSql()
    {
        $this->select->setFrom('users');
        $this->select->setOffset(0);
        $this->assertEquals('OFFSET 0', $this->select->getOffset());
    }

    public function testOrderByRaw()
    {
        $this->select->setFrom('users');
        $this->select->orderByRaw('username ASC');
        $this->assertEquals('ORDER BY username ASC', $this->select->getOrderBy());
    }
    public function testHavingRaw()
    {
        $this->select->setFrom('users');
        $this->select->havingRaw('status = 1');
        $this->assertEquals('HAVING status = 1', $this->select->getHavingRaw());
    }
}