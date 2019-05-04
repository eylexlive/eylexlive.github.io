<?php
  error_reporting(0);
  require_once "../../config.php";
  
  if(!session('login')) {
    echo "Session Hatası"; exit;
  }else{
    $uye_id = session("uye_id");
    $user = row(query("SELECT * FROM uyeler WHERE uye_id='$uye_id'"));
    if ($user["uye_rutbe"] == 1) {
      echo rows(query("SELECT ticket_id FROM ticketler WHERE (ticket_turu = 1 or ticket_turu = 3) AND ticket_id = ticket_ana_id"));
    }
  }