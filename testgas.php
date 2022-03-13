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
