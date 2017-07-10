<?php
require('ssp.php');
session_start();

if(!isset($_SESSION['user']) or $_SESSION['user'] != 1){
    header('Location: login.php');
    exit();
}

$msg = '';
if(isset($_POST['submit'])){
    if($_POST['tb'] == 'user_add' and $_POST['username'] != '' and $_POST['password'] != '' and $_POST['expire'] != '' and $_POST['gid'] != ''){
        $username = $_POST['username'];
        $auth1 = sha1($_POST['username']);
        $auth2 = sha1($_POST['password']);
        $gid = $_POST['gid'];
        $expire = $_POST['expire'];
        $sql_add_user = "insert into user (gid,name,auth1,auth2,expire) values ('$gid','$username','$auth1','$auth2','$expire')";
        SSP::simple('acl/acl.db', $sql_add_user);
    }
    if($_POST['tb'] == 'user_del' and $_POST['id'] != ''){
        $uid = $_POST['id'];
        $sql_del_user = "delete from user where id='$uid'";
        SSP::simple('acl/acl.db', $sql_del_user);
    }
    if($_POST['tb'] == 'user_mod' and $_POST['id'] != ''){
        $sql_mod_user = "update user set ";
        if($_POST['password'] != ''){
            $sql_mod_user .= "auth2='" . sha1($_POST['password']) . "',";
        }
        if($_POST['expire'] != ''){
            $sql_mod_user .= "expire='" . $_POST['expire'] . "',";
        }
        $sql_mod_user .= "gid='" . $_POST['gid'] . "' where id='" . $_POST['id'] . "'";
        SSP::simple('acl/acl.db', $sql_mod_user);
    }
    if($_POST['tb'] == 'level_add' and $_POST['level'] != ''){
        $level = $_POST['level'];
        $privilege = $_POST['privilege'];
        $level_msg = $_POST['level_msg'];
        $sql_add_level = "insert into level (level,privilege,msg) values ('$level','$privilege','$level_msg')";
        SSP::simple('acl/acl.db', $sql_add_level);
    }
    if($_POST['tb'] == 'level_mod' and $_POST['id'] != ''){
        $sql_mod_level = "update level set ";
        if($_POST['privilege'] != ''){
            $sql_mod_level .= "privilege='" . $_POST['privilege'] . "',";
        }
        $sql_mod_level .= "msg='" . $_POST['level_msg'] . "' where id='" . $_POST['id'] . "'";
        SSP::simple('acl/acl.db', $sql_mod_level);
    }
    if($_POST['tb'] == 'level_del' and $_POST['id'] != ''){
        $gid = $_POST['id'];
        $sql_qry_level = "select name from user where gid='$gid'";
        $user_in_level = SSP::simple('acl/acl.db', $sql_qry_level);
        if(count($user_in_level) == 0){
            $sql_del_level = "delete from level where id='$gid'";
            SSP::simple('acl/acl.db', $sql_del_level);
        }else{
            $u = '';
            foreach($user_in_level as $d){
                $u .= $d[0] . ',';
            }
            $msg = '用户' . $u . '正在使用该等级，不能删除';
        }
    }
}

$sql1 = 'select user.id,user.name,level.level,user.expire from user,level where user.gid=level.id';
$sql2 = 'select id,level,privilege,msg from level';
$data1 = SSP::simple('acl/acl.db', $sql1);
$data2 = SSP::simple('acl/acl.db', $sql2);
$user_list_html = '';
foreach($data1 as $d){
    $user_list_html .= '<option value="' . $d[0] . '">' . $d[1] . '</option>';
}
$level_list_html = '';
foreach($data2 as $d){
    $level_list_html .= '<option value="' . $d[0] . '">' . $d[1] . '</option>';
}
$user_html = '';
foreach($data1 as $d){
    if($d[0] == 1){
        $disable = 'disabled="disabled"';
    }else{
        $disable = '';
    }
    $user_html .= '<tr><td><input type="radio" name="id" value="' . $d[0] . '" ' . $disable . ' /></td>';
    $user_html .= '<td>' . $d[1] . '</td><td>' . $d[2] . '</td><td>' . $d[3] . '</td></tr>';
}
$level_html = '';
foreach($data2 as $d){
    $level_html .= '<tr><td><input type="radio" name="id" value="' . $d[0] . '" /></td>';
    $level_html .= '<td>' . $d[1] . '</td><td>' . $d[2] . '</td><td>' . $d[3] . '</td></tr>';
}

