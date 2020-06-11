<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB\Builders\Common;

use MarwaDB\Exceptions\ArrayNotFoundException;
use MarwaDB\Exceptions\NotFoundException;
use MarwaDB\Builders\Common\BuilderInterface;
use MarwaDB\SqlTrait;
use MarwaDB\Builders\Common\WhereBuilder;
use MarwaDB\Builders\Common\Sql\Delete;

class CommonDeleteBuilder implements BuilderInterface
{
    use SqlTrait;
    use WhereBuilder;

    private $__sqlString;
    
    protected $_delete;

    public function __construct()
    {
        $this->_delete = new Delete();
    }
    
    public function table(string $name)
    {
        $this->_delete->setTable($name);
    }
    
    public function formatSql()
    {
        $this->__sqlString = 'DELETE FROM '.$this->_delete->getTable();
        if (!empty($this->_where)) {
            $this->__sqlString .= ' '.$this->_where->getWhere();
        }
    }

    public function getSql()
    {
        return $this->removeWhiteSpace($this->__sqlString);
    }
}