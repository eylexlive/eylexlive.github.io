<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?=mset("site_adi")?> - <?=mset("site_slogan")?></title>
    
    <link rel="shortcut icon" href="<?=str_replace(array("[URL]","[TEMA]"),array(URL,THEMES_PATH),mset("favicon"))?>">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    
    <link rel="stylesheet" href="<?=THEMES_PATH?>/css/jquery.mobile.min.css">
    <link rel="stylesheet" href="<?=THEMES_PATH?>/css/style.css">
    <script src="<?=INC_PATH?>/js/jquery.min.js"></script>
    <script src="<?=INC_PATH?>/js/jquery.mobile.min.js"></script>
  </head>
  <body>
    <div data-role="page" id="pageone">
      <div data-role="panel" id="menuPanel"> 
        <ul data-role="listview" data-inset="true" data-shadow="false">
          <?php 
          function menu_get($menu_slug='',$id=0,$setting='') {
            $get_query = query("SELECT * FROM menu WHERE menu_slug='$menu_slug' AND menu_ust_id='$id' ORDER BY menu_sira ASC");
            if (rows($get_query)) {
              while ($row = row($get_query)) {
                $menu_id = $row["menu_id"];
                $get_json = json_decode($row["menu_json"],true);
                $get_query_sub = query("SELECT * FROM menu WHERE menu_slug='$menu_slug' AND menu_ust_id='$menu_id' ORDER BY menu_sira ASC");
                if (rows($get_query_sub)) {
                  echo "<li data-role='collapsible' data-iconpos='right' data-inset='false'>";
                    echo "<h2>".$get_json['title']."</h2>";
                    echo "<ul data-role='listview' data-theme='b'>";
                      menu_get($menu_slug,$row["menu_id"]);
                    echo "</ul>";
                  echo "</li>";
                }else{
                  $get_url = str_ireplace("[URL]",URL,$get_json["url"]);
                  $get_target = $get_json["blank"] == "1" ? "target='_blank'":null;
                  echo "<li><a href='$get_url/?cw=".rasgeleSifre(6)."' $get_target>".$get_json['title']."</a></li>";
                }
              }
            }
          }
          menu_get("menu");
          ?>
        </ul>
      </div>

      <div data-role="header" data-position="fixed">
        <a href="#menuPanel" class="ui-btn-left ui-btn ui-btn-icon-left ui-icon-bars">Menü</a>
        <h1><?=mset("site_adi")?></h1>
        <?php if (!session("login")): ?>
          <a href="<?=URL?>/giris" class="ui-btn ui-btn-right ui-btn-icon-right ui-icon-lock">Giriş</a>
        <?php else: ?>
          <a href="#profilPanel" class="ui-btn ui-btn-right ui-btn-icon-right ui-icon-user">Profil</a>
        <?php endif; ?>
      </div>
      
      <?php if (session("login")): ?>
        <?php $uye_row = row(query("SELECT * FROM uyeler WHERE uye_id = '".session('uye_id')."'")); ?>
        <div data-role="panel" data-theme="b" data-position="right" id="profilPanel"> 
          <div class="ui-body ui-body-a ui-corner-all">
            <div class="row">
              <div class="col-xs-3" style="padding-left: 10px;">
                <img src="https://cravatar.eu/avatar/<?=$uye_row["uye_kadi"]?>/200.png" alt="<?=$uye_row["uye_kadi"]?>" class="img-responsive img-rounded">
              </div>
              <div>
                <b><?=$uye_row["uye_kadi"]?></b> <br>
                <span class="label label-danger" style="font-size:13px">
                  Bakiyen:
                  <strong><?=$uye_row["uye_kredi"]?> TL</strong>
                </span>
              </div>
            </div>
          </div>
          <ul data-role="listview" data-inset="true">
            <li><a href="<?=URL?>/profil">Profil</a></li>
            <li><a href="<?=URL?>/kredi">Kredi Yükle</a></li>
            <li><a href="<?=URL?>/cikis">Çıkış Yap</a></li>
          </ul>
        </div> 
      <?php endif; ?>

      <div data-role="main" class="ui-content">
        <?php 
        $_page = get("go");
        if(!$_page){
          require_once THEMES_SET."/anasayfa.tema.php";
        }else{
          if(file_exists(THEMES_SET."/{$_page}.tema.php")){
            require_once THEMES_SET."/{$_page}.tema.php";
          }else{
            echo alert("Dikkat et sürüden ayrılanı kurt kapar. Çok yanlış yerlere geldin...","danger");
          }
        }
        ?>
      </div>

      <div data-role="footer" data-position="fixed">
        <h1><a href="https://craftweb.co">CraftWeb</a> <b>v<?=substr(mset("version"),0,3)?></b></h1>
      </div> 
    </div>
  </body>
</html>
<?php
if (!session("login") AND cookie("CWACCOUNTID")) {
  $uye_token = @htmlspecialchars(addslashes(trim(cookie("CWACCOUNTID"))));
  $cookie = query("SELECT * FROM uyeler WHERE uye_token = '$uye_token'");
  if(rows($cookie) > 0){
    $row = row($cookie);
    session_olustur(array(
      'login' => true,
      'uye_id' => $row['uye_id'],
      'uye_hesap_tur' => $row['uye_hesap_tur']
    ));
    cookie_olustur("CWACCOUNTID",$row["uye_token"],"600000");
    go_js(URL);
  }else{
    cookie_sil("CWACCOUNTID");
  }
}
?>