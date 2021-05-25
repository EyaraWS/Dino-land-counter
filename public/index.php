<?php

use App\Shelter;

require dirname(__DIR__).'/vendor/autoload.php';

$shelter = new Shelter();
echo "Please choose the width of the continent [1-100000]\n";
try {
    $shelter->setWidth(trim(fgets(STDIN)));
} catch (Exception $e) {
    echo sprintf("%s\n", $e->getMessage());
    exit(1);
}
echo "Please choose your altitude values [0-100000]\n";
try {
    $shelter->setAltitudes(trim(fgets(STDIN)));
} catch (Exception $e) {
    echo sprintf("%s\n", $e->getMessage());
    exit(1);
}
$startTime = microtime(true);
echo "Computing...\n";
echo sprintf("There is %s shelter(s) in this continent.\n", $shelter->compute());

echo sprintf("__________\nMemory usage: %skb\n", round(memory_get_peak_usage() / 1024));
echo sprintf("Execution time: %sms\n", round((microtime(true) - $startTime) * 100, 3));
echo "Press [ENTER] to exit\n";
fgets(STDIN);
