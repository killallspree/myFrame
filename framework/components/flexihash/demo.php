<?php
/**
 * 一致性哈希算法
 */
error_reporting(E_ALL);
ini_set('display_errors', true);

require(dirname(__FILE__).'/../Flexihash.php');

$hash = new Flexihash();

// bulk add
$hash->addTargets(array('127.0.0.1:6379', '127.0.0.1:6380', 'cache-3', 'cache-1', 'cache-2'));
var_dump($hash->getAllTargets());
//$hash->removeTarget('cache-2');
//var_dump($hash->getAllTargets());die();
// simple lookup
echo $hash->lookup('object-a'); // "cache-1"
echo $hash->lookup('object-b'); // "cache-2"


// add and remove
$hash
    ->addTarget('cache-4')
    ->removeTarget('cache-1');

// lookup with next-best fallback (for redundant writes)
$hash->lookupList('object', 2); // ["cache-2", "cache-4"]

// remove cache-2, expect object to hash to cache-4
$hash->removeTarget('cache-2');
$hash->lookup('object'); // "cache-4"