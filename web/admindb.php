<?php
function adminer_object() {
  class AdminerSoftware extends Adminer {
    function login($login, $password) {
      return ($login == 'admin' && $password == 'starwars');
    }
    function name() {
      return 'RohanKDD';
    }
  }
  
  return new AdminerSoftware;
}

include "adminer.php";
?>
