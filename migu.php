<?php
error_reporting(0);
header('Content-Type: text/json;charset=UTF-8');

$id=$_GET['id'];
if(isset($id) && !empty($id)){
	if(strlen($id)<9){
		header('HTTP/1.0 404 Not Found');
	    exit("ID Length Less than 9 your id is $id.");
	}
	
	$url="http://webapi.miguvideo.com/gateway/playurl/v2/play/playurlh5?contId=".$id."";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_TIMEOUT, 5);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
	$curljson = curl_exec($curl);
	curl_close($curl);
	$outobj=json_decode($curljson,true);
	$playurl=$outobj['body']['urlInfo']['url'];
    header('location:'.urldecode($playurl));
	exit;
}
?>