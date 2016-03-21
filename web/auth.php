<?php
require('ssp.php');
session_start();
$user_valid = false;
$user_name = '';
$user_expire = '';
$user_level = '';
$user_priv = array();

if(!isset($_SESSION['user'])){
    header('Location: login.php');
    exit();
}else{
    $sql = "select user.name,user.expire,level.level,level.privilege from user,level where user.gid=level.id and user.id='" . $_SESSION['user'] . "'";
    $priv_data = SSP::simple('acl.db', $sql);
    $user_name = $priv_data[0][0];
    $user_expire = $priv_data[0][1];
    if(time() < strtotime($user_expire)) {
        $user_valid = true;
    }
    $user_level = $priv_data[0][2];
    $user_priv = explode(";",$priv_data[0][3]);
}

?>
