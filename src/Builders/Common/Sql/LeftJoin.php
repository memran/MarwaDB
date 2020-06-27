<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see https://www.github.com/memran
 * @see http://www.memran.me
 **/
namespace MarwaDB\Builders\Common\Sql;

use MarwaDB\Builders\Common\Sql\InvalidTableException;
use MarwaDB\Builders\Common\Sql\InnterJoin;

class LeftJoin extends InnerJoin
{
    
    /**
     * Undocumented function
     *
     * @param  string $joinTable
     * @param  string $leftColumn
     * @param  string $condition
     * @param  string $rightColumn
     * @return void
     */
    public function addJoin(string $joinTable, string $leftColumn, string $condition, string $rightColumn) : void
    {
        $inner = "LEFT JOIN {$joinTable} ON {$leftColumn} {$condition} {$rightColumn}";
        array_push($this->_joins, $inner);
    }
}
