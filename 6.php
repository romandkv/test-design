<?php

function readTheFile(&$memoryUsage) {
    $begin = memory_get_usage(true);
    $handle = fopen('large_file.txt', "r");
    while ($line = fread($handle, 7)) {
    	yield $line;
    }
    fclose($handle);
	$end = memory_get_usage(true);
    $memoryUsage = $end - $begin;
}

function readMyFile(&$memoryUsage)
{
    $begin = memory_get_usage(true);
    $filePath = 'large_file.txt';
    $result = [];
    foreach (file($filePath) as $x => $line) {
        $result[] = 'Line ' . $x . ': ' . $line;
    }
    $end = memory_get_usage(true);
    $memoryUsage = $end - $begin;
    return $result;
}

$memoryUsage = 0;
$memoryUsage2 = 0;

readMyFile($memoryUsage);
foreach (readTheFile($memoryUsage2) as $item) {

};

$memoryUsage = $memoryUsage / 1024 / 1024;
$memoryUsage2 = $memoryUsage2 / 1024 / 1024;


echo "Memory usage is: $memoryUsage<br>";
echo "Memory usage is: $memoryUsage2<br>";

echo "Mem1/Mem2 = " . $memoryUsage / $memoryUsage2 . "<br>";