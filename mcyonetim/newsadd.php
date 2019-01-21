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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO news (title, images, subcontent, content, `key`, video, tarih, autor) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['images'], "text"),
                       GetSQLValueString($_POST['subcontent'], "text"),
                       GetSQLValueString($_POST['content'], "text"),
                       GetSQLValueString($_POST['key'], "text"),
                       GetSQLValueString($_POST['video'], "text"),
                       GetSQLValueString($_POST['tarih'], "date"),
                       GetSQLValueString($_POST['autor'], "text"));

  mysql_select_db($database_baglanti, $baglanti);
  $Result1 = mysql_query($insertSQL, $baglanti) or die(mysql_error());

  $insertGoTo = "index.php?mc=news";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<ul class="nav nav-pills">
	<li><a href="index.php?mc=news"><i class="fa fa-newspaper-o"></i> Eklenen haberler</a></li>
    <li class="active"><a href="?mc=newsadd"><i class="fa fa-plus"></i> Yeni haber Ekle</a></li>
  
</ul>
<hr />

<div class="panel panel-primary">
<?php 
  if ( isset($_POST['submit']) ){
	 header("Location: index.php?mc=news"); 
	 }?>
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-plus"></i> Yeni haber Ekle</h3>
  </div>
  <div class="panel-body">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table width="100%" align="center">
        <tr valign="baseline">
          <th width="21%" height="45" align="right">Baslık:</th>
          <td width="79%" height="45"><input class="form-control" type="text" name="title" value="" size="32" required /></td>
        </tr>
        <tr valign="baseline">
          <th height="45" align="right">Resim URL:</th>
          <td height="45">
          <div class="input-group">
    <span class="input-group-addon"><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal">Resim Yükle</button></span>
          <input class="form-control" placeholder="Yüklediğiniz Resim URL'sini giriniz" type="text" name="images" value="" size="32" required />
            </div>
          <hr />
          <strong>ÖRNEK;</strong> http://img506.yukle.tc/images/<strong class="label label-success"><strong>[resimID'si]</strong></strong>.png<br />Başka sitelerden yükle <strong><a href="https://hizliresim.com/" target="_blank">HIZLI RESİM</a></strong>
          <hr />          <a href="https://hizliresim.com/" target="_blank"></a></td>
        </tr>
        <tr valign="baseline">
          <th height="45" align="right" valign="top">Spot:</th>
          <td height="45"><textarea class="form-control" placeholder="Haber Kısa açıklama giriniz" name="subcontent" cols="50" rows="3" required ></textarea>
          <hr /></td>
        </tr>
        <tr valign="baseline">
          <th height="45" align="right" valign="top">İçerik:</th>
          <td height="45"><textarea name="content" cols="50" rows="5"></textarea>
          <script src='ckeditor/ckeditor.js' type='text/javascript'> </script>
                    <script type='text/javascript'>
//< ![CDATA[
CKEDITOR.replace( 'content' );
//]]>
                    </script>
          <hr /></td>
        </tr>
        <tr valign="baseline">
          <th height="45" align="right">Etiket:</th>
          <td height="45"><input class="form-control" type="text" name="key" value="" size="32" placeholder="Etiketleri (,) virgül ile ayırınız." /></td>
        </tr>
        <tr valign="baseline">
          <th height="45" align="right" valign="top">Video:</th>
          <td height="45"><textarea class="form-control" name="video" cols="50" rows="5"></textarea>
          <hr /></td>
        </tr>
        <tr valign="baseline">
          <th height="45" align="right">Tarih:</th>
          <td height="45"><input class="form-control" name="tarih" type="text" value="<?php echo tarihcevir(date("m/d/Y H:i")); ?>" size="32" readonly="readonly" /></td>
        </tr>
        <tr valign="baseline">
          <th height="45" align="right">&nbsp;</th>
          <td height="45"><input name="submit" class="btn btn-primary" type="submit" value="KAYDET" /> 
          Bu haberi ekleyen editör; <strong><?php echo $_SESSION['kt_username']; ?></strong></td>
        </tr>
      </table>
      <input type="hidden" name="autor" value="<?php echo $_SESSION['kt_username']; ?>" />
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Yeni Resim Ekle</h4>
      </div>
      <div class="modal-body">
        <h1>Resim Yükle</h1></center>

<form method="post" enctype="multipart/form-data" action="http://img506.yukle.tc/" target="_blank" onSubmit="return kontrol();" name="form1"> 

<table border="0"><tr><td align="center" width="310">

<input type="file"  value="Gözat" size="40" name="userfile" style="background-color: #F7F7F7; font-size: 10px; font-family: Verdana; border-style: outset; border-width: 1;cursor:hand" onChange="showoptions(this)"></font>
Maksimum resim boyutu : 5 MB 
</td><td>

<div id="kategoriform" style="display:none;">
<table border="0"><tr><td width="35"><b>Başlık</b>: </td><td>
<input type="text" style="border-color: #F7F7F7;" name="baslik" id="baslik" disabled="disabled" size="35" style="background-color: #F7F7F7; font-size: 10px; font-family: Verdana; border-style: groove; border-width: 1;">
<input name="kategori" id="kategori" disabled="disabled" type="hidden" value="17">
</td></tr></table>

</td><td>
<input type="submit" value="Yükle" name="upload" id="upload" style="background-color: #F7F7F7; font-size: 10px; font-family: Verdana; border-style: outset; border-width: 1;cursor:hand"></font>
</td></tr></table>

</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
  </div>
</div>
</body>
</html>