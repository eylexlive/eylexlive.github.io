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
$formValidation->addField("baslangictarihi", true, "text", "", "", "", "Lütfen ürün başlangıç tarihini belirleyin...");
$formValidation->addField("bitistarihi", true, "text", "", "", "", "Lütfen ürünün sona erme tarihini belirleyin...");
$formValidation->addField("servisdurumu", true, "text", "", "", "", "Lütfen bir işlem seçiniz...");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an update transaction instance
$upd_cart = new tNG_update($conn_baglanti);
$tNGs->addTransaction($upd_cart);
// Register triggers
$upd_cart->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_cart->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_cart->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=cart");
// Add columns
$upd_cart->setTable("cart");
$upd_cart->addColumn("marketID", "NUMERIC_TYPE", "POST", "marketID");
$upd_cart->addColumn("musteriID", "NUMERIC_TYPE", "POST", "musteriID");
$upd_cart->addColumn("musteriadi", "STRING_TYPE", "POST", "musteriadi");
$upd_cart->addColumn("servisadi", "STRING_TYPE", "POST", "servisadi");
$upd_cart->addColumn("servisaciklama", "STRING_TYPE", "POST", "servisaciklama");
$upd_cart->addColumn("servisfiyat", "STRING_TYPE", "POST", "servisfiyat");
$upd_cart->addColumn("servissure", "STRING_TYPE", "POST", "servissure");
$upd_cart->addColumn("servismiktar", "NUMERIC_TYPE", "POST", "servismiktar");
$upd_cart->addColumn("baslangictarihi", "STRING_TYPE", "POST", "baslangictarihi");
$upd_cart->addColumn("bitistarihi", "STRING_TYPE", "POST", "bitistarihi");
$upd_cart->addColumn("servisdurumu", "STRING_TYPE", "POST", "servisdurumu");
$upd_cart->setPrimaryKey("siparisID", "NUMERIC_TYPE", "GET", "siparisID");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscart = $tNGs->getRecordset("cart");
$row_rscart = mysql_fetch_assoc($rscart);
$totalRows_rscart = mysql_num_rows($rscart);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
  </script>
  <script>
  $(function() {
    $( "#datepicker2" ).datepicker();
  });
  </script>
  <script>
  $(function() {
    $( document ).tooltip();
  });
  </script>
  <style>
  label {
    display: inline-block;
    width: 5em;
  }
  </style>
<?php echo $tNGs->displayValidationRules();?>
</head>
<body>

<ul class="nav nav-pills">
	<li class="active"><a href="index.php?mc=cart"><i class="fa fa-balance-scale"></i> Ödeme Bekleyen Siparişler</a></li>
    <li><a href="?mc=shopadd"><i class="fa fa-cube"></i> Onaylanan Siparişler</a></li>
    <li><a href="?mc=shopadd"><i class="fa fa-flag"></i> Hizmet Süresi Sona Eren Siparişler</a></li>
  
