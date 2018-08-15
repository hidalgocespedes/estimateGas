<?php

if (!function_exists("getLatestBlock")) { 

//Send POST and returns its raw response
function postContent($url,$data) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                            'Content-Type: application/json'
                                            ));
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    // receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec ($ch);

    curl_close ($ch);
    return $server_output;
}

//Computes a percentile X
function get_percentile($percentile, $array) {
    sort($array);
    $index = ($percentile/100) * count($array);
    if (floor($index) == $index) {
         $result = ($array[$index-1] + $array[$index])/2;
    }
    else {
        $result = $array[floor($index)];
    }
    return $result;
}

//Arithmetic media
function media($array) {
	$suma=array_sum($array);
	$total=count($array);
	if ($total == 0) return 0;
	$media=$suma / $total;
	return ($media);
}

//Moda
function moda($array) {
	$cuenta = array_count_values($array);
    arsort($cuenta);
    return key($cuenta);
}

//JSON RPC to retrieve full data of a block
function getBlock($url,$block) {
	$data = Array("jsonrpc" => "2.0","method" => "eth_getBlockByNumber","params" => Array($block,true),"id"=>1);
	$d = json_decode(postContent($url,$data),true);
	return $d['result'];
}


//Shortcut to getBlock
function getLatestBlock($url) {
	return getBlock($url,"latest");
}
	

//Main function
function analyzeGasPrice($url, $bloques = 50, $verbose = false) {

	$results = Array();

	//Firstly, the latest
	$last = getLatestBlock($url);

	$last_number = hexdec($last['number']);
	$results['last_block'] = $last_number;
	$results['blocks'] = Array();

	if ($verbose) 
		echo "Last block number = ".$last_number.PHP_EOL;;

	$min = PHP_INT_MAX;

	//A safe amount of blocks
	$bloques = min($last_number, $bloques);
	$last_number -= $bloques -1; 


	//External iteration, it runs on every block of the series
	$bigarray = Array();
	for ($k = 0; $k < $bloques ; $k++) {
	
		$block = Array();

		$minb = PHP_INT_MAX;
		$b = getBlock($url,'0x'.dechex($last_number + $k));

		$array = Array();
		for ($i = 0; $i < count($b['transactions']) ; $i++) {
			$x = $b['transactions'][$i]['gasPrice'];
			$g_d = strval(hexdec($x)) / pow(10,9);

			//Because php's sort array function deals just with integer an strings, we round the gasPrice keeping one decimal
			$entero 	= intval(round($g_d * 10));
			$array[] 	= $entero;
			$bigarray[] = $entero;
			$minb = min($minb,$entero);
		}

		$block['moda'] 	= moda($array)/10;
		$block['media'] = media($array)/10;
		$block['min'] 	= $minb/10;
		$results['blocks'][strval($last_number + $k)] = $block;

 		$min = min($minb,$min);
	}

	$results['moda'] = moda($bigarray)/10;	
	$results['media'] = media($bigarray)/10;	
	$results['percentile_50'] = get_percentile(50,$bigarray) /10;
	$results['percentile_25'] = get_percentile(25,$bigarray) /10;
	$results['percentile_10'] = get_percentile(10,$bigarray) /10;

	return $results;

}


function getGasPriceEstimation($url, $blocks = 10, $speed = "medium") {

	$json = analyzeGasPrice($url, $blocks);
	if (in_array($speed, Array("slow", "cheapest"))) {
		$gas = $json['percentile_10'];
	}
	else if (in_array($speed, Array("medium", "safe", "cheap"))) {
		$gas = $json['percentile_25'];
	}
	else $gas = $json['percentile_50'];

	return $gas;
}
	

}

