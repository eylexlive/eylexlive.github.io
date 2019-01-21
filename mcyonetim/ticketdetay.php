
<?php require_once('../Connections/baglanti.php'); 
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_baglanti, "../");
//Grand Levels: Level
$restrict->addLevel("2");
$restrict->Execute();
//End Restrict Access To Page

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("mesaj", true, "text", "", "", "", "Lütfen mesajınızı belirtiniz...");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_ticketyanit = new tNG_insert($conn_baglanti);
$tNGs->addTransaction($ins_ticketyanit);
// Register triggers
$ins_ticketyanit->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_ticketyanit->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_ticketyanit->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=ticketdetay&id={GET.id}&ticID={GET.id}");
// Add columns
$ins_ticketyanit->setTable("ticketyanit");
$ins_ticketyanit->addColumn("ticketID", "NUMERIC_TYPE", "POST", "ticketID");
$ins_ticketyanit->addColumn("musteriID", "NUMERIC_TYPE", "POST", "musteriID");
$ins_ticketyanit->addColumn("yetkiliID", "NUMERIC_TYPE", "POST", "yetkiliID");
$ins_ticketyanit->addColumn("musteriadi", "STRING_TYPE", "POST", "musteriadi");
$ins_ticketyanit->addColumn("yetkiliadi", "STRING_TYPE", "POST", "yetkiliadi");
$ins_ticketyanit->addColumn("mesaj", "STRING_TYPE", "POST", "mesaj");
$ins_ticketyanit->addColumn("eposta", "STRING_TYPE", "POST", "eposta");
$ins_ticketyanit->addColumn("tarih", "STRING_TYPE", "POST", "tarih");
$ins_ticketyanit->addColumn("durum", "STRING_TYPE", "POST", "durum");
$ins_ticketyanit->setPrimaryKey("id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsticketyanit = $tNGs->getRecordset("ticketyanit");
$row_rsticketyanit = mysql_fetch_assoc($rsticketyanit);
$totalRows_rsticketyanit = mysql_num_rows($rsticketyanit);
?>
<?php
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
?>
<?php
$colname_ticgcvp = "-1";
if (isset($_GET['id'])) {
  $colname_ticgcvp = $_GET['id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_ticgcvp = sprintf("SELECT * FROM ticket WHERE id = %s", GetSQLValueString($colname_ticgcvp, "int"));
$ticgcvp = mysql_query($query_ticgcvp, $baglanti) or die(mysql_error());
$row_ticgcvp = mysql_fetch_assoc($ticgcvp);
$totalRows_ticgcvp = mysql_num_rows($ticgcvp);

$colname_desdizid = "-1";
if (isset($_GET['ticID'])) {
  $colname_desdizid = $_GET['ticID'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_desdizid = sprintf("SELECT * FROM ticketyanit WHERE ticketID = %s ORDER BY id DESC", GetSQLValueString($colname_desdizid, "int"));
$desdizid = mysql_query($query_desdizid, $baglanti) or die(mysql_error());
$row_desdizid = mysql_fetch_assoc($desdizid);
$totalRows_desdizid = mysql_num_rows($desdizid);
?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE ticket SET durum=%s WHERE id=%s",
                       GetSQLValueString($_POST['durumgel'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_baglanti, $baglanti);
  $Result1 = mysql_query($updateSQL, $baglanti) or die(mysql_error());

  
}

$colname_uptic = "-1";
if (isset($_GET['ticID'])) {
  $colname_uptic = $_GET['ticID'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_uptic = sprintf("SELECT * FROM ticket WHERE id = %s", GetSQLValueString($colname_uptic, "int"));
$uptic = mysql_query($query_uptic, $baglanti) or die(mysql_error());
$row_uptic = mysql_fetch_assoc($uptic);
$totalRows_uptic = mysql_num_rows($uptic);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<style>
		.comments .media-heading {
    margin-top: 25px;
}

.comments .comment-info {
    margin-left: 6px;
    margin-top: 21px;
}

.comments .comment-info .btn {
    font-size: 0.8em;
}

.comments .comment-info .fa {
    line-height: 10px;
}

.comments .media-body p {
    position: relative;
    background: #F7F7F7;
    padding: 15px;
    margin-top: 50px;
}

.comments .media-body p::before {
    background-color: #F7F7F7;
    box-shadow: -2px 2px 2px 0 rgba( 178, 178, 178, .4 );
    content: "\00a0";
    display: block;
    height: 30px;
    left: 20px;
    position: absolute;
    top: -13px;
    transform: rotate( 135deg );
    width:  30px;
}

.white {
    color: #fff;
}
	</style>
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
</head>
<body>
<ul class="nav nav-pills">
	<li><a href="index.php?mc=ticket"><i class="fa fa-ticket"></i> Cevap Bekleyen Destek</a></li>
  <li><a href="index.php?mc=ticketclose"><i class="fa fa-ticket"></i>  Tüm Ticketler</a></li>
  
</ul>

<p class="well"><span class="label label-primary"><?php echo $row_ticgcvp['destektarih']; ?></span> - Departman : <span class="label label-primary"><?php echo $row_ticgcvp['departman']; ?></span> - Durum : 
<?php 

if ($row_ticgcvp['durum'] == '0') 
{ 
    echo ' <span class="btn btn-danger btn-xs">Cevap Bekliyor</span> '; 
} 
elseif ($row_ticgcvp['durum'] == '1') 
{ 
    echo '<span class="btn btn-success btn-xs">Cevap Landı</span>'; 
} 
elseif ($row_ticgcvp['durum'] == '2') 
{ 
    echo '<span class="btn btn-default btn-xs">KAPANDI</span>'; 
} 
else 
{ 
    echo 'HATA'; 
} 

?>
<form id="form1" name="form1" method="POST" >
    <select name="durumgel" id="durumgel">
    <option>--- Lütfen Talebi cevapladıgınızda burdaki durumu değiştiriniz...</option>
      <option value="1">Aktif</option>
      <option value="2">Kapalı</option>
      <option value="0">Cevap Bekliyor</option>
    </select>
  
  
    <input type="submit" name="tamam" id="tamam" value="Güncelle" />
  
  <input name="id" type="hidden" id="id" value="<?php echo $_GET['ticID']; ?>" />
  <input type="hidden" name="MM_update" value="form1" /></p>
</form>  

<?php if ($totalRows_desdizid > 0) { // Show if recordset not empty ?>
  <ul class="media-list comments">
    <?php do { ?>
      <li class="media"><a name="Commend" id="Commend<?php echo $row_desdizid['id']; ?>"></a>
        <a class="pull-left" href="#">
        <img class="media-object img-circle img-thumbnail" src="http://snipplicious.com/images/guest.png" width="64" alt="<?php echo $row_ticgcvp['baslik']; ?>">
        </a>
        <div class="media-body">
          <h5 class="media-heading pull-left"><?php echo $row_desdizid['yetkiliadi']; ?> | </h5>
          <div class="comment-info pull-left">
            <div class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Sent <?php echo $row_desdizid['tarih']; ?>"><i class="fa fa-user"></i></div>
            <div class="btn btn-primary btn-xs"><a class="fa fa-envelope white" href="<?php echo $row_desdizid['eposta']; ?>"></a></div>
            <div class="btn btn-default btn-xs"><i class="fa fa-clock-o"></i> <?php echo $row_desdizid['tarih']; ?></div>
          </div>
          <br class="clearfix">
          <p class="well"><?php echo htmlspecialchars($row_desdizid['mesaj']); ?></p>
        </div>
      </li>
      <?php } while ($row_desdizid = mysql_fetch_assoc($desdizid)); ?>
      
  </ul>
  <?php } // Show if recordset not empty ?>
<hr>
<h4 id="addComment">Cevap Yaz:</h4>
    <table width="100%" border="0">
  <tr>
    <td valign="top"><a name="a" id="CavapYaz"></a>
      <p class="well"><strong><em>Destek Ana Mesaj;</em></strong> <?php echo $row_ticgcvp['mesaj']; ?></p>
    
      <form name="form1" method="post" id="form1" action="index.php?mc=ticketdetay&id=<?php echo $_GET['id']; ?>&ticID=<?php echo $_GET['id']; ?>">
        <table width="100%" cellpadding="2" cellspacing="0" class="KT_tngtable">
          <tr>
            <td width="18%" height="45" class="KT_th"><label for="musteriadi">Musteri Adi:</label></td>
            <td width="82%" height="45"><input name="musteriadi" type="text" id="musteriadi" value="<?php echo $row_ticgcvp['isim']; ?><?php echo KT_escapeAttribute($row_rsticketyanit['musteriadi']); ?>" size="32" readonly="readonly" />
              <?php echo $tNGs->displayFieldHint("musteriadi");?> <?php echo $tNGs->displayFieldError("ticketyanit", "musteriadi"); ?></td>
          </tr>
          <tr>
            <td height="45" class="KT_th"><label for="yetkiliadi">Yetkili Adi:</label></td>
            <td height="45"><input name="yetkiliadi" type="text" id="yetkiliadi" value="<?php echo $_SESSION['kt_username']; ?> || Yetkili<?php echo KT_escapeAttribute($row_rsticketyanit['yetkiliadi']); ?>" size="32" readonly="readonly" />
              <?php echo $tNGs->displayFieldHint("yetkiliadi");?> <?php echo $tNGs->displayFieldError("ticketyanit", "yetkiliadi"); ?></td>
          </tr>
          <tr>
            <td height="45" class="KT_th">Müşteri Sorusu:</td>
            <td height="45"><?php echo $row_ticgcvp['baslik']; ?></td>
          </tr>
          <tr>
            <td height="45" class="KT_th"><label for="mesaj">Mesaj:</label></td>
            <td height="45"><textarea name="mesaj" id="mesaj" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsticketyanit['mesaj']); ?></textarea>
              <?php echo $tNGs->displayFieldHint("mesaj");?> <?php echo $tNGs->displayFieldError("ticketyanit", "mesaj"); ?></td>
          </tr>
          <tr>
            <td height="45" class="KT_th"><label for="eposta">Eposta:</label></td>
            <td height="45"><input name="eposta" type="text" id="eposta" value="<?php echo $_SESSION['kt_email']; ?><?php echo KT_escapeAttribute($row_rsticketyanit['eposta']); ?>" size="32" readonly="readonly" />
              <?php echo $tNGs->displayFieldHint("eposta");?> <?php echo $tNGs->displayFieldError("ticketyanit", "eposta"); ?></td>
          </tr>
          <tr>
            <td height="45" class="KT_th"><label for="tarih">Tarih:</label></td>
            <td height="45"><input name="tarih" type="text" id="tarih" value="<?php echo tarihcevir(date("m/d/Y H:i")); ?><?php echo KT_escapeAttribute($row_rsticketyanit['tarih']); ?>" size="32" readonly="readonly" />
              <?php echo $tNGs->displayFieldHint("tarih");?> <?php echo $tNGs->displayFieldError("ticketyanit", "tarih"); ?></td>
          </tr>
          <tr>
            <td height="45" class="KT_th"><label for="durum">Durum:</label></td>
            <td height="45"><select name="durum" id="durum">
              <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rsticketyanit['durum'])))) {echo "SELECTED";} ?>>Aktif</option>
              <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute($row_rsticketyanit['durum'])))) {echo "SELECTED";} ?>>Kapalı</option>
            </select>
              <?php echo $tNGs->displayFieldError("ticketyanit", "durum"); ?>
              
              </td>
          </tr>
          <tr class="KT_buttons">
            <td height="45">&nbsp;</td>
            <td height="45"><input type="submit" name="KT_Insert1" id="KT_Insert1" value="GÖNDER" /></td>
          </tr>
          <tr class="KT_buttons">
            <td height="45">&nbsp;</td>
            <td height="45">
            <input type="hidden" name="ticketID" id="ticketID" value="<?php echo $row_ticgcvp['id']; ?><?php echo KT_escapeAttribute($row_rsticketyanit['ticketID']); ?>" />
            <?php echo $tNGs->displayFieldHint("ticketID");?> <?php echo $tNGs->displayFieldError("ticketyanit", "ticketID"); ?>
            <input type="hidden" name="musteriID" id="musteriID" value="<?php echo $row_ticgcvp['musteriID']; ?><?php echo KT_escapeAttribute($row_rsticketyanit['musteriID']); ?>"/>
             <?php echo $tNGs->displayFieldHint("musteriID");?> <?php echo $tNGs->displayFieldError("ticketyanit", "musteriID"); ?>
            <input type="hidden" name="yetkiliID" id="yetkiliID" value="<?php echo $row_ticgcvp['yoneticiID']; ?><?php echo KT_escapeAttribute($row_rsticketyanit['yetkiliID']); ?>"/>
            <?php echo $tNGs->displayFieldHint("yetkiliID");?> <?php echo $tNGs->displayFieldError("ticketyanit", "yetkiliID"); ?>
            </td>
          </tr>
        </table>
      </form>
    
    
    </td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($ticgcvp);

mysql_free_result($desdizid);
?>
