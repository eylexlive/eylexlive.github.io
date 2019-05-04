<?php defined("ADMIN") ? null: die('Hacking?'); ?>
<?php 

  if ($user["uye_oauth_uid"] != "0") {
    session_olustur(array('two_factor' => true,'uye_o_id' => $user['uye_id']));
    
    if (isset($_SERVER['HTTP_COOKIE'])) {
      $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
      foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
      }
    }
    
    session_sil("login");
    session_sil("uye_id");
    
    go(ADMIN_URL,2);
    echo alert("Hesabınız <b>Güvenlik Moduna</b> alınmıştır. Giriş yapmak için doğrulama kodunu giriniz.","success");
  }else{
    echo alert("Güvenlik modu ayrıcalığından yararlanabilmek için, <b>İki Faktörlü Doğrulama Sistemini</b> etkinleştirin!");
  }

?>
