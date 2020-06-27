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
use MarwwDB\Builders\Common\BuilderInterface;

abstract class AbstractQueryBuilder
{
    abstract public function __construct(BuilderInterface $builder_in);
    abstract public function buildQuery();
    abstract public function getSql();
}
