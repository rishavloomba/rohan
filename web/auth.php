<?php
require('ssp.php');
session_start();
$user_valid = false;
$user_name = '';
$user_expire = '';
$user_level = '';
$user_priv = '';
$user_msg = '';

if(!isset($_SESSION['user'])){
    header('Location: login.php');
    exit();
}else{
    $sql = "select user.name,user.expire,level.level,level.privilege,level.msg from user,level where user.gid=level.id and user.id='" . $_SESSION['user'] . "'";
    $priv_data = SSP::simple('acl/acl.db', $sql);
    $user_name = $priv_data[0][0];
    $user_expire = $priv_data[0][1];
    if(time() < strtotime($user_expire)) {
        $user_valid = true;
    }
    $user_level = $priv_data[0][2];
    $user_priv = $priv_data[0][3];
    $user_msg = $priv_data[0][4];
}

?>
