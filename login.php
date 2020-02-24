<?php
ini_set('display_errors',1); 
ini_set('display_startup_errors',1); 
error_reporting(E_ERROR);

include_once"aes.php";
include_once "config.php";

if(isset($_POST['login'])){

	if(!empty($_SERVER['HTTP_X_REAL_IP'])){$ip=$_SERVER['HTTP_X_REAL_IP'];}else{$ip=$_SERVER['REMOTE_ADDR'];}
	$sql = "SELECT `ip`,count(*) as num FROM `chzb_users` WHERE ip='$ip'";
	$result = mysqli_query($GLOBALS['conn'],$sql);
	if($row = mysqli_fetch_array($result)){$num=$row['num'];}
	if($num >= MAX_SAME_IP_USER){
		header('HTTP/1.1 403 Forbidden');
		mysqli_free_result($result);
		mysqli_close($GLOBALS['conn']);
		exit();
	}else{
		unset($row);
		mysqli_free_result($result);
		$json=$_POST['login'];
		$obj=json_decode($json);
		$region=$obj->region;
		$androidid=$obj->androidid;
		$mac=$obj->mac;
		$model=$obj->model;
		$nettype=$obj->nettype;
		$appname=$obj->appname;
		if(empty($region)){
			$myurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$json=file_get_contents(dirname($myurl)."/getIpInfo.php?ip=$ip");
			$obj=json_decode($json);
			$region=$ipobj->data->region . $ipobj->data->city . $ipobj->data->isp;
		}
		function genName(){
			$name=rand(1000,999999);
			$result = mysqli_query($GLOBALS['conn'],"SELECT * from chzb_users where name=$name");
			if($row=mysqli_fetch_array($result)){
				unset($row);
				mysqli_free_result($result);
				genName();
			}else{
				$result = mysqli_query($GLOBALS['conn'],"SELECT * from chzb_serialnum where sn=$name");
				if($row=mysqli_fetch_array($result)){
					unset($row);
					mysqli_free_result($result);
					genName();
				}else{
					mysqli_free_result($result);
					return $name;
				}
			}
		}
	
		//status=1,正常用户；
		//status=0,停用用户;
		//status=-1,未授权用户
		//status=999为永不到期
		$nowtime=time();
	
		//androidID是否匹配
		$sql = "SELECT name,status,exp,deviceid,model FROM chzb_users where deviceid='$androidid'";
		$result = mysqli_query($GLOBALS['conn'],$sql);
		if($row = mysqli_fetch_array($result)){
			//匹配成功
			$days=ceil(($row['exp']-time())/86400);
			$status=intval($row['status']);
			$name=$row['name'];
			$exp=$row["exp"];  //收视期限，时间戳
			$status2=$status;
			if($days>0&&$status==-1){
				$status=1;
			}
			//更新位置，登陆时间
			mysqli_query($GLOBALS['conn'],"UPDATE chzb_users set region='$region',ip='$ip',lasttime=$nowtime where  deviceid='$androidid'");
			//生成用户访问记录
			$result=mysqli_query($GLOBALS['conn'],"SELECT logintime from chzb_loginrec where deviceid='$androidid' and ip='$ip'");
			if($row=mysqli_fetch_array($result)){//数据库中找到该用户该IP的登陆记录
				mysqli_query($GLOBALS['conn'],"UPDATE chzb_loginrec set logintime=$nowtime where deviceid='$androidid' and ip='$ip'");
			}else{
				mysqli_query($GLOBALS['conn'],"INSERT into chzb_loginrec values($name,'$androidid','$mac','$model','$ip','$region','$nowtime')");
			}
			mysqli_free_result($result);
		}else{
			//用户验证失败，识别用户信息存入后台
			$name=genName();
			$sql = "SELECT trialdays FROM chzb_appdata";
			$result = mysqli_query($GLOBALS['conn'],$sql);
			if($row = mysqli_fetch_array($result)) {
				$days=$row['trialdays']; 
			}else{
				$days=0;
			}
			mysqli_free_result($result);
			if($days>0){
				$status=-1;
				$marks='试用';
			}elseif($days=="-999") {
				$status=-1;
				$marks='免费用户';
			}else{
				$status=-1;
				$marks='未授权';
			}
			$status2=$status;
			$exp=strtotime(date("Y-m-d"),time())+86400*$days;
			mysqli_query($GLOBALS['conn'],"INSERT into chzb_users (name,mac,deviceid,model,exp,ip,status,region,lasttime,marks) values($name,'$mac','$androidid','$model',$exp,'$ip',$status,'$region',$nowtime,'$marks')");
			if($days>0&&$status==-1)$status=1;
		}
		unset($row);
		mysqli_free_result($result);
	
		$sql = "SELECT dataver,appver,setver,adtext,qqinfo,showtime,showinterval,dataurl,appurl,decoder,buffTimeOut,tiploading,tipusernoreg,tipuserexpired,tipuserforbidden,needauthor,autoupdate,randkey,updateinterval,trialdays FROM chzb_appdata";
		$result = mysqli_query($GLOBALS['conn'],$sql);
		if($row = mysqli_fetch_array($result)) {
			$dataver=$row['dataver'];
			$appver=$row['appver']; 
			$setver=$row['setver'];
			$adtext=$row['adtext'];
			$qqinfo=$row['qqinfo'];
			$showtime=$row['showtime'];
			$showinterval=$row['showinterval'];
			$decoder=$row['decoder'];
			$buffTimeOut=$row['buffTimeOut'];
			$tiploading=$row['tiploading'];
			$tipusernoreg=$row['tipusernoreg'];
			$tipuserexpired='当前账号'.$name.'，'.$row['tipuserexpired'];
			$tipuserforbidden='当前账号'.$name.'，'.$row['tipuserforbidden'];
			$needauthor=$row['needauthor'];
			$autoupdate=$row['autoupdate'];
			$randkey=$row['randkey'];
			$updateinterval=$row['updateinterval'];
			$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]; 
			$dataurl=dirname($url)."/data.php";
			$appUrl=$row['appurl'];
			$isfreeuser=$row["trialdays"];   //试用的天数   -999为免费无限期试用
		}
		unset($row);
		mysqli_free_result($result);
	
		if($needauthor==0 || ($status==-1 && $isfreeuser=="-999") ){
			$status=999;
		}
	
		if($status<1){
			$dataurl='';
			$appUrl='';
		}
	
		$objres= array('status' => $status, 'dataurl'=>$dataurl,'appurl'=>$appUrl,'dataver' =>$dataver,'appver'=>$appver,'setver'=>$setver,'adtext'=>$adtext,'showinterval'=>$showinterval,'categoryCount'=>0,'exp' => $days,'ip'=>$ip,'showtime'=>$showtime ,'id'=>$name,'decoder'=>$decoder,'buffTimeOut'=>$buffTimeOut,'tipusernoreg'=>$tipusernoreg,'tiploading'=>$tiploading,'tipuserforbidden'=>$tipuserforbidden,'tipuserexpired'=>$tipuserexpired,'qqinfo'=>$qqinfo,'arrsrc'=>$src,'location'=>$region,'nettype'=>$nettype,'autoupdate'=>$autoupdate,'updateinterval'=>$updateinterval,'randkey'=>$randkey,'exps'=>$exp,'stus'=>$status2);
		$objres=str_replace("\\/", "/", json_encode($objres,JSON_UNESCAPED_UNICODE));
		$key=substr($key,5,16);
		$aes2 = new Aes($key);
		$encrypted =$aes2->encrypt($objres);
		unset($objres);
	
		echo $encrypted;
		mysqli_close($GLOBALS['conn']);
	}
    
}else{

  mysqli_close($GLOBALS['conn']);
  exit();

}
?>