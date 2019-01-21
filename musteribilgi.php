<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_baglanti, "");
//Grand Levels: Level
$restrict->addLevel("1");
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

$colname_lasthistory = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_lasthistory = $_SESSION['kt_login_id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_lasthistory = sprintf("SELECT * FROM historylog WHERE users = %s ORDER BY lastlogindate DESC", GetSQLValueString($colname_lasthistory, "text"));
$lasthistory = mysql_query($query_lasthistory, $baglanti) or die(mysql_error());
$row_lasthistory = mysql_fetch_assoc($lasthistory);
$totalRows_lasthistory = mysql_num_rows($lasthistory);

$colname_urunmID = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_urunmID = $_SESSION['kt_login_id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_urunmID = sprintf("SELECT * FROM cart WHERE musteriID = %s", GetSQLValueString($colname_urunmID, "int"));
$urunmID = mysql_query($query_urunmID, $baglanti) or die(mysql_error());
$row_urunmID = mysql_fetch_assoc($urunmID);
$totalRows_urunmID = mysql_num_rows($urunmID);

$colname_uyeticket = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_uyeticket = $_SESSION['kt_login_id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_uyeticket = sprintf("SELECT * FROM ticket WHERE musteriID = %s AND durum = '0' ORDER BY id DESC", GetSQLValueString($colname_uyeticket, "int"));
$uyeticket = mysql_query($query_uyeticket, $baglanti) or die(mysql_error());
$row_uyeticket = mysql_fetch_assoc($uyeticket);
$totalRows_uyeticket = mysql_num_rows($uyeticket);

$colname_destops = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_destops = $_SESSION['kt_login_id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_destops = sprintf("SELECT * FROM ticket WHERE musteriID = %s", GetSQLValueString($colname_destops, "int"));
$destops = mysql_query($query_destops, $baglanti) or die(mysql_error());
$row_destops = mysql_fetch_assoc($destops);
$totalRows_destops = mysql_num_rows($destops);

$colname_kreditops = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_kreditops = $_SESSION['kt_login_id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_kreditops = sprintf("SELECT * FROM credit WHERE musteriID = %s  AND durum = '1'", GetSQLValueString($colname_kreditops, "int"));
$kreditops = mysql_query($query_kreditops, $baglanti) or die(mysql_error());
$row_kreditops = mysql_fetch_assoc($kreditops);
$totalRows_kreditops = mysql_num_rows($kreditops);

function getToken($length=32){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}

$tokenid = md5(mt_rand());
?>



<!-- Default -->
<div class="panel"  onload="setTimeout('timeout()', 1)">
    <div class="heading">
        <span class="title"><i class="fa fa-info-circle"></i> Müşteri Bilgileri</span>
    </div>
    <div class="content">
      <table width="100%" border="0">
        <tr>
          <td width="50%" height="40" bgcolor="#D6D6D6"><strong>&nbsp;&nbsp;Hoşgeldiniz; </strong>  <?php echo $_SESSION['kt_username']; ?></td>
          <td width="50%" height="40" bgcolor="#D6D6D6"><strong>&nbsp;&nbsp;Hesap Özeti </strong></td>
        </tr>
        <tr>
          <td valign="top">
          
          <table width="100%" border="0" class="table cell-hovered border bordered">
            <tr>
              <td width="5%"><i class="fa fa-user"></i></td>
              <td width="95%"><?php echo $_SESSION['kt_username']; ?></td>
            </tr>
            <tr>
              <td><i class="fa fa-globe"></i></td>
              <td><?php echo $row_lasthistory['ipdadres']; ?></td>
            </tr>
            <tr>
              <td><i class="fa fa-step-forward"></i></td>
              <td><?php echo $_SESSION['kt_lastlogin']; ?></td>
            </tr>
            <tr>
              <td><i class="fa fa-bed"></i></td>
              <td><?php echo $_SESSION['kt_world']; ?></td>
            </tr>
            <tr>
              <td><i class="fa fa-calendar-o"></i></td>
              <td><?php echo $row_lasthistory['lastlogindate']; ?></td>
            </tr>
            <tr>
              <td><i class="fa fa-envelope"></i></td>
              <td><?php echo $_SESSION['kt_email']; ?></td>
            </tr>
            <tr>
              <td colspan="2"><i class="fa fa-calendar"></i> Son İşlem Tarihi</td>
            </tr>
            <tr>
              <td colspan="2"><i class="fa fa-clock-o"></i> <?php echo $row_lasthistory['lastactivitydate']; ?></td>
            </tr>
            <tr>
              <td colspan="2"> <div class="video-container">
                  <iframe src="https://www.youtube.com/embed/12CeaxLiMgE?controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
                        </div></td>
            </tr>
          </table>
        <?php date_default_timezone_set('Europe/Istanbul'); ?>
          </td>
          <td valign="top"><table width="100%" border="0" class="table cell-hovered border bordered">
            <tr>
              <td><span class="mif-shopping-basket"></span> Ürün/Hizmet Sayısı</td>
              <td align="center"><button class="button primary cycle-button mini-button"><?php echo $totalRows_urunmID ?> </button></td>
            </tr>
            <tr>
              <td><span class="mif-contacts-mail"></span> Destek Bildirimi Sayısı</td>
              <td align="center"><button class="button warning cycle-button mini-button"><?php echo $totalRows_destops ?></button></td>
            </tr>
            <tr>
              <td><span class="mif-dollars"></span> Kredi</td>
              <td align="center"><button class="button success rounded mini-button">
              <?php if ($totalRows_kreditops == 0) { // Show if recordset empty ?>
                0
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_kreditops > 0) { // Show if recordset not empty ?>
    <?php echo $row_kreditops['miktar']; ?> .00
<?php } // Show if recordset not empty ?>
<i class="fa fa-try"></i></button></td>
            </tr>
            <tr>
              <td><span class="mif-file-pdf"></span> Faturalarınız</td>
              <td align="center"><button class="button info cycle-button mini-button">0 </button></td>
            </tr>
            <tr >
              <td colspan="2" align="center"><form method="post" action="kredilerim"><button type="submit" class="button success rounded " name="gonder" id="gonder" style="font-size:24px" data-hotkey="alt+1" onclick="alert('Kredimi Yüklemek istiyorsunuz?')"> <span class="mif-dollars"></span> KREDİ YÜKLE</button></form>Kısa Yol Tuşu (Alt+1)</td>
            </tr>
            <tr class="active">
              <td colspan="2"><div class="text-shadow bg-cyan fg-white align-center"><hr>Kredinizi sipariş verdikten sonra destek talebinden bizimle iletişime geçiniz. Aksi taktirde krediniz hesabınıza yüklenmeyecektir.<hr></div></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <hr>
      <table width="100%" border="0">
        <tr>
          <td height="40" bgcolor="#D6D6D6">&nbsp;&nbsp;&nbsp;&nbsp;<strong><i class="fa fa-ticket"></i>
 Açık Destek Talepleriniz</strong></td>
          <td align="right" bgcolor="#D6D6D6"><a href="newticket"><button class="button info"><i class="fa fa-plus"></i> Yeni Destek Talebi</button></a></td>
        </tr>
        <tr>
          <td colspan="2" valign="top"><?php if ($totalRows_uyeticket == 0) { // Show if recordset empty ?>
  <center><h3>Açık destek talebiniz bulunmuyor...</h3></center>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_uyeticket > 0) { // Show if recordset not empty ?>
  <table width="100%" class="table border bordered sortable-markers-on-left">
    <thead>
      <tr>
        <th width="21%" >Tarih</th>
        <th width="38%">Başlık</th>
        <th width="26%">Durum</th>
        <th width="15%">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
      <?php do { ?>
        <tr>
          <td><?php echo $row_uyeticket['destektarih']; ?></td>
          <td><?php echo $row_uyeticket['baslik']; ?></td>
          <td align="center"><?php 

if ($row_uyeticket['durum'] == '0') 
{ 
    echo ' <button class="button danger loading-pulse lighten mini-button">Cevap Bekliyor</button> '; 
} 
elseif ($row_uyeticket['durum'] == '1') 
{ 
    echo '<button class="button mini-button">Cevaplandı</button>'; 
} 
elseif ($row_uyeticket['durum'] == '2') 
{ 
    echo '<button class="button mini-button">KAPANDI</button>'; 
} 
else 
{ 
    echo 'HATA'; 
} 

?></td>
          <td align="center"><form id="form<?php echo $row_uyeticket['id']; ?>" name="form<?php echo $row_uyeticket['id']; ?>" method="post" action="d<?php echo $row_uyeticket['id']; ?>-<?php echo permalink($row_uyeticket['baslik']); ?>.htm">
            <label>
              <button class="button mini-button" type="submit" name="durum" id="durum"><i class="fa fa-info"></i> İncele</button>
            </label>
          </form></td>
        </tr>
        <?php } while ($row_uyeticket = mysql_fetch_assoc($uyeticket)); ?>
    </tbody>
  </table>
  <?php } // Show if recordset not empty ?></td>
        </tr>
      </table>
      <hr>
      <p>&nbsp;</p>
    </div>
</div>
<?php
mysql_free_result($lasthistory);

mysql_free_result($urunmID);

mysql_free_result($uyeticket);

mysql_free_result($destops);

mysql_free_result($kreditops);

?>
