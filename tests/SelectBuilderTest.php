<?php
 use PHPUnit\Framework\TestCase;
use MarwaDB\DB;
use MarwaDB\Builders\Common\Sql\InvalidTableException;
use MarwaDB\Builders\Common\CommonSelectBuilder;
use MarwaDB\Exceptions\NotFoundException;
use MarwaDB\Builders\Common\BuilderInterface;

final class SelectBuilderTest extends TestCase
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
        $this->select = new CommonSelectBuilder();
    }
    public function testInstanceOfBuilderInterface()
    {
        $this->assertInstanceOf(BuilderInterface::class, $this->select);
    }
    public function testExceptioNotFoundForEmptySql()
    {
        $this->expectException(NotFoundException::class);
        $this->select->getSql();
    }
    public function testSetTableSuccessReturn()
    {
        $select = $this->select->table('users');
        $this->assertInstanceOf(BuilderInterface::class, $select);
    }
    public function testAddSelectException()
    {
        $this->expectException(NotFoundException::class);
        $select = $this->select->addSelect('');
    }

    public function testOnSelectMethodEmptyArrayIgnore()
    {
        $select = $this->select->select([]);
        $this->assertInstanceOf(BuilderInterface::class, $select);
    }
}