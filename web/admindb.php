<?php
function adminer_object() {
  class AdminerSoftware extends Adminer {
    function login($login, $password) {
      return (sha1($login) == 'd033e22ae348aeb5660fc2140aec35850c4da997' && sha1($password) == '327156ab287c6aa52c8670e13163fc1bf660add4');
    }
    function name() {
      return 'RohanKDD';
    }
  }
  
  return new AdminerSoftware;
}

include "adminer.php";
?>
