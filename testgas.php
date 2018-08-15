<?php
/*
*	Idea and coding, https://github.com/hidalgocespedes
*/

require "gas.php";

$url = "http://localhost:8545";
$blocks = 10;
$options = getopt("b:u:");
if (isset($options['b'])) $blocks = $options['b'];
if (isset($options['u'])) $url = $options['u'];

echo "URL " . $url.PHP_EOL;
echo "Study based on ".$blocks. " blocks".PHP_EOL;
echo "Medium/cheap gasPrice, according to the last ".$blocks." blocks = ". getGasPriceEstimation($url, $blocks, $speed = "medium") . ' gwei'.PHP_EOL;


//To browse full returned jsons
$full = analyzeGasPrice($url, $blocks);
echo "Slow/cheapest gasPrice, according to the last ".$blocks." blocks = ". $full['percentile_10'] . ' gwei'.PHP_EOL;
echo "Fast/expensive gasPrice, according to the last ".$blocks." blocks = ". $full['percentile_50']. ' gwei'.PHP_EOL;

