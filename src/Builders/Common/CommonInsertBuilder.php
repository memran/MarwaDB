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
use MarwaDB\Builders\Common\Sql\Insert;
use MarwaDB\Builders\Common\Sql\Update;

class CommonInsertBuilder implements BuilderInterface
{
    use SqlTrait;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $__sqlString ;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_insert;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_update;
    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $_data=[];
    /**
     * Undocumented function
     *
     * @param array $data
     */
    public function __construct(array $data=[])
    {
        $this->_insert = new Insert();

        if (!empty($data)) {
            $this->_data = $data;
        }
    }
    /**
     * Undocumented function
     *
     * @param  string $name
     * @return void
     */
    public function table(string $name)
    {
        $this->_insert->setTable($name);
    }
    /**
     * Undocumented function
     *
     * @param  array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->_insert->addData($data);
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function updateOrInsert(array $attributes)
    {
        $this->_update = new Update();
        $this->_update->addData($attributes);
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function formatSql()
    {
        $this->__sqlString = 'INSERT INTO '.$this->_insert->getTable();
        $this->__sqlString.= ' ('.$this->_insert->getCols().')';
        $this->__sqlString.= ' VALUES ('.$this->_insert->getValues().')';

        if (isset($this->_update)) {
            $this->__sqlString .= ' ON DUPLICATE KEY UPDATE '.$this->_update->getColumnValues();
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
