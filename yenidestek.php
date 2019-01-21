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
$formValidation->addField("eposta", true, "text", "email", "", "", "");
$formValidation->addField("baslik", true, "text", "", "5", "100", "Lütfen bir başlık giriniz... Minimum:5 karakter");
$formValidation->addField("mesaj", true, "text", "", "10", "400", "Mesajınızı belirtiniz. Minimum:10 Maksimum:400 karakter");
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

$colname_urun = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_urun = $_SESSION['kt_login_id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_urun = sprintf("SELECT * FROM cart WHERE musteriID = %s", GetSQLValueString($colname_urun, "int"));
$urun = mysql_query($query_urun, $baglanti) or die(mysql_error());
$row_urun = mysql_fetch_assoc($urun);
$totalRows_urun = mysql_num_rows($urun);

mysql_select_db($database_baglanti, $baglanti);
$query_bankgl = "SELECT * FROM bankahesaplari WHERE aktif = '1' ORDER BY bankaadi ASC";
$bankgl = mysql_query($query_bankgl, $baglanti) or die(mysql_error());
$row_bankgl = mysql_fetch_assoc($bankgl);
$totalRows_bankgl = mysql_num_rows($bankgl);

$colname_kreditus = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_kreditus = $_SESSION['kt_login_id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_kreditus = sprintf("SELECT * FROM credituser WHERE musteriID = %s && durum = '0' && eklendimi = '0' ORDER BY id DESC", GetSQLValueString($colname_kreditus, "int"));
$kreditus = mysql_query($query_kreditus, $baglanti) or die(mysql_error());
$row_kreditus = mysql_fetch_assoc($kreditus);
$totalRows_kreditus = mysql_num_rows($kreditus);

// Make an insert transaction instance
$ins_ticket = new tNG_insert($conn_baglanti);
$tNGs->addTransaction($ins_ticket);
// Register triggers
$ins_ticket->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_ticket->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_ticket->registerTrigger("END", "Trigger_Default_Redirect", 99, "supports");
// Add columns
$ins_ticket->setTable("ticket");
$ins_ticket->addColumn("musteriID", "NUMERIC_TYPE", "POST", "musteriID", "{SESSION.kt_login_id}");
$ins_ticket->addColumn("isim", "STRING_TYPE", "POST", "isim");
$ins_ticket->addColumn("eposta", "STRING_TYPE", "POST", "eposta");
$ins_ticket->addColumn("baslik", "STRING_TYPE", "POST", "baslik");
$ins_ticket->addColumn("departman", "STRING_TYPE", "POST", "departman");
$ins_ticket->addColumn("iliskilihizmet", "STRING_TYPE", "POST", "iliskilihizmet", "{urun.siparisID}");
$ins_ticket->addColumn("oncelik", "STRING_TYPE", "POST", "oncelik");
$ins_ticket->addColumn("mesaj", "STRING_TYPE", "POST", "mesaj");
$ins_ticket->addColumn("odemebanka", "STRING_TYPE", "POST", "odemebanka");
$ins_ticket->addColumn("odenenmiktar", "STRING_TYPE", "POST", "odenenmiktar");
$ins_ticket->addColumn("odemeyapankisi", "STRING_TYPE", "POST", "odemeyapankisi");
$ins_ticket->addColumn("eklentiler", "STRING_TYPE", "POST", "eklentiler");
$ins_ticket->addColumn("destektarih", "STRING_TYPE", "POST", "destektarih");
$ins_ticket->setPrimaryKey("id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsticket = $tNGs->getRecordset("ticket");
$row_rsticket = mysql_fetch_assoc($rsticket);
$totalRows_rsticket = mysql_num_rows($rsticket);
?>



<style type="text/css">
<!--
.panel .content table tr td .cont {
	margin: 10px;
}
-->
</style>
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>


<body>
<!-- Default -->
<div class="panel" >
    <div class="heading">
        <span class="title"><i class="fa fa-ticket"></i> Ticket Sistemi</span></div>
    <div class="content">
      <table width="100%" border="0">
        <tr>
          <td height="45" bgcolor="#E3E3E3">&nbsp;&nbsp;<strong>YENİ DESTEK TALEBİ OLUŞTUR</strong></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><div class="cont">
          <div class="grid">
            <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
              <table width="100%" cellpadding="2" cellspacing="0" class="KT_tngtable">
                <tr>
                  <td colspan="2" class="KT_th">
                  <div class="padding20 bg-grayLighter">
                    <label class="switch-original padding10">
                      <input type="checkbox" onChange="$('#cevapyaz').toggleClass('table'); $('#cevapyaz').toggleClass('no-display')">
                      <span class="caption"> ÖDEME BİLDİRİMİ AYARLARI </span>
                      <span class="check"></span>
                    </label></div>
                  <div class="inline-block no-display" id="cevapyaz">
                  <table width="100%" border="1" cellpadding="1" cellspacing="1">
                    <tr>
                      <td height="45" class="KT_th"><label for="iliskilihizmet2">İlişkili Hizmet:</label></td>
                      <td height="45"><div class="input-control select">
                       
                                <select name="iliskilihizmet" id="iliskilihizmet">
                                      <option>--- Yok ---</option>
                                      <?php if ($totalRows_kreditus > 0) { // Show if recordset not empty ?><?php do { ?><option value="<?php echo $row_kreditus['id']; ?>, <?php echo $row_kreditus['musteriadi']; ?> , <?php echo $row_kreditus['miktar']; ?>.00TL Kredi İşlemi"><?php echo $row_kreditus['miktar']; ?>.00 TL Kredi İşlemi!</option><?php } while ($row_kreditus = mysql_fetch_assoc($kreditus)); ?> <?php } // Show if recordset not empty ?>
                                  <?php 
                                do {  
                                ?>
                                  <option value="<?php echo $row_urun['servisadi']?>"<?php if (!(strcmp($row_urun['servisadi'], $row_rsticket['iliskilihizmet']))) {echo "SELECTED";} ?>><?php echo $row_urun['servisadi']?></option>
  <?php
} while ($row_urun = mysql_fetch_assoc($urun));
  $rows = mysql_num_rows($urun);
  if($rows > 0) {
      mysql_data_seek($urun, 0);
	  $row_urun = mysql_fetch_assoc($urun);
  }
?>
</select></div>
                    <?php echo $tNGs->displayFieldError("ticket", "iliskilihizmet"); ?></td>
                    </tr>
                    <tr>
                     <td height="45" class="KT_th"><label for="odemebanka">Ödeme Yapılan Banka:</label></td>
                  <td height="45"><div class="input-control select"><select name="odemebanka" id="odemebanka">
                  <option style="text-align:center">- Ödeme Şekli -</option>
                  <option value="">Paypal İle Ödeme Sistemi </option>
                  <option>-- Havale ile Ödenen Bankayı Seç --</option>
                    <?php 
do {  
?>
					
                    <option value="<?php echo $row_bankgl['bankaadi']?>"<?php if (!(strcmp($row_bankgl['bankaadi'], $row_rsticket['odemebanka']))) {echo "SELECTED";} ?>><?php echo $row_bankgl['bankaadi']?></option>
                    <?php
} while ($row_bankgl = mysql_fetch_assoc($bankgl));
  $rows = mysql_num_rows($bankgl);
  if($rows > 0) {
      mysql_data_seek($bankgl, 0);
	  $row_bankgl = mysql_fetch_assoc($bankgl);
  }
?>
                    </select></div>
                    <?php echo $tNGs->displayFieldError("ticket", "odemebanka"); ?></td>
                  </tr>
                <tr>
                  <td height="45" class="KT_th"><label for="odenenmiktar">Ödenen Miktar:</label></td>
                  <td height="45">
                  <div class="input-control text">
                  <input type="text" name="odenenmiktar" id="odenenmiktar" value="<?php echo KT_escapeAttribute($row_rsticket['odenenmiktar']); ?>" size="32" /></div>
                    <?php echo $tNGs->displayFieldHint("odenenmiktar");?> <?php echo $tNGs->displayFieldError("ticket", "odenenmiktar"); ?></td>
                  </tr>
                <tr>
                  <td height="45" class="KT_th"><label for="odemeyapankisi">Ödeme Yapan Kisi:</label></td>
                  <td height="45"><div class="input-control text"><input type="text" name="odemeyapankisi" id="odemeyapankisi" value="<?php echo KT_escapeAttribute($row_rsticket['odemeyapankisi']); ?>" size="32" /></div>
                    <?php echo $tNGs->displayFieldHint("odemeyapankisi");?> <?php echo $tNGs->displayFieldError("ticket", "odemeyapankisi"); ?></td>
                  </tr>
                <tr>
                  <td height="45" class="KT_th"><label for="eklentiler">Eklentiler:</label></td>
                  <td height="45"><div class="input-control text"><input type="text" name="eklentiler" id="eklentiler" value="<?php echo KT_escapeAttribute($row_rsticket['eklentiler']); ?>" size="32" /></div>Resim URL'si
                    <?php echo $tNGs->displayFieldHint("eklentiler");?> <?php echo $tNGs->displayFieldError("ticket", "eklentiler"); ?></td>
                    </tr>
                  </table>
                  </div>
                  </td>
                  </tr>
                <tr>
                  <td width="24%" height="45" class="KT_th"><label for="isim">Isim:</label></td>
                  <td width="76%" height="45">
                  <div class="input-control text success">
                  <input name="isim" type="text" id="isim" value="<?php echo $_SESSION['kt_username']; ?>" size="32" readonly="readonly" /></div>
                    <?php echo $tNGs->displayFieldHint("isim");?> <?php echo $tNGs->displayFieldError("ticket", "isim"); ?></td>
                  </tr>
                <tr>
                  <td height="45" class="KT_th"><label for="eposta">E-posta Adresi:</label></td>
                  <td height="45">
                  <div class="input-control text success">
                  <input name="eposta" type="text" id="eposta" value="<?php echo $_SESSION['kt_email']; ?>" size="32" readonly="readonly" />
                  
                  </div>
                    <?php echo $tNGs->displayFieldHint("eposta");?> <?php echo $tNGs->displayFieldError("ticket", "eposta"); ?></td>
                  </tr>
                <tr>
                  <td height="45" class="KT_th"><label for="baslik">Baslik:</label></td>
                  <td height="45">
                  <div class="input-control text info full-size">
                  <input type="text" name="baslik" id="baslik" value="<?php echo KT_escapeAttribute($row_rsticket['baslik']); ?>" size="32" required />
                  </div>
                    <?php echo $tNGs->displayFieldHint("baslik");?> <?php echo $tNGs->displayFieldError("ticket", "baslik"); ?></td>
                  </tr>
                <tr>
                  <td height="45" class="KT_th"><label for="departman">Departman:</label></td>
                  <td height="45">
                  <div class="input-control select">
                  <select name="departman" id="departman">
                    
                    <option value="Oyun içi hata" <?php if (!(strcmp("Oyun içi hata", KT_escapeAttribute($row_rsticket['departman'])))) {echo "SELECTED";} ?>>Oyun içi hata</option>
                    <option onClick="$('#cevapyaz').toggleClass('table'); $('#cevapyaz').toggleClass('no-display')" value="Ödeme Bildirimi" <?php if (!(strcmp("Ödeme Bildirimi", KT_escapeAttribute($row_rsticket['departman'])))) {echo "SELECTED";} ?>>Ödeme Bildirimi</option>
                     <option onClick="$('#cevapyaz').toggleClass('table'); $('#cevapyaz').toggleClass('no-display')" value="Kredi Ekleme" <?php if (!(strcmp("Kredi Ekleme", KT_escapeAttribute($row_rsticket['departman'])))) {echo "SELECTED";} ?>>Kredi Ekleme</option>
                    <option value="Bug Bildirimi" <?php if (!(strcmp("Bug Bildirimi", KT_escapeAttribute($row_rsticket['departman'])))) {echo "SELECTED";} ?>>Bug Bildirimi</option>
                    <option value="Oyuncu Şikayet" <?php if (!(strcmp("Oyuncu Şikayet", KT_escapeAttribute($row_rsticket['departman'])))) {echo "SELECTED";} ?>>Oyuncu Şikayet</option>
                    </select></div>
                    <?php echo $tNGs->displayFieldError("ticket", "departman"); ?></td>
                  </tr>
              
                <tr>
                  <td height="45" class="KT_th"><label for="oncelik">Öncelik:</label></td>
                  <td height="45">
                  <div class="input-control select">
                  <select name="oncelik" id="oncelik">
                    <option value="Yüksek" <?php if (!(strcmp("Yüksek", KT_escapeAttribute($row_rsticket['oncelik'])))) {echo "SELECTED";} ?>>Yüksek</option>
                    <option value="Orta" <?php if (!(strcmp("Orta", KT_escapeAttribute($row_rsticket['oncelik'])))) {echo "SELECTED";} ?>>Orta</option>
                    <option value="Düşük" <?php if (!(strcmp("Düşük", KT_escapeAttribute($row_rsticket['oncelik'])))) {echo "SELECTED";} ?>>Düşük</option>
                    </select></div>
                    <?php echo $tNGs->displayFieldError("ticket", "oncelik"); ?></td>
                  </tr>
                <tr>
                  <td height="45" class="KT_th"><label for="mesaj">Mesaj:</label></td>
                  <td height="45"><textarea name="mesaj" id="mesaj" cols="50" rows="5" required><?php echo KT_escapeAttribute($row_rsticket['mesaj']); ?></textarea>
                    
                    <?php echo $tNGs->displayFieldHint("mesaj");?> <?php echo $tNGs->displayFieldError("ticket", "mesaj"); ?></td>
                </tr>
                <tr class="KT_buttons">
                  <td>&nbsp;</td>
                  <td><input class="button success" type="submit" name="KT_Insert1" id="KT_Insert1" value="GÖNDER" /> <input type="button" value="&lt;&lt; İPTAL ET" class="btn btn-large" onClick="window.location='supports'" /> </td>
                </tr>
                </table>
              <input type="hidden" name="musteriID" id="musteriID" value="<?php echo KT_escapeAttribute($row_rsticket['musteriID']); ?>" />
              <input type="hidden" name="destektarih" id="destektarih" value="<?php echo date("d/m/Y"); ?><?php echo KT_escapeAttribute($row_rsticket['destektarih']); ?>" />
            </form>
            <p>&nbsp;</p>
          </div>
          </div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
</div></div>
<?php
mysql_free_result($urun);

mysql_free_result($bankgl);

mysql_free_result($kreditus);
?>
