<?php

// Load the common classes
require_once('includes/common/KT_common.php');
 

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

$colname_mekredit = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_mekredit = $_SESSION['kt_login_id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_mekredit = sprintf("SELECT * FROM credituser WHERE musteriID = %s ORDER BY id DESC", GetSQLValueString($colname_mekredit, "int"));
$mekredit = mysql_query($query_mekredit, $baglanti) or die(mysql_error());
$row_mekredit = mysql_fetch_assoc($mekredit);
$totalRows_mekredit = mysql_num_rows($mekredit);

mysql_select_db($database_baglanti, $baglanti);
$query_paypal = "SELECT * FROM paypalsettings WHERE id = 1";
$paypal = mysql_query($query_paypal, $baglanti) or die(mysql_error());
$row_paypal = mysql_fetch_assoc($paypal);
$totalRows_paypal = mysql_num_rows($paypal);

// Make an insert transaction instance
$ins_credituser = new tNG_insert($conn_baglanti);
$tNGs->addTransaction($ins_credituser);
// Register triggers
$ins_credituser->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_credituser->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_credituser->registerTrigger("END", "Trigger_Default_Redirect", 99, "kredilerim");
// Add columns
$ins_credituser->setTable("credituser");
$ins_credituser->addColumn("musteriID", "NUMERIC_TYPE", "POST", "musteriID", "{SESSION.kt_login_id}");
$ins_credituser->addColumn("musteriadi", "STRING_TYPE", "POST", "musteriadi", "{SESSION.kt_username}");
$ins_credituser->addColumn("method", "STRING_TYPE", "POST", "method");
$ins_credituser->addColumn("miktar", "STRING_TYPE", "POST", "miktar");
$ins_credituser->addColumn("token", "STRING_TYPE", "POST", "token");
$ins_credituser->setPrimaryKey("id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscredituser = $tNGs->getRecordset("credituser");
$row_rscredituser = mysql_fetch_assoc($rscredituser);
$totalRows_rscredituser = mysql_num_rows($rscredituser);

$tokenid = md5(mt_rand());
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
 <script>
        $(function(){
            $(".js-select").select2({
                placeholder: "Lütfen bir şeçim Yapınız",
                allowClear: false
            });
        });

        function fmtState (state) {
            if (!state.id) { return state.text; }
            var $state = $(
                    '<span><img src="images/flags/' + state.element.value.toLowerCase() + '.png" class="img-flag" /> ' + state.text + '</span>'
            );
            return $state;
        }
    </script>
</head>

<body>
<!-- Default -->
<div class="panel" >
    <div class="heading">
        <span class="title"><span class="mif-dollars"></span> YÜKLENEN KREDİLER</span>
    </div>
    <div class="content">
      <table width="100%" border="0">
        <tr>
          <td height="35" bgcolor="#D6D6D6"><strong>&nbsp;&nbsp;<i class="fa fa-money"></i>
 KREDİ YÜKLE</strong></td>
        </tr>
        <tr>
          <td height="50" bgcolor="#FFFFFF">
       
            <form name="form1" method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
              <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                <tr>
                  <td width="147" class="KT_th"><label for="method">Ödeme Yöntemi:</label></td>
                  <td width="263">
                  <div class="input-control select">
                  <select class="js-select full-size" name="method" id="method">
                  <option value="2" <?php if (!(strcmp(2, KT_escapeAttribute($row_rscredituser['method'])))) {echo "SELECTED";} ?>>Paypal ile Öde</option>
                    <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rscredituser['method'])))) {echo "SELECTED";} ?>>Havale ile Öde</option>
                    
                  </select></div>
                    <?php echo $tNGs->displayFieldError("credituser", "method"); ?></td>
                </tr>
                <tr>
                  <td class="KT_th"><label for="miktar">Miktar:</label></td>
                  <td>
                  <div class="input-control select">
                  <select class="js-select full-size" name="miktar" id="miktar">
                    <option value="5" <?php if (!(strcmp(5, KT_escapeAttribute($row_rscredituser['miktar'])))) {echo "SELECTED";} ?>>5.00 TL Kredi</option>
                    <option value="10" <?php if (!(strcmp(10, KT_escapeAttribute($row_rscredituser['miktar'])))) {echo "SELECTED";} ?>>10.00 TL Kredi</option>
                    <option value="15" <?php if (!(strcmp(15, KT_escapeAttribute($row_rscredituser['miktar'])))) {echo "SELECTED";} ?>>15.00 TL Kredi</option>
                    <option value="25" <?php if (!(strcmp(25, KT_escapeAttribute($row_rscredituser['miktar'])))) {echo "SELECTED";} ?>>25.00 TL Kredi</option>
                    <option value="30" <?php if (!(strcmp(30, KT_escapeAttribute($row_rscredituser['miktar'])))) {echo "SELECTED";} ?>>30.00 TL Kredi</option>
                    <option value="40" <?php if (!(strcmp(40, KT_escapeAttribute($row_rscredituser['miktar'])))) {echo "SELECTED";} ?>>40.00 TL Kredi</option>
                    <option value="50" <?php if (!(strcmp(50, KT_escapeAttribute($row_rscredituser['miktar'])))) {echo "SELECTED";} ?>>50.00 TL Kredi</option>
                    <option value="70" <?php if (!(strcmp(70, KT_escapeAttribute($row_rscredituser['miktar'])))) {echo "SELECTED";} ?>>70.00 TL Kredi</option>
                    <option value="90" <?php if (!(strcmp(90, KT_escapeAttribute($row_rscredituser['miktar'])))) {echo "SELECTED";} ?>>90.00 TL Kredi</option>
                    <option value="100" <?php if (!(strcmp(100, KT_escapeAttribute($row_rscredituser['miktar'])))) {echo "SELECTED";} ?>>100.00 TL Kredi</option>
                  </select></div>
                    <?php echo $tNGs->displayFieldError("credituser", "miktar"); ?></td>
                </tr>
                <tr class="KT_buttons">
                  <td>&nbsp;</td>
                  <td>
                  <input  class="button rounded primary" type="submit" onClick="alert('Kredi siparişinizi verdiniz! Lütfen ödeme yapınız...')" name="KT_Insert1" id="KT_Insert1" value="KREDİ YÜKLE" >
                  
                  
                  <script type="text/javascript">

