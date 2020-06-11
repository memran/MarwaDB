<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB\Builders;

use MarwaDB\Exceptions\ArrayNotFoundException;
use MarwaDB\Exceptions\NotFoundException;
use MarwaDB\Builders\Common\BuilderInterface;

abstract class AbstractQueryBuilder
{
    abstract public function __construct(BuilderInterface $builder_in, string $type, string $table);
    abstract public function setMethods(array $methods);
    abstract public function buildQuery();
    abstract public function getSql();
}