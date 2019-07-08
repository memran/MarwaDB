<?php
chdir(__DIR__);

if (!defined('IS_TEST')) {
    define('IS_TEST', true);
}
require_once __DIR__ . '/../vendor/autoload.php';