echo <<<END
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<script type="text/javascript" src="//cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<title>若海数据 - 管理</title>
</head>
<body>
<div id="container">
<fieldset style="width:98%;">
    <span><a href="index.php">返回首页</a></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><a href="../downloads/">文件管理</a></span>
</fieldset>
<br />
<fieldset style="width:98%;">
    <form method="post" action="admin.php">
    <input type="hidden" value="user_add" name="tb" />
    <h4>添加用户</h4>
    <table style="width:99%;text-align:left;">
        <tr><td>用户名 <input type="text" name="username" /></td><td>密码 <input type="text" name="password" /></td></tr>
        <tr><td>有效至 <input type="text" name="expire" /></td><td>等级 <select name="gid">${level_list_html}</select></td></tr>
        <tr><td><input type="submit" value="添加" name="submit" /></td><td></td></tr>
    </table>
    </form>
</fieldset>
<br />
<fieldset style="width:98%;">
    <form method="post" action="admin.php">
    <input type="hidden" value="user_mod" name="tb" />
    <h4>修改用户</h4>
    <table style="width:99%;text-align:left;">
        <tr><td>用户 <select name="id">${user_list_html}</select></td><td>密码 <input type="text" name="password" /></td></tr>
        <tr><td>有效至 <input type="text" name="expire" /></td><td>等级 <select name="gid">${level_list_html}</select></td></tr>
        <tr><td><input type="submit" value="修改" name="submit" /></td><td></td></tr>
    </table>
    </form>
</fieldset>
<br />
<fieldset style="width:98%;">
    <form method="post" action="admin.php">
    <input type="hidden" value="user_del" name="tb" />
    <h4>删除用户</h4>
    <table style="width:99%;text-align:left;">
        <tr><td></td><td>用户</td><td>等级</td><td>有效至</td></tr>
        ${user_html}
    </table>
    <input type="submit" value="删除" name="submit" />
    </form>
</fieldset>
<br />
<fieldset style="width:98%;">
    <form method="post" action="admin.php">
    <input type="hidden" value="level_add" name="tb" />
    <h4>添加等级</h4>
    <table style="width:99%;text-align:left;">
        <tr><td>等级 <input type="text" name="level" /></td></tr>
        <tr><td>权限 <textarea name="privilege"></textarea></td></tr>
        <tr><td>消息 <textarea name="level_msg"></textarea></td></tr>
        <tr><td><input type="submit" value="添加" name="submit" /></td></tr>
    </table>
    </form>
</fieldset>
<br />
<fieldset style="width:98%;">
    <form method="post" action="admin.php">
    <input type="hidden" value="level_mod" name="tb" />
    <h4>修改等级</h4>
    <table style="width:99%;text-align:left;">
        <tr><td>等级 <select name="id">${level_list_html}</select></td></tr>
        <tr><td>权限 <textarea name="privilege"></textarea></td></tr>
        <tr><td>消息 <textarea name="level_msg"></textarea></td></tr>
        <tr><td><input type="submit" value="修改" name="submit" /></td></tr>
    </table>
    </form>
</fieldset>
<br />
<fieldset style="width:98%;">
    <form method="post" action="admin.php">
    <input type="hidden" value="level_del" name="tb" />
    <h4>删除等级</h4>
    <table style="width:99%;text-align:left;word-break:break-all;word-wrap:break-all;">
        <tr><td></td><td>等级</td><td>权限</td><td>消息</td></tr>
        ${level_html}
    </table>
    <input type="submit" value="删除" name="submit" />
    </form>
<span style="color:red;">${msg}</span>
</fieldset>
</div>
</body>
</html>
END;
?>
