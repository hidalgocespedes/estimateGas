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

$results = Array(
    "daitransferunits" => getEstimateGas($url, $DAI_address, $data_transferDAI),
    "estimation5B" => getGasPriceEstimation($url, 5, $speed = "medium"),
    "analysisXB" => $blocks >0 ? analyzeGasPrice($url, $blocks) : null
);



echo "To transfer DAI, the network estimates " . $results["daitransferunits"] . " gas units are needed".PHP_EOL;
echo "Medium/cheap gasPrice, according to the last 5 (example) blocks = ". $results['estimation5B'] . ' gwei'.PHP_EOL;

//To browse full returned jsons
if ($blocks>0) {
    echo "Study based on ".$blocks. " blocks".PHP_EOL;
    echo " Slow/cheapest gasPrice, according to the last ".$blocks." blocks = ". $results['analysisXB']['percentile_10'] . ' gwei'.PHP_EOL;
    echo " Medium/cheap gasPrice, according to the last ".$blocks." blocks = ". $results['analysisXB']['percentile_25'] . ' gwei'.PHP_EOL;
    echo " Fast/expensive gasPrice, according to the last ".$blocks." blocks = ". $results['analysisXB']['percentile_50']. ' gwei'.PHP_EOL;
}
