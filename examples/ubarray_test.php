<?php

require '../lib/UBArray.php';

$arr = array(
	array( 'foo0' => 'bar0' )
);

$extracted = UBArray::extractFirstLevel( $arr );

var_dump( $extracted );



$arr1 = array(
	array( 'foo1' => 'bar1' )
);

$arr2 = array(
	array( 'foo2' => 'bar2' )
);

$merged = UBArray::merge($arr1, $arr2);

var_dump($merged);

