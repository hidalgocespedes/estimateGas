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

$data_transferDAI = "0xa9059cbb000000000000000000000000b9e655487e33bddf199f0e3e3c9b4faf027f5d5f00000000000000000000000000000000000000000000000ad78ebc5ac6200000";
$DAI_address = "0x6B175474E89094C44Da98b954EedeAC495271d0F";
echo "Para transferir DAI se estiman " . getEstimateGas($url, $DAI_address, $data_transferDAI) . " unidades de gas".PHP_EOL.PHP_EOL;

echo "Medium/cheap gasPrice, according to the last 5 (example) blocks = ". getGasPriceEstimation($url, 5, $speed = "medium") . ' gwei'.PHP_EOL;

//To browse full returned jsons
echo "Study based on ".$blocks. " blocks".PHP_EOL;
$full = analyzeGasPrice($url, $blocks);
echo "Slow/cheapest gasPrice, according to the last ".$blocks." blocks = ". $full['percentile_10'] . ' gwei'.PHP_EOL;
echo "Medium/cheap gasPrice, according to the last ".$blocks." blocks = ". $full['percentile_25'] . ' gwei'.PHP_EOL;
echo "Fast/expensive gasPrice, according to the last ".$blocks." blocks = ". $full['percentile_50']. ' gwei'.PHP_EOL;

