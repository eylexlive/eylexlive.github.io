<?php defined("ADMIN") ? null: die('Hacking?'); ?>
<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">

    <title>CraftWeb Admin Panel</title>

    <meta name="description" content="CraftWeb Minecraft Scripti v5 Admin Panel Sayfası">
    <meta name="author" content="CraftWeb">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Stylesheets -->
    <!-- Web fonts -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">

    <!-- Bootstrap and OneUI CSS framework -->
    <link rel="stylesheet" href="<?=ADMIN_URL?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" id="css-main" href="<?=ADMIN_URL?>/assets/css/oneui.min.css">
    <link rel="stylesheet" id="css-main" href="<?=ADMIN_URL?>/assets/css/style.css">
  </head>
  <body>
    <div class="content overflow-hidden">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <div class="block block-themed animated fadeIn">
                    <div class="block-header bg-primary">
                        <ul class="block-options">
                            <li>
                                <a href="<?=URL?>/kayitol">Kayıt Ol</a>
                            </li>
                        </ul>
                        <h3 class="block-title">Giriş Yap</h3>
                    </div>
                    <div class="block-content block-content-full block-content-narrow">
                        <h1 class="h2 font-w600 push-30-t push-5">CraftWeb</h1>
                        <p>Hoşgeldin, lütfen giriş yapın.</p>

                        <!-- Login Form -->
                        <?php 
                          if (!session("login") AND cookie("CWACCOUNTID")) {
                            $uye_token = @htmlspecialchars(addslashes(trim(cookie("CWACCOUNTID"))));
                            $cookie = query("SELECT * FROM uyeler WHERE uye_token = '$uye_token'");
                            if(rows($cookie) > 0){
                              $row = row($cookie);
                              session_olustur(array(
                                'login' => true,
                                'uye_id' => $row['uye_id']
                              ));
                              cookie_olustur("CWACCOUNTID",$row["uye_token"],"600000");
                              go(ANLIK_URL);
                            }else{
                              cookie_sil("CWACCOUNTID");
                            }
                          }
                        ?>
                        <?php
                          if(!session("login") AND !cookie("CWACCOUNTID")) {
                            if (isset($_POST["giris"])) {
                              $uye_kadi = post("uye_kadi");
                              $uye_sifre = post("uye_sifre");
                              $beni_hatirla = post("beni_hatirla");
                              if (!$uye_kadi OR !$uye_sifre) {
                                  echo alert("Lütfen boş alan bırakmayınız.", "danger");
                              }else{
                                $varmi = query("SELECT * FROM uyeler WHERE uye_kadi = '$uye_kadi'");
                                if (rows($varmi) < 1) {
                                    echo alert("Böyle bir oyuncu bulunamadı.", "danger");
                                }else{
                                  $row = row($varmi);
                                  
                                  if (empty($row["uye_token"])) {
                                    $token = rasgeleSifre(32);
                                    $token_ekle = query("UPDATE uyeler SET uye_token = '$token' WHERE uye_id='".$row["uye_id"]."'");
                                    if ($token_ekle) {
                                      $row["uye_token"] = $token;
                                    }else{
                                      echo alert("Bir hata meydana geldi. Lütfen daha sonra tekrar deneyiniz.","danger");
                                    }
                                  }
                                  
                                  if (mset("authme_sifre") == "sha256"){ $k_sifre = sha256Kontrol($uye_sifre,$row["uye_sifre"]); }else{ $k_sifre = ($row["uye_sifre"] != md5($uye_sifre))?false:true; }
                                  if ($k_sifre == false) {
                                      echo alert("Yazdığınız parola uyuşmuyor.", "danger");
                                  }else{
                                    if ($row["uye_oauth_uid"] != "0") {
                                      session_olustur(array('two_factor' => true,'uye_o_id' => $row['uye_id']));
                                    }else{
                                      session_olustur(array('login' => true,'uye_id' => $row['uye_id']));
                                      if ($beni_hatirla == 1) {
                                          cookie_olustur("CWACCOUNTID", $row["uye_token"], "600000");
                                      }else{
                                          cookie_sil("CWACCOUNTID");
                                      }
                                      echo alert("Giriş işlemi başarılı. Sayfa Yenileniyor...", "success");
                                      go(ANLIK_URL, 2);
                                    }
                                  }
                                }
                              }
                            }
                          }
                        ?>
                        <form class="js-validation-login form-horizontal push-30-t push-50" id="login-form" action="" method="post">
                          <?php if (session("two_factor") == true): ?>
                            <?php
                              if (isset($_POST["kontrol"])) {
                                require_once '../includes/class/GoogleAuthenticator.php';
                                $ga = new PHPGangsta_GoogleAuthenticator();
                                
                                $oneCode = post("code1").post("code2").post("code3").post("code4").post("code5").post("code6");
                                $oneCode = empty($oneCode)?"999999":$oneCode;
                                $uyeQ = query("SELECT * FROM uyeler WHERE uye_id = '".session("uye_o_id")."'");
                                $uRow = row($uyeQ);
                                
                                $checkResult = $ga->verifyCode($uRow["uye_oauth_uid"], $oneCode, 2);
                                if ($checkResult) {
                                  session_sil("two_factor");
                                  session_sil("uye_o_id");
                                  
                                  session_olustur(array(
                                    'login' => true,
                                    'uye_id' => $uRow['uye_id']
                                  ));
                                  cookie_olustur("CWACCOUNTID", $uRow["uye_token"], "600000");
                                  echo alert("Giriş işlemi başarılı. Sayfa Yenileniyor...", "success");
                                  go(ANLIK_URL, 2);
                                }else{
                                  echo alert("Güvenlik Kodu Hatalı!", "danger");
                                }
                              }
                            ?>
                            <div class="form-group">
                                <div class="col-xs-12">
                                  <label for="login-username">Güvenlik Kodu:</label>
                                  <div class="gkod">
                                    <input type="text" class="form-control input-lg" maxlength="1" name="code1">
                                    <input type="text" class="form-control input-lg" maxlength="1" name="code2">
                                    <input type="text" class="form-control input-lg" maxlength="1" name="code3">
                                    <span class="cizgi">-</span>
                                    <input type="text" class="form-control input-lg" maxlength="1" name="code4">
                                    <input type="text" class="form-control input-lg" maxlength="1" name="code5">
                                    <input type="text" class="form-control input-lg" maxlength="1" name="code6">
                                  </div>
                                </div>
                            </div>
                            <p>Authenticator Uygulamanızdaki 6 Haneli giriş kodunu girmelisiniz.</p>
                            <div class="form-group">
                                <div class="col-xs-12 push-30-b">
                                    <button class="btn btn-block btn-primary pull-left" name="kontrol" value="true" type="submit">Giriş Yap</button>
                                </div>
                            </div>
                          <?php else: ?>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material form-material-primary floating">
                                        <input class="form-control" type="text" name="uye_kadi" value="<?=isset($_GET["kadi"])?get("kadi"):null?>">
                                        <label for="login-username">Kullanıcı Adı</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material form-material-primary floating">
                                        <input class="form-control" type="password" name="uye_sifre">
                                        <label for="login-password">Parola</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label class="css-input switch switch-sm switch-primary">
                                        <input type="checkbox" name="beni_hatirla"><span></span> Beni Hatırla
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-30-b">
                                    <button class="btn btn-block btn-primary pull-left" name="giris" value="giris" type="submit">Giriş Yap</button>
                                </div>
                            </div>
                          <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Login Content -->

    <!-- Login Footer -->
    <div class="push-10-t text-center animated fadeInUp">
        <small class="text-muted font-w600">2014-2017 &copy; CraftWeb v5</small>
    </div>
    <!-- END Login Footer -->

    <!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
    <script src="<?=ADMIN_URL?>/assets/js/oneui.min.js"></script>
    <script type="text/javascript">
    var input = $(".gkod input");

    for (var i = 0; i < input.length; i++) {
      input[i].index = i;
      input[i].oncopy = function(e) {
        return false;
      }
      input[i].oncut = function(e) {
        return false;
      }
      input[i].onpaste = function(e) {
        return false;
      }
    }

    for (var i = 0; i < input.length; i++) {
      input[i].onkeyup = function(e) {
        if (e.keyCode == 8) {
          input[this.index - 1].focus();
        }
        if (/[0-9]/.test(this.value)) {
          if (input.length != this.index + 1) {
            input[this.index + 1].focus();
          }else{
            $("button[name=kontrol]").click();
          }
        } else {
          this.value = "";
        }
      }
    }
    </script>
  </body>
</html>