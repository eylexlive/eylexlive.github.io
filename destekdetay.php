<?php require_once('Connections/baglanti.php'); 
// Load the common classes
require_once('includes/common/KT_common.php');
 require_once('Connections/baglanti.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_baglanti, "");
//Grand Levels: Level
$restrict->addLevel("1");
$restrict->addLevel("2");
$restrict->Execute();
//End Restrict Access To Page

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("mesaj", true, "text", "", "", "", "Lütfen  mesajınızı yazınız...");
$tNGs->prepareValidation($formValidation);
// End trigger

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

$colname_ticgcvp = "-1";
if (isset($_GET['id'])) {
  $colname_ticgcvp = $_GET['id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_ticgcvp = sprintf("SELECT * FROM ticket WHERE id = %s", GetSQLValueString($colname_ticgcvp, "int"));
$ticgcvp = mysql_query($query_ticgcvp, $baglanti) or die(mysql_error());
$row_ticgcvp = mysql_fetch_assoc($ticgcvp);
$totalRows_ticgcvp = mysql_num_rows($ticgcvp);

$colname_cvptic = "-1";
if (isset($_GET['id'])) {
  $colname_cvptic = $_GET['id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_cvptic = sprintf("SELECT * FROM ticketyanit WHERE ticketID = %s ORDER BY id DESC", GetSQLValueString($colname_cvptic, "int"));
$cvptic = mysql_query($query_cvptic, $baglanti) or die(mysql_error());
$row_cvptic = mysql_fetch_assoc($cvptic);
$totalRows_cvptic = mysql_num_rows($cvptic);

// Make an insert transaction instance
$ins_ticketyanit = new tNG_insert($conn_baglanti);
$tNGs->addTransaction($ins_ticketyanit);
// Register triggers
$ins_ticketyanit->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_ticketyanit->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_ticketyanit->registerTrigger("END", "Trigger_Default_Redirect", 99, "d{ticgcvp.id}-.htm");
// Add columns
$ins_ticketyanit->setTable("ticketyanit");
$ins_ticketyanit->addColumn("ticketID", "NUMERIC_TYPE", "POST", "ticketID", "{ticgcvp.id}");
$ins_ticketyanit->addColumn("musteriID", "NUMERIC_TYPE", "POST", "musteriID", "{SESSION.kt_login_id}");
$ins_ticketyanit->addColumn("musteriadi", "STRING_TYPE", "POST", "musteriadi", "{SESSION.kt_username}");
$ins_ticketyanit->addColumn("yetkiliadi", "STRING_TYPE", "POST", "yetkiliadi", "{SESSION.kt_username} || Müsteri");
$ins_ticketyanit->addColumn("mesaj", "STRING_TYPE", "POST", "mesaj");
$ins_ticketyanit->addColumn("eposta", "STRING_TYPE", "POST", "eposta", "{SESSION.kt_email}");
$ins_ticketyanit->addColumn("ipadres", "STRING_TYPE", "POST", "ipadres", "{SERVER.REMOTE_ADDR}");
$ins_ticketyanit->addColumn("tarih", "STRING_TYPE", "POST", "tarih");
$ins_ticketyanit->addColumn("durum", "STRING_TYPE", "POST", "durum", "2");
$ins_ticketyanit->setPrimaryKey("id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsticketyanit = $tNGs->getRecordset("ticketyanit");
$row_rsticketyanit = mysql_fetch_assoc($rsticketyanit);
$totalRows_rsticketyanit = mysql_num_rows($rsticketyanit);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
 <script>
        function no_submit(){
            return false;
        }

        function notifyOnErrorInput(input){
            var message = input.data('validateHint');
            $.Notify({
                caption: 'Error',
                content: message,
                type: 'alert'
            });
        }
    </script>
</head>

<body>

<!-- Default -->
<div class="panel" >
    <div class="heading">
        <span class="title"><span class="mif-contacts-mail"></span> DESTEK TALEBİ / Cevapla - Oku</span>
    </div>
    <?php if ($totalRows_ticgcvp == 0) { // Show if recordset empty ?>
  <div class="content">
    <center>
      <h1 style="color:#C00; font-size:56px; margin:0px; padding:0px;">404 HATA</h1>
      <h5>Aradığınız destek talebi bulunamadı...</h5>
      <div data-role="preloader" data-type="metro" data-style="dark"></div>
      <small style="font-size:14px">Anasayfaya yönlendiriliyorsunuz..</small><br />
      <small style="font-size:16px">IP Adresiniz <strong><?php echo $ip_adresi = GetIP(); ?></strong> kaydedildi teşekkürler :)</small>
      </center>
    <br />
    <meta http-equiv="refresh" content="5;URL=ClientArea" />

  </div>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_ticgcvp > 0) { // Show if recordset not empty ?>
  <div class="content">
    <table width="100%" border="0">
      <tr>
        <td height="35" colspan="3" bgcolor="#D6D6D6"><strong>&nbsp;&nbsp;&nbsp;<i class="fa fa-ticket"></i> <em><?php echo $row_ticgcvp['baslik']; ?></em> / Destek talebi cevapları</strong></td>
        </tr>
      <tr>
        <td height="35" bgcolor="#F2F2F2"><strong>&nbsp;&nbsp;DURUM;</strong> <span class="well">
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
          </span></td>
        <td bgcolor="#F2F2F2"><strong>TARİH;</strong> <?php echo $row_ticgcvp['destektarih']; ?></td>
        <td bgcolor="#F2F2F2"><strong>DEPARTMAN;</strong> <?php echo $row_ticgcvp['departman']; ?></td>
        </tr>
      <tr>
        <td colspan="3"></td>
        </tr>
    </table>
    <?php if ($totalRows_cvptic == 0) { // Show if recordset empty ?>
      <center>
        <h1>Cevap Bekleniyor</h1>
        <div data-role="preloader" data-type="metro" data-style="dark"></div>
        <small>Lütfen daha sonra tekrar ziyaret ediniz...</small>
      </center>
      <?php } // Show if recordset empty ?>
    <?php if ($totalRows_cvptic > 0) { // Show if recordset not empty ?>
      <div class="padding20 bg-grayLighter">
        <label class="switch-original padding10">
          <input type="checkbox" onchange="$('#cevapyaz').toggleClass('table'); $('#cevapyaz').toggleClass('no-display')">
          <span class="caption"> Cevap Yaz </span>
          <span class="check"></span>
        </label></div>
      <div class="inline-block no-display" id="cevapyaz">
        <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
          <table align="center" cellpadding="2" cellspacing="0" class="KT_tngtable">
            <tr>
              <td class="KT_th"> <textarea data-validate-func="pattern" data-validate-arg="^\d+$" data-validate-hint="Value must be eq to pattern" placeholder="Lütfen cevabınızı yazınız" name="mesaj" id="mesaj" cols="70" rows="9"><?php echo strip_tags(KT_escapeAttribute($row_rsticketyanit['mesaj'])); ?></textarea>
              <script src="//cdn.ckeditor.com/4.5.7/basic/ckeditor.js"></script>
              <script type="text/javascript"> 
			  CKEDITOR.replace( 'mesaj', {
width :600,
height: 100
}); 

</script>
              
                <?php echo $tNGs->displayFieldHint("mesaj");?> <?php echo $tNGs->displayFieldError("ticketyanit", "mesaj"); ?></td>
              </tr>
            <tr class="KT_buttons">
              <td><input type="submit" class="primary" name="KT_Insert1" id="KT_Insert1" value="CEVAP YAZ" /></td>
              </tr>
            </table>
          <input type="hidden" name="ticketID" id="ticketID" value="<?php echo KT_escapeAttribute($row_rsticketyanit['ticketID']); ?>" />
          <input type="hidden" name="musteriID" id="musteriID" value="<?php echo KT_escapeAttribute($row_rsticketyanit['musteriID']); ?>" />
          <input type="hidden" name="musteriadi" id="musteriadi" value="<?php echo KT_escapeAttribute($row_rsticketyanit['musteriadi']); ?>" />
          <input type="hidden" name="yetkiliadi" id="yetkiliadi" value="<?php echo KT_escapeAttribute($row_rsticketyanit['yetkiliadi']); ?>" />
          <input type="hidden" name="eposta" id="eposta" value="<?php echo KT_escapeAttribute($row_rsticketyanit['eposta']); ?>" />
          <input type="hidden" name="ipadres" id="ipadres" value="<?php echo KT_escapeAttribute($row_rsticketyanit['ipadres']); ?>" />
          <input type="hidden" name="tarih" id="tarih" value="<?php date_default_timezone_set('Europe/Istanbul'); echo tarihcevir(date("m/d/Y H:i")); ?><?php echo KT_escapeAttribute($row_rsticketyanit['tarih']); ?>" />
          <input type="hidden" name="durum" id="durum" value="<?php echo KT_escapeAttribute($row_rsticketyanit['durum']); ?>" />
          </form></div>
      </div>
      <?php do { ?>
        <table width="100%" border="0" align="center" class="table border" style=" margin-bottom:0px; padding-bottom:0px;" >
          <tr 
          <?php 

if ($row_cvptic['durum'] == '2') 
{ 
    echo ' bgcolor="#CCFFCC" '; 
} 
elseif ($row_cvptic['durum'] == '1') 
{ 
    echo 'bgcolor="#FFFFCC"'; 
} 
 
else 
{ 
    echo 'HATA'; 
} 

?>
          
          >
            <td width="48%" >&nbsp;&nbsp;&nbsp;<i class="fa fa-user"></i> <strong>Yazan;</strong> <?php echo $row_cvptic['yetkiliadi']; ?></td>
            <td width="52%" ><i class="fa fa-calendar"></i> <strong>Tarih;</strong> <?php echo $row_cvptic['tarih']; ?></td>
            </tr>
        </table>
        <table width="100%" border="0" align="center" class="table border bordered " style=" padding:10px; margin-top:0px">
          <tr>
            <td bgcolor="#F2F2F2"><blockquote>
              <p><?php echo htmlspecialchars($row_cvptic['mesaj']); ?></p>
              </blockquote>
              <i class="fa fa-lightbulb-o"></i> <strong>Durum;</strong> <span class="well">
                <?php 

if ($row_cvptic['durum'] == '2') 
{ 
    echo ' <span class="btn btn-warning btn-xs">Müşteri Yanıtı</span> '; 
} 
elseif ($row_cvptic['durum'] == '1') 
{ 
    echo '<span class="btn btn-success btn-xs">Yönetici Yanıtı</span>'; 
} 
 
else 
{ 
    echo 'HATA'; 
} 

?>
              </span></td>
            </tr>
        </table>
        <?php } while ($row_cvptic = mysql_fetch_assoc($cvptic)); ?>
      <?php } // Show if recordset not empty ?>
    <table width="100%" border="0" align="center" class="table border" style=" margin-bottom:0px; padding-bottom:0px;" >
      <tr>
        <td width="34%" bgcolor="#CCFFCC">&nbsp;&nbsp;&nbsp;<i class="fa fa-user"></i> <strong>Yazan; </strong> <?php echo $row_ticgcvp['isim']; ?></td>
        <td width="31%" bgcolor="#CCFFCC"><i class="fa fa-calendar"></i> <strong>Tarih;</strong> <?php echo $row_ticgcvp['destektarih']; ?></td>
        <td width="35%" bgcolor="#CCFFCC"><i class="fa fa-lightbulb-o"></i> <strong>Durum;</strong> <span class="well">
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
          </span></td>
        </tr>
    </table>
    <table width="100%" border="0" align="center" class="table border bordered " style=" padding:10px; margin-top:0px">
      <tr>
        <td bgcolor="#F2F2F2">
          <blockquote>
            <p><?php echo htmlspecialchars($row_ticgcvp['mesaj']); ?></p>
          </blockquote></td>
        </tr>
    </table>
  </div>
  <?php } // Show if recordset not empty ?>
</div>
</body>
</html>
<?php
mysql_free_result($ticgcvp);

mysql_free_result($cvptic);
?>
<?php 

function tarihcevir($tarih){  
$tarih= date('d F Y D H:i', strtotime($tarih));   
$tarihdegistir =array(  
'Mon'=>'Pazartesi',  
'Tue'=>'Salı',  
'Wed'=>'Çarşamba',  
'Thu'=>'Perşembe',  
'Fri'=>'Cuma',  
'Sat'=>'Cumartesi',  
'Sun' => 'Pazar',  
'January' => 'Ocak',  
'February' => 'Şubat',  
'March' => 'Mart',  
'April' => 'Nisan',  
'May' => 'Mayıs',  
'June' => 'Haziran',  
'July' => 'Temmuz',  
'August' => 'Ağustos',  
'September' => 'Eylül',  
'October' => 'Ekim',  
'November' => 'Kasım',  
'December' => 'Aralık');  
$yenitarih= strtr($tarih, $tarihdegistir); 
return $yenitarih;  
} 
?>