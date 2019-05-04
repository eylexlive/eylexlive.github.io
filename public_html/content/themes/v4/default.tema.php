<?php $tset = json_decode(mset("v4_s","ayar_format"),true); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title><?=mset("site_adi")?> - <?=mset("site_slogan")?></title>
    
    <link rel="shortcut icon" href="<?=str_replace(array("[URL]","[TEMA]"),array(URL,THEMES_PATH),mset("favicon"))?>">
    
    <link rel="stylesheet" href="<?=THEMES_PATH?>/css/bootstrap.css">
    <link rel="stylesheet" href="<?=THEMES_PATH?>/css/bootflat.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=THEMES_PATH?>/css/sweetalert.css">
    <link rel="stylesheet" href="<?=THEMES_PATH?>/css/style.css">
    
    <!-- JS Code -->
    <script type="text/javascript" src="<?=INC_PATH?>/js/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=THEMES_PATH?>/js/clipboard.js"></script>
    <script type="text/javascript" src="<?=THEMES_PATH?>/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="<?=THEMES_PATH?>/js/edit.js"></script>
  </head>
  <body style="background-image: url('<?=isset($tset["bg"])?$tset["bg"]:null?>');">
    
    <div class="container-fluid" style="background-color:#333333;">
      <div class="<?=($tset["boyut"] == "1")?"":"container"?>">
        <ul class="menu">
          <?php 
            $mMenu = new menu();
            $mMenu->get("menu");
          ?>
          <li class="pull-right ipbutton"><a style="cursor: pointer;"><i class="fa fa-fw fa-gamepad "></i> <?=mset("sunucu_ip")?></a></li>
        </ul>
      </div>
    </div>
    <?php if ($tset["logo"]): ?>
      <div class="spacer"></div>
      
      <div class="col-md-12 text-center" id="logo" style="margin-bottom: 40px;">
        <a href="<?=URL?>"><img src="<?=str_replace(array("[URL]","[TEMA]"),array(URL,THEMES_PATH),$tset["logo"])?>" width="50%"></a>
      </div>
    <?php endif; ?>
    
    <div class="spacer"></div>
     
    <div class="<?=($tset["boyut"] == "1")?"container-fluid":"container"?>">
      
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
          <div class="panel panel-default">
          	<div class="panel-heading">
          	  <h3 class="panel-title">Son Kayıt Olanlar</h3>
          	</div>
          	<div class="table-responsive" style="font-size:0.9em">
          	  <table class="table table-striped table-bordered">
          	    <thead>
          	      <tr>
          	        <th class="text-center">#</th>
          	        <th>Üye Adı</th>
          	      </tr>
          	    </thead>
          	    <tbody>
          	       <?php
          	    $query = query("SELECT * FROM uyeler ORDER BY uye_id DESC LIMIT 5");
          	    while($row = row($query)){
          	  ?>
          	  <tr>
          	    <td class="text-center"><img src="http://cravatar.eu/avatar/<?php echo $row["uye_kadi"]; ?>/20.png"></td>
          	    <td><?php echo $row["uye_kadi"]; ?></td>
          	  </tr>
          	  <?php } ?>
          	    </tbody>
          	  </table>
          	</div>
          </div>
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
    
  </body>
</html>