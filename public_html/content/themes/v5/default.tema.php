<?php $tset = json_decode(mset("v5_s","ayar_format"),true); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title><?=mset("site_adi")?> - <?=mset("site_slogan")?></title>
    
    <link rel="shortcut icon" href="<?=str_replace(array("[URL]","[TEMA]"),array(URL,THEMES_PATH),mset("favicon"))?>">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=THEMES_PATH?>/css/style.css">
    
    <!-- JS Code -->
    <script src="<?=INC_PATH?>/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="<?=THEMES_PATH?>/js/jquery.sticky.js"></script>
    <script>
      $(document).ready(function(){
        $(".navbar-cw").sticky({topSpacing:0,zIndex:10});
      });
    </script>
  </head>
  <body style='background-image: url("<?=$tset["bg"]?>"); transform: none;'>
    <div class="container">
      <div class="header">
        <div class="col-md-9 col-sm-8 logo-bar">
          <a href="<?=URL?>"><img src="<?=$tset["logo"]?>" alt="Logo" class="img-responsive"></a>
        </div>
        <div class="col-md-3 col-sm-4 hidden-xs">
          <?php if (session("login")): ?>
            <?php $uye_id = session("uye_id"); $user = row(query("SELECT * FROM uyeler WHERE uye_id='$uye_id'")); ?>
            <div class="btn-group">
              <a href="#" class="btn btn-block btn-success btn-lg" data-toggle="dropdown" aria-expanded="false">
                <?=$user["uye_kadi"]?>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu" style="width: 200px;z-index: 11;">
                <li><a href="<?=URL?>/profil">Profil</a></li>
                <li><a href="<?=URL?>/kredi">Kredi Yükle</a></li>
                <li class="divider"></li>
                <li><a href="<?=URL?>/two_factor">İki Adımlı Doğrulama</a></li>
                <li class="divider"></li>
                <li><a href="<?=URL?>/kredi">Bakiye: <span class="label label-danger" style="float:right;margin-top: 2.2px;"><?=$user["uye_kredi"]?> TL</span></a></li>
              </ul>
            </div>
            <a href="<?=URL?>/cikis" class="btn btn-block btn-danger btn-lg">Çıkış Yap</a>
          <?php else: ?>
            <button class="btn btn-block btn-success btn-lg giris_yap">Giriş Yap</button>
            <a href="<?=URL?>/kayitol" class="btn btn-block btn-danger btn-lg">Kayıt Ol</a>
          <?php endif; ?>
        </div>
      </div>
      <nav class="navbar navbar-default navbar-cw" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
    
        <div class="collapse navbar-collapse" id="navbar">
          <ul class="nav navbar-nav">
            <?php 
              $mMenu = new menu();
              $mMenu->get("menu",true);
            ?>
          </ul>
          <ul class="nav navbar-nav navbar-right navbar-fixed" style="display:none;">
            <?php if (session("login")): ?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?=$user["uye_kadi"]?> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu" style="width: 200px;">
                  <li><a href="<?=URL?>/profil">Profil</a></li>
                  <li><a href="<?=URL?>/kredi">Kredi Yükle</a></li>
                  <li class="divider"></li>
                  <li><a href="<?=URL?>/two_factor">İki Adımlı Doğrulama</a></li>
                  <li class="divider"></li>
                  <li><a href="<?=URL?>/kredi">Bakiye: <span class="label label-danger" style="float:right;margin-top: 2.2px;"><?=$user["uye_kredi"]?> TL</span></a></li>
                </ul>
              </li>
              <li><a href="<?=URL?>/cikis">Çıkış Yap</a></li>
            <?php else: ?>
              <li><a class="giris_yap" href="#">Giriş Yap</a></li>
              <li><a  href="<?=URL?>/kayitol">Kayıt Ol</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </nav>
      
      <div class="row">
        <div class="col-md-8">
          <?php $cacheIMG = new ImageCache("content/cache",URL."/content/cache"); ?>
          <?php 
          $_page = get("go");
          if(!$_page){
            require_once THEMES_SET."/anasayfa.tema.php";
          }else{
            if(file_exists(THEMES_SET."/{$_page}.tema.php")){
              require_once THEMES_SET."/{$_page}.tema.php";
            }else{
              if(file_exists("content/themes/deskop_default/{$_page}.tema.php")){
                require_once "content/themes/deskop_default/{$_page}.tema.php";
              }else{
                echo alert("Dikkat et sürüden ayrılanı kurt kapar. Çok yanlış yerlere geldin...","danger");
              }
            }
          }
          ?>
        </div>
        <div class="col-md-4">
          <?php
            $sidebar = new sidebar();
            $sidebar->get("sidebar");
          ?>
        </div>
      </div>
      
      <div class="panel panel-default">
        <div class="panel-body">
          <span class="hidden-xs hidden-sm">Copyright &copy; <?=date("Y")?> <?=mset("site_adi")?>. Tüm hakları saklıdır.</span>
          <span class="hidden-md hidden-lg"><?=mset("site_adi")?></span>
          <span class="pull-right"><a href="https://craftweb.co">CraftWeb</a> <b>v<?=substr(mset("version"),0,3)?></b></span>
        </div>
      </div>
    </div>
    
    <script type="text/javascript" src="<?=THEMES_PATH?>/js/edit.js"></script>  
    
    <?php if (!session("login")): ?>
      <script type="text/javascript">
        $(".giris_yap").click(function() {
          $("#giris_yap").modal();
          $(".sonuc").html('');
          $(".gonder").prop("disabled", false);
        });
      </script>
      <div id="giris_yap" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">

          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Giriş Yap</h4>
            </div>
            <div class="modal-body">
              <div class="sonuc">
                
              </div>
              
              <form action="" method="post" onsubmit="return false;" id="giris_yap">
                <div class="form-group">
                  <label for="uye_kadi">Kullanıcı Adı</label>
                  <input type="text" class="form-control" name="uye_kadi" id="uye_kadi">
                </div>
                <div class="form-group">
                  <label for="uye_sifre">Şifre</label>
                  <input type="password" class="form-control" name="uye_sifre" id="uye_sifre">
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-block btn-success gonder" onclick="gonder()">Giriş Yap</button>
                </div>
              </form>
              <a href="<?=URL?>/sifremi_unuttum" class="btn btn-block btn-default">Şifremi Unuttum</a>
            </div>
          </div>

        </div>
        
      </div>
    <?php endif; ?>
  </body>
</html>