<!--

var secs = 321;

var wait = secs * 1000;

document.form1.KT_Insert1.disabled=true;

	

for(i=1;i<=secs;i++) {

 window.setTimeout("update(" + i + ")", i * 1000);

}



window.setTimeout("timer()", wait);



function update(num) {

 if(num == (wait/1000)) {

  document.form1.KT_Insert1.value = "KREDİ YÜKLE";

 }

 else {

  printnr = (wait/1000)-num;

  document.form1.KT_Insert1.value = "Lütfen (" + printnr + ") saniye bekleyin !";

 }

}



function timer() {

 document.form1.KT_Insert1.disabled=false;

}

//-->

</script>

                  
                  </td>
                </tr>
              </table>
              <input type="hidden" name="token" id="token" value="<?php echo $tokenid ?><?php echo $_SESSION['kt_username']; ?>" />
              <input type="hidden" name="musteriID" id="musteriID" value="<?php echo KT_escapeAttribute($row_rscredituser['musteriID']); ?>" />
              <input type="hidden" name="musteriadi" id="musteriadi" value="<?php echo KT_escapeAttribute($row_rscredituser['musteriadi']); ?>" />
            </form>
            
          


            <div data-role="preloader" data-type="metro" data-style="dark"></div>
            <div class="text-shadow bg-cyan fg-white align-center"><hr>
            <i class="fa fa-bell-o"></i> Ödeme yaptıkdan sonra lütfen destek talebi oluşturunuz...
              <hr></div>
          </td>
        </tr>
      </table>
      <hr>

      <table width="100%" border="0">
        <tr>
          <td height="45" bgcolor="#66CC99"><strong>&nbsp;&nbsp;<i class="fa fa-money"></i>
 YÜKLENEN KREDİLERİM / ÖDEME BEKLEYENLER<a name="kredit"></a></strong></td>
        </tr>
        <tr>
          <td><?php if ($totalRows_mekredit == 0) { // Show if recordset empty ?>
  <center><h3>Kredi hareketleri yok...</h3></center>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_mekredit > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" class="table bordered border">
    <tr style="font-size:13px">
      <th width="25%" height="35" bgcolor="#CCFFFF">ÖDEME YÖNTEMİ</th>
      <th width="24%" height="35" bgcolor="#CCFFFF">YÜKLENEN BAKİYE</th>
      <th width="29%" height="35" bgcolor="#CCFFFF">SİPARİŞ DURUMU</th>
      <th width="22%" height="35" bgcolor="#CCFFFF">İŞLEM</th>
    </tr>
    <?php do { ?>
      <tr>
        <td align="center"><?php 

if ($row_mekredit['method'] == '1') 
{ 
    echo ' <span class="btn btn-xs btn-default"><i class="fa fa-money"></i> Banka Havalesi</span> '; 
} 
elseif ($row_mekredit['method'] == '2') 
{ 
    echo '<span class="btn btn-xs btn-default"><i class="fa fa-cc-paypal"></i> Paypal ile Öde</span>'; 
} 
else 
{ 
    echo '<span class="btn btn-xs btn-danger"><i class="fa fa-exclamation-triangle"></i> 404 Hata</span>'; 
} 

?></td>
        <td align="center">
          <?php 

if ($row_mekredit['durum'] == '0') 
{ 
    echo ' <span class="btn btn-xs btn-success">'.$row_mekredit['miktar'].'.00 <i class="fa fa-try"></i></span> <form id="'.$row_mekredit['token'].'" name="'.$row_mekredit['token'].'" method="post" action="credikdelete.php?token='.$row_mekredit['token'].'">
  
    <button class="button rounded mini-button danger" type="submit" onClick="return confirm(\'Sipariş vermiş olduğunuz '.$row_mekredit['miktar'].'.00 TL tutarındaki kredi işlemini iptal etmek istediğinizden eminmisiniz?\')" name="sil" id="sil">İPTAL ET </button>
 
</form>  '; 
} 
elseif ($row_mekredit['durum'] == '1') 
{ 
    echo '<span class="btn btn-xs btn-success">'.$row_mekredit['miktar'].'.00 <i class="fa fa-try"></i></span>'; 
} 
else 
{ 
    echo '<span class="btn btn-xs btn-danger"><i class="fa fa-exclamation-triangle"></i> 404 Hata</span>'; 
} 

?>
        </td>
        <td align="center"><?php 

if ($row_mekredit['durum'] == '0') 
{ 
    echo ' <button class="button rounded mini-button danger">ÖDEME BEKLİYOR</button> '; 
} 
elseif ($row_mekredit['durum'] == '1' && $row_mekredit['eklendimi'] == '0') 
{ 
    echo '<button class="button rounded mini-button success">ÖDEME YAPILDI</button>'; 
	echo '<button class="button rounded mini-button warning loading-cube lighten">ONAY BEKLİYOR</button>'; 
} 
elseif ($row_mekredit['eklendimi'] == '1') 
{ 
    echo '<button class="button rounded mini-button success">KREDİ EKLENDİ</button>'; 
}

else 
{ 
    echo '<span class="btn btn-xs btn-danger"><i class="fa fa-exclamation-triangle"></i> 404 Hata</span>'; 
} 

?></td>
        <td align="center"><?php 

if ($row_mekredit['method'] == '2' && $row_mekredit['durum'] == '0') 
{ 
    echo ' <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="'.$row_paypal['epostaadresi'].'">
<input type="hidden" name="lc" value="'.$row_paypal['ulke'].'">
<input type="hidden" name="item_name" value="URUN NO; '.$row_mekredit['id'].' / Musteri; '.$row_mekredit['musteriadi'].' / Kredi Satin Al / '.$row_ayar['title'].' Kredi satin alma islemi">
<input type="hidden" name="item_number" value="'.$row_mekredit['id'].'">
<input type="hidden" name="amount" value="'.$row_mekredit['miktar'].'.00">
<input type="hidden" name="currency_code" value="'.$row_paypal['parabirimi'].'">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynow_SM.gif:NonHostedGuest">
<input type="image" src="https://www.paypalobjects.com/tr_TR/i/btn/btn_paynow_SM.gif" border="0" name="submit" alt="PayPal - Online ödeme yapmanın daha güvenli ve kolay yolu!">
<img alt="" border="0" src="https://www.paypalobjects.com/tr_TR/i/scr/pixel.gif" width="1" height="1">
</form>
 '; 
} 
elseif ($row_mekredit['method'] == '1' && $row_mekredit['durum'] == '0') 
{ 
    echo '<button class="button rounded mini-button warning loading-pulse" style="font-size:8px">HAVALE İŞLEMİ SÜRÜYOR</button>'; 
} 
elseif ($row_mekredit['durum'] == '1') 
{ 
    echo '<button class="button rounded mini-button default" style="font-size:8px; color:#333" disabled>ÖDEMENİZ ALINDI</button>'; 
}
else 
{ 
    echo '<span class="btn btn-xs btn-danger"><i class="fa fa-exclamation-triangle"></i> 404 Hata</span>'; 
} 

?></td>
      </tr>
      <?php } while ($row_mekredit = mysql_fetch_assoc($mekredit)); ?>
  </table>
  <?php } // Show if recordset not empty ?></td>
        </tr>
      </table>
  </div></div>
</body>
</html><?php
mysql_free_result($mekredit);

mysql_free_result($paypal);
?>
