<?php
header("Content-type: text/json; charset=utf-8");

$ip=$_GET['ip'];
$url="http://ip.taobao.com/service/getIpInfo.php?ip=$ip";

retry:
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_TIMEOUT, 5);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$curljson = curl_exec($curl);
curl_close($curl);
$curlobj=strtr($curljson, array("X" => ''));
$ipobj=json_decode($curlobj);
if( empty($ipobj->data->ip)) {
	$ipobj=(Object)null;
	goto retry;
}
$nettype=$ipobj->data->isp;
if( empty($nettype)) {
  $location=$ipobj->data->region . $ipobj->data->city ;
} else{
  $location=$ipobj->data->region . $ipobj->data->city . ',' . $nettype;
}
$obj=(Object)null;
$obj->region=$location;

echo json_encode($obj);
?>
