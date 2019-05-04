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
    <script type="text/javascript" src="<?=INC_PATH?>/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body>
    
    <div class="container">
      <nav class="navbar navbar-default navbar-cw" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?=mset("site_adi")?></a>
          </div>
      
          <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav">
              <?php 
                $mMenu = new menu();
                $mMenu->get("menu",false);
              ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <?php if (session("login")): ?>
                <li><a href="<?=URL?>/profil">Profil</a></li>
              <?php else: ?>
                <li><a href="<?=URL?>/kayitol">Kayıt Ol</a></li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </nav>
      
      <div class="row">
        <div class="col-md-8">
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
  </body>
</html>