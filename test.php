<?php

require_once __DIR__ . '/vendor/autoload.php';

use PHPExtra\Sorter\ObjectSorter;
use PHPExtra\Sorter\SorterInterface;
use PHPExtra\Sorter\StringSorter;
use PHPExtra\Type\Collection\Collection;

header('Content-type: text/plain; charset=utf-8');

$collection1 = array(
    (object)array('name' => 'żdzisia', 'pos' => 10,    'plec' => 'm'),
    (object)array('name' => 'ździsia', 'pos' => 10,    'plec' => 'm'),
    (object)array('name' => 'Ździsia', 'pos' => 10,    'plec' => 'm'),
    (object)array('name' => 'Żdzisia', 'pos' => 10,    'plec' => 'm'),
    (object)array('name' => 'Żdzisia', 'pos' => 10,    'plec' => 'k'),
    (object)array('name' => 'zdzisia', 'pos' => 10,    'plec' => 'm'),
    (object)array('name' => 'śnia',    'pos' => 1,     'plec' => 'k'),
    (object)array('name' => 'ąnia',    'pos' => 1,     'plec' => 'k'),
    (object)array('name' => 'ania',    'pos' => 1,     'plec' => 'k'),
    (object)array('name' => 'ania',    'pos' => 2,     'plec' => 'k'),
    (object)array('name' => 'ania',    'pos' => 2,     'plec' => 'm'),
    (object)array('name' => 'beata',   'pos' => 6,     'plec' => 'k'),
);

$collection2 = array(
    'żdzisia',
    'Ania',
    'ania',
    'śnia',
    'ździsia',
    'zdzisia',
    'ąnia',
    'beata',
    'ąnia'
);

$col = new Collection($collection1);

//var_dump($col);
$sorter = new ObjectSorter();
$sorter
    ->sortBy('name', SorterInterface::ASC)
    ->sortBy('pos', SorterInterface::ASC)
    ->sortBy('plec', SorterInterface::ASC)
;
//var_dump($sorter->sort($collection1));

//$sorter = new StringSorter();

var_dump(new Collection($sorter->sort($col)));

