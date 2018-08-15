

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


## Test


```
$ php testgas.php   -u https://mainnet.infura.io/<your_INFURA_key>
URL https://mainnet.infura.io/<your_INFURA_key>
Study based on 10 blocks
Medium/cheap gasPrice, according to the last 10 blocks = 15 gwei
```



## Full returned data
The following is the aspect of the full JSON you can get calling the function *"analyzeGasPrice($url, $blocks)"*. Enjoy it.

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
    [6151734]=>
    array(3) {
      ["moda"]=>
      int(20)
      ["media"]=>
      float(35.609677419355)
      ["min"]=>
      int(15)
    }
    [6151735]=>
    array(3) {
      ["moda"]=>
      int(10)
      ["media"]=>
      float(22.36862745098)
      ["min"]=>
      int(9)
    }
    [6151736]=>
    array(3) {
      ["moda"]=>
      int(9)
      ["media"]=>
      float(19.303571428571)
      ["min"]=>
      int(8)
    }
    [6151737]=>
    array(3) {
      ["moda"]=>
      int(12)
      ["media"]=>
      float(33.561111111111)
      ["min"]=>
      int(10)
    }
    [6151738]=>
    array(3) {
      ["moda"]=>
      int(10)
      ["media"]=>
      float(21.936507936508)
      ["min"]=>
      int(8)
    }
    [6151739]=>
    array(3) {
      ["moda"]=>
      int(20)
      ["media"]=>
      float(29.69298245614)
      ["min"]=>
      float(8.8)
    }
    [6151740]=>
    array(3) {
      ["moda"]=>
      int(8)
      ["media"]=>
      float(11.736363636364)
      ["min"]=>
      float(6.5)
    }
    [6151741]=>
    array(3) {
      ["moda"]=>
      int(10)
      ["media"]=>
      float(18.093023255814)
      ["min"]=>
      int(8)
    }
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

## To be studied

This script can be extended to study the gasPrices in an historical perspective. It is a pity that EthGasStation does not supply archived estimations to do a comparison.


