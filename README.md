# 骆驼IPTV管理后台

- 新增EPG支持
- 兼容PHP7
- 去除无用的功能和代码

## 面板安装
- 下载代码
```bash
git clone https://github.com/GaHoKwan/tvpanel
or
wget https://github.com/GaHoKwan/tvpanel/archive/master.zip && unzip master.zip
```
- 新建并导入sql目录的数据库
```bash
mysql -u帐号 -p密码 数据库名 < 数据库.sql
```
- 修改config.php
```bash
define("APPS_APPNAME",""); //客户端软件名
define("APPS_PACKAGENAME",""); //客户端软件包名
define("PANEL_MD5_KEY","");  //面板密码MD5加密秘钥
$db_host="";  //数据库服务器
$db_user="";  //数据库帐号
$db_pwd="";   //数据库密码
$db_dabse="";  //数据库名称
```

- 登入后台 （默认用户与密码:admin）
```
http://{域名}/admin/userlogin.php
```

## 客户端对接（提示：act3.php -> active.php  ， login3.php -> login.php）
- 无教程，如需指导搭建请扫码联系微信，有偿服务。

![image](https://github.com/GaHoKwan/tvpanel/raw/master/wechat.jpeg)