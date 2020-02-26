<?php
header("Content-type: text/html; charset=utf-8");

define("APPS_SIG","12315");  //APP签名验证秘钥
define("APPS_APPNAME","");  //APP软件名
define("APPS_PACKAGENAME","");  //APP包名
define("JISU_API_KEY","");  //极速数据API秘钥
define("PANEL_MD5_KEY","tvkey_");  //面板密码MD5加密秘钥
define("MAX_SAME_IP_USER","5");  //允许相同IP登录用户最大数量
define("WEATHER_API_ID","");  //天气API_appid
define("WEATHER_API_KEY","");  //天气API_appsecret


$db_host="localhost";  //数据库服务器
$db_user="";  //数据库帐号
$db_pwd="";   //数据库密码
$db_dabse="";  //数据库名称
$conn=mysqli_connect($db_host,$db_user,$db_pwd,$db_dabse) OR die ('无法登录MYSQL服务器！');  //连接数据库对象

global $conn;
mysqli_query($GLOBALS['conn'],"SET NAMES 'UTF8'");
?>