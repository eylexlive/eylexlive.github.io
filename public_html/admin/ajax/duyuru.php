<?php
  error_reporting(0);
  require_once '../../config.php';
  if(!session('login')) {
    echo alert("Session Hatası","warning text-center");
  }else{
    $uye_id = session("uye_id");
    $user = row(query("SELECT * FROM uyeler WHERE uye_id='$uye_id'"));
    if ($user["uye_rutbe"] > 0) {
      echo file_get_contents("http://v5.craftweb.co/duyuru.php");
    }else{
      echo alert("Yetki Hatası","danger text-center");
    }
  }
?>