</ul>
<hr />
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-paper-plane-o"></i> SİPARİŞ DETAYLARI <small>Siparişi veren üye; </small></h3>
  </div>
  <div class="panel-body">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <table width="100%" cellpadding="2" cellspacing="0" class="KT_tngtable">
        <tr>
          <td width="29%" height="45" class="KT_th"><label for="marketID">Market ID:</label></td>
          <td width="71%" height="45"><input name="marketID" title="Market ID Numarası" type="text" class="form-control" id="marketID" value="<?php echo KT_escapeAttribute($row_rscart['marketID']); ?>" size="32" readonly="readonly" />
          <?php echo $tNGs->displayFieldHint("marketID");?> <?php echo $tNGs->displayFieldError("cart", "marketID"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="musteriadi">Müşteri Adı:</label></td>
          <td height="45"><input style="font-size:18px" title="Siparişi veren müşteri adı <?php echo $row_rscart['musteriadi']; ?>" name="musteriadi" type="text" class="form-control" id="musteriadi" value="<?php echo KT_escapeAttribute($row_rscart['musteriadi']); ?>" size="32" readonly="readonly" />
            <?php echo $tNGs->displayFieldHint("musteriadi");?> <?php echo $tNGs->displayFieldError("cart", "musteriadi"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="servisadi">Ürün Adı:</label></td>
          <td height="45"><input name="servisadi" title="<?php echo $row_rscart['musteriadi']; ?> Müşterisinin vermiş olduğu ürünün adı" type="text" class="form-control" id="servisadi" value="<?php echo KT_escapeAttribute($row_rscart['servisadi']); ?>" size="32" readonly="readonly" />
            <?php echo $tNGs->displayFieldHint("servisadi");?> <?php echo $tNGs->displayFieldError("cart", "servisadi"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="servisaciklama">Ürün Açıklama:</label></td>
          <td height="45"><textarea class="form-control" name="servisaciklama" id="servisaciklama" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rscart['servisaciklama']); ?>
          </textarea>
            <?php echo $tNGs->displayFieldHint("servisaciklama");?> <?php echo $tNGs->displayFieldError("cart", "servisaciklama"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="servisfiyat">Ürün Fiyatı:</label></td>
          <td height="45">
          <div class="input-group">
    <span class="input-group-addon"><i class="fa fa-try" style="font-size:18px"></i></span>
          <input title="Marketi oluştururken sizin bu ürene vermiş olduğunuz ürün fiyatıdır." name="servisfiyat" style="font-size:18px" type="text" class="form-control" id="servisfiyat" value="<?php echo KT_escapeAttribute($row_rscart['servisfiyat']); ?>" size="32" readonly="readonly" /></div>
            <?php echo $tNGs->displayFieldHint("servisfiyat");?> <?php echo $tNGs->displayFieldError("cart", "servisfiyat"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="servissure">Servis Süresi:</label></td>
          <td height="45"><input style="font-size:18px" name="servissure" type="text" class="form-control" id="servissure" value="<?php echo KT_escapeAttribute($row_rscart['servissure']); ?>" size="32" readonly="readonly" />
            <?php echo $tNGs->displayFieldHint("servissure");?> <?php echo $tNGs->displayFieldError("cart", "servissure"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="servismiktar">Ürün Miktarı:</label></td>
          <td height="45"><input style="font-size:18px" name="servismiktar" type="text" class="form-control" id="servismiktar" value="<?php echo KT_escapeAttribute($row_rscart['servismiktar']); ?>" size="32" readonly="readonly" />
            <?php echo $tNGs->displayFieldHint("servismiktar");?> <?php echo $tNGs->displayFieldError("cart", "servismiktar"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="baslangictarihi">Ürün Başlangıç Tarihi:</label></td>
          <td height="45">
           <div class="input-group">
    <span class="input-group-addon"><i class="fa fa-calendar" style="font-size:18px"></i></span>
          <input class="form-control" type="text" name="baslangictarihi" id="datepicker" value="<?php echo KT_escapeAttribute($row_rscart['baslangictarihi']); ?>" size="32" /></div>
            <?php echo $tNGs->displayFieldHint("baslangictarihi");?> <?php echo $tNGs->displayFieldError("cart", "baslangictarihi"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="bitistarihi">Ürün Bitiş Tarihii:</label></td>
          <td height="45">
           <div class="input-group">
    <span class="input-group-addon"><i class="fa fa-calendar" style="font-size:18px"></i></span>
          <input class="form-control" type="text"  name="bitistarihi" id="datepicker2" value="<?php echo KT_escapeAttribute($row_rscart['bitistarihi']); ?>" size="32" /></div>
            <?php echo $tNGs->displayFieldHint("bitistarihi");?> <?php echo $tNGs->displayFieldError("cart", "bitistarihi"); ?>
            
            </td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="servisdurumu">Sepet Durumu:</label></td>
          <td height="45"><div class="form-group has-success"><select class="form-control" name="servisdurumu" id="servisdurumu" style="font-size:15px">
            <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rscart['servisdurumu'])))) {echo "SELECTED";} ?>>Durum Aktif</option>
            <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute($row_rscart['servisdurumu'])))) {echo "SELECTED";} ?>>Ödeme Onayı Bekliyor</option>
            <option value="2" <?php if (!(strcmp(2, KT_escapeAttribute($row_rscart['servisdurumu'])))) {echo "SELECTED";} ?>>Hizmet Süresi Doldu</option>
          </select></div>
            <?php echo $tNGs->displayFieldError("cart", "servisdurumu"); ?></td>
        </tr>
        <tr class="KT_buttons">
          <td height="45">&nbsp;</td>
          <td height="45"><input type="submit" class="btn btn-primary" name="KT_Update1" id="KT_Update1" value="SİPARİŞİ KAYDET" /> <input value="SİPARİŞİ SİL" type="button" class="btn btn-danger" name="deleteButton" onclick="if(deleteConfirm()) window.location='index.php?mc=cartdelete&siparisID=<?php echo $row_rscart['siparisID']; ?>';" > <a href="index.php?mc=cart" class="btn btn-warning">GERİ DÖN</a></td>
        </tr>
      </table>
      <input type="hidden" name="musteriID" id="musteriID" value="<?php echo KT_escapeAttribute($row_rscart['musteriID']); ?>" />
    </form>
    <p>&nbsp;</p>
  </div>
</div>


</body>
</html>