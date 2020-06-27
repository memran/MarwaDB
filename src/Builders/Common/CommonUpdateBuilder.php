<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see https://www.github.com/memran
 * @see http://www.memran.me
 **/

namespace MarwaDB\Builders\Common;

use MarwaDB\Exceptions\ArrayNotFoundException;
use MarwaDB\Exceptions\NotFoundException;
use MarwaDB\Builders\Common\BuilderInterface;
use MarwaDB\SqlTrait;
use MarwaDB\Builders\Common\WhereBuilder;
use MarwaDB\Builders\Common\Sql\Update;

class CommonUpdateBuilder implements BuilderInterface
{
    use SqlTrait;
    use WhereBuilder;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $__sqlString;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_update;
    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->_update = new Update();
    }
    /**
     * Undocumented function
     *
     * @param  string $name
     * @return void
     */
    public function table(string $name)
    {
        $this->_update->setTable($name);
    }
    /**
     * Undocumented function
     *
     * @param  array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->_update->addData($data);
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function formatSql()
    {
        $this->__sqlString = 'UPDATE '.$this->_update->getTable().' SET ';
        $this->__sqlString.= $this->_update->getColumnValues();
        if (!empty($this->_where)) {
            $this->__sqlString .= ' '.$this->_where->getWhere();
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getSql()
    {
        return $this->removeWhiteSpace($this->__sqlString);
    }
}
