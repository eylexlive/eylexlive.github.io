<?php require_once('../Connections/baglanti.php'); ?>
<?php
// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_baglanti, "../");
//Grand Levels: Level
$restrict->addLevel("2");
$restrict->Execute();
//End Restrict Access To Page

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_baglanti, $baglanti);
$query_ftp = "SELECT * FROM ftpsettings WHERE id = 1";
$ftp = mysql_query($query_ftp, $baglanti) or die(mysql_error());
$row_ftp = mysql_fetch_assoc($ftp);
$totalRows_ftp = mysql_num_rows($ftp);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <style>
  .ui-progressbar {
    position: relative;
  }
  .progress-label {
    position: absolute;
    left: 40%;
    top: 4px;
    font-weight: bold;
    text-shadow: 1px 1px 0 #fff;
  }
  </style>
  <script>
  $(function() {
    var progressbar = $( "#progressbar" ),
      progressLabel = $( ".progress-label" );
 
    progressbar.progressbar({
      value: false,
      change: function() {
        progressLabel.text( progressbar.progressbar( "value" ) + "%" );
      },
      complete: function() {
        progressLabel.text( "Kayıt İşlemi Başarılı!" );
      }
    });
 
    function progress() {
      var val = progressbar.progressbar( "value" ) || 0;
 
      progressbar.progressbar( "value", val + 2 );
 
      if ( val < 99 ) {
        setTimeout( progress, 80 );
      }
    }
 
    setTimeout( progress, 2000 );
  });
  </script>
</head>
<body>
<ul class="nav nav-pills">
	<li class="active"><a href="index.php?mc=ftp"><i class="fa fa-cogs"></i> FTP AYARLARI</a></li>
    <li><a href="?mc=ftplogin"><i class="fa fa-sign-in"></i> FTP GİRİŞİ</a></li>
  
</ul>
<hr />
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-file"></i> FTP AYARLARI</h3>
  </div>
  <div class="panel-body">
  <?php 
  if ( isset($_POST['submit']) ){
	
	$id       ="1";
	$ftpip    =$_POST["ftpip"];
 
	$sorgu=mysql_query("UPDATE ftpsettings SET ftpip='$ftpip' WHERE id='$id'");
	 
	if($sorgu){
		echo '<div id="progressbar"><div class="progress-label">Kayıt işlemi yapılıyor...</div></div><meta http-equiv="refresh" content="7" />

';
	}else{
		echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">KAPAT</button>
  <strong>BİR HATA OLUŞTU FTP KAYIT BİLGİLERİ GÜNCELLENEMEDİ</strong>
</div>';
	}}
  ?>
  
  
  <div class="form-group">
  
  </div>
    <form method="post">
     
  
  
    <div class="form-group">
    <label for="ftpip">FTP Sunucu Adresi (IP/Host)</label>
    <input type="text" class="form-control" name="ftpip" id="ftpip" value="<?php echo $row_ftp['ftpip']; ?>" placeholder="FTP Sunucu Adresi (IP/Host)" required />
  </div>
 
  <button type="submit" name="submit" class="btn btn-success">AYARLARI KAYDET</button><span class="label label-danger">FTP Ip adresini doğru giriniz aksi taktirde bağlanmayacaktır!</span>
  <input type="hidden" name="id" id="hiddenField" value="1"/>
    </form>
  </div>
</div>
<div class="alert alert-dismissible alert-info">
  <strong>BİLGİ!</strong> Eğer serveriniz panelin kurulu olduğu sistemdeyse localhost olarak'da ayarları gire bilirsiniz. Normal sFTP giriş bilgileride geçerlidir.
</div>

</body>
</html>
<?php
mysql_free_result($ftp);
?>
