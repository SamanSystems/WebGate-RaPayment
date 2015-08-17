<?php

/*

This script demonstrates querying account balance
using PerfectMoney API interface.

*/

// trying to open URL to process PerfectMoney Spend request
$f=fopen('https://perfectmoney.is/acct/balance.asp?AccountID=10000&PassPhrase=111111', 'rb');

if($f===false){
   echo 'error openning url';
}

// getting data
$out=array(); $out="";
while(!feof($f)) $out.=fgets($f);

fclose($f);

// searching for hidden fields
if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $out, $result, PREG_SET_ORDER)){
   echo 'Ivalid output';
   exit;
}

// putting data to array
$ar="";
foreach($result as $item){
   $key=$item[1];
   $ar[$key]=$item[2];
}

echo '<pre>';
print_r($ar);
echo '</pre>';

?>