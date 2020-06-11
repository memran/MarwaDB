<?php
 use PHPUnit\Framework\TestCase;
use MarwaDB\DB;
use MarwaDB\QueryFactory;
use MarwaDB\Builders\Common\BuilderInterface;
use MarwaDB\Builders\Common\Sql\InvalidTableException;
use MarwaDB\Exceptions\NotFoundException;

final class QueryFactoryTest extends TestCase
{
    public function testSelectBuilderInstanceProperly()
    {
        $select = QueryFactory::getInstance('mysql', 'select');
        $this->assertInstanceOf(BuilderInterface::class, $select);
    }

    public function testInvalidTableException()
    {
        $this->expectException(NotFoundException::class);
        $obj = QueryFactory::getInstance('mysql', 'select');
        $obj->getSql();
    }
}