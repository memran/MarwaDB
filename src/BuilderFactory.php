<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see https://www.github.com/memran
 * @see http://www.memran.me
 **/

namespace MarwaDB;

use MarwaDB\Exceptions\ArrayNotFoundException;
use MarwaDB\Exceptions\NotFoundException;
use MarwaDB\Builders\Common\BuilderInterface;

class BuilderFactory
{
    /**
     * Undocumented function
     *
     * @param  [type] $instance
     * @return void
     */
    public static function getInstance(BuilderInterface $instance, string $type, string $table, string $driver)
    {
        $builder_director='\\MarwaDB\\Builders\\'.$driver.'\\SqlBuilder';
        return new $builder_director($instance, $type, $table);
    }
}
