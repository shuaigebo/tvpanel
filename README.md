# 骆驼IPTV管理后台

- 新增天气显示
- 兼容PHP7
- 新增外部列表导入
- 支持在后台直接修改对接的apk接口
- 新增管理员登录操作记录

## 面板安装
- 下载代码
```bash
git clone https://github.com/GaHoKwan/tvpanel
```
或者
```
wget https://github.com/GaHoKwan/tvpanel/archive/master.zip && unzip master.zip
```

- 新建并导入sql目录的数据库
```bash
mysql -u帐号 -p密码 数据库名 < 数据库.sql
```

- 创建配置文件
```bash
cp .config.php config.php
```

- 修改配置文件
到[https://www.tianqiapi.com/](https://www.tianqiapi.com/)注册天气API
```
define("PANEL_MD5_KEY","");  //面板密码MD5加密秘钥
$db_host="";  //数据库服务器
$db_user="";  //数据库帐号
$db_pwd="";   //数据库密码
$db_database="";  //数据库名称
```

- 登入后台 （默认用户与密码:admin,安全码：空）
```
http://{域名}/
```
