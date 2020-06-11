<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/
namespace MarwaDB;

use MarwaDB\Builders\Common\Sql\InvalidTableException;

class Util
{
    /**
     * Undocumented function
     *
     * @param [type] $string
     * @param [type] $startString
     * @return void
     */
    public static function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
    /**
     * Undocumented function
     *
     * @param [type] $a
     * @return boolean
     */
    public static function is_multi($a)
    {
        if (count($a) == count($a, COUNT_RECURSIVE)) {
            return false ;
        } else {
            return true;
        }
    }
}