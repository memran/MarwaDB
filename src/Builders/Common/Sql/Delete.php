<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/
namespace MarwaDB\Builders\Common\Sql;

use MarwaDB\Util;
use MarwaDB\Builders\Common\Sql\InvalidTableException;

class Delete
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $_table;
  
    /**
     * set table name
     *
     * @param string $name
     * @return void
     */
    public function setTable(string $name)
    {
        $this->_table = $name;
    }
    /**
     * get Table name
     *
     * @return void
     */
    public function getTable()
    {
        return $this->_table;
    }
}