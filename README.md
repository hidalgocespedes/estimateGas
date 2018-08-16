

# Explanation

This is a simple approach to study the minimum gasPrice that should to be used in Ethereum networks to have success sending transactions at minimum costs.

The basis of this approach is different and much simpler from that used by https://ethgasstation.info . In https://ethgasstation.info/FAQcalc.php you can read more about ethgasstation's approach.

The following script just do the following:
* It iterates downloading every on the "n" blocks in the ethereum network.
* For every block, it gets every transaction gasPrice.
* It computes three different percentiles to be used as a gasPrice reference.


The result is a JSON. The main part of this JSON is

```
  ["moda"]=>  int(8)
  ["media"]=> float(19.445505617978)
  ["percentile_50"]=> int(10)
  ["percentile_25"]=> int(8)
  ["percentile_10"]=> int(8)
```


# How to use it

The meaning of percentiles (e.g. percentile_X) is that "the X % of the gasPrices in the studied blocks are less or equal than this gasPrice". The question you should face is how much hurried do you need to be on sending a transaction. If the answer is "very hurried" maybe is safe enough to pay more than the 50% of the gas than the previous transactions have paid. 

But, in the contrary, if you can wait some more time but you want to assure that (earthquakes apart) your transaction is cheap and very likely successful in an affordable amount of blocks/time, you can try with percentiles 25 or indeed 10.


# Test

As a test you can invoke ** testgas.php ** to test the gasPrice analysis at any time.


```
$ php testgas.php   -u https://mainnet.infura.io/<your_INFURA_key> -b 10
URL https://mainnet.infura.io/<your_INFURA_key>
Study based on 10 blocks
Medium/cheap gasPrice, according to the last 10 blocks = 15 gwei
```


# Human interpretation

As it has been returned in the previous test, the estimation based on percentiles could be traslated into an "speed" or "economic" interpretation. 

The most exigent percentile (50) is more expensive but can be fast in comparison with percentile 10. Read carefully the following block to understand the "synonims" of each percentile. When nothing about is specified you get the safest and expensive estimation.


```php

<?php
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
?>
```

# Full returned data


The following is the aspect of the full JSON you can get calling the function **"analyzeGasPrice($url, $blocks)"**. Enjoy it.


```
array(7) {
  ["last_block"]=>
  int(6151741)
  ["blocks"]=>
  array(10) {
    [6151732]=>
    array(3) {
      ["moda"]=>
      float(7.5)
      ["media"]=>
      float(7.9515151515152)
      ["min"]=>
      int(7)
    }
    [6151733]=>
    array(3) {
      ["moda"]=>
      int(8)
      ["media"]=>
      float(12.321052631579)
      ["min"]=>
      int(1)
    }
	...
  }
  ["moda"]=>
  int(8)
  ["media"]=>
  float(19.445505617978)
  ["percentile_50"]=>
  int(10)
  ["percentile_25"]=>
  int(8)
  ["percentile_10"]=>
  int(8)
}

```

# Future work

This script can be extended to study the gasPrices against an historical estimation series. It is a pity that EthGasStation does not supply archived estimations to do a comparison.


