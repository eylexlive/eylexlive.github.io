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
$formValidation->addField("durum", true, "text", "", "", "", "Zorunlu Alan...");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an update transaction instance
$upd_credituser = new tNG_update($conn_baglanti);
$tNGs->addTransaction($upd_credituser);
// Register triggers
$upd_credituser->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_credituser->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_credituser->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=credit");
// Add columns
$upd_credituser->setTable("credituser");
$upd_credituser->addColumn("musteriID", "NUMERIC_TYPE", "POST", "musteriID");
$upd_credituser->addColumn("musteriadi", "STRING_TYPE", "POST", "musteriadi");
$upd_credituser->addColumn("method", "STRING_TYPE", "POST", "method");
$upd_credituser->addColumn("miktar", "STRING_TYPE", "POST", "miktar");
$upd_credituser->addColumn("durum", "STRING_TYPE", "POST", "durum");
$upd_credituser->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscredituser = $tNGs->getRecordset("credituser");
$row_rscredituser = mysql_fetch_assoc($rscredituser);
$totalRows_rscredituser = mysql_num_rows($rscredituser);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
</head>
<body>
<ul class="breadcrumb">
	
    <li  class="active"><a href="?mc=credit">Onay Bekleyen Kredi Hareketleri</a></li>
    <li class="active">Onaylanan Kredi hareketleri</li>
</ul>

<hr />
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Kredi Onayı</h3>
  </div>
  <div class="panel-body">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <table width="100%" cellpadding="2" cellspacing="0" class="KT_tngtable">
        <tr>
          <td width="179" height="45" class="KT_th"><label for="musteriID">MusteriID:</label></td>
          <td width="882" height="45"><div class="input-group col-xs-2"><input style="text-align:center; font-weight:bold" class="form-control" name="musteriID" type="text" id="musteriID" value="<?php echo KT_escapeAttribute($row_rscredituser['musteriID']); ?>" size="5" readonly="readonly" /></div>
            <?php echo $tNGs->displayFieldHint("musteriID");?> <?php echo $tNGs->displayFieldError("credituser", "musteriID"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="musteriadi">Müsteri Adı:</label></td>
          <td height="45"><div class="input-group col-xs-5"><input class="form-control" name="musteriadi" type="text" id="musteriadi" value="<?php echo KT_escapeAttribute($row_rscredituser['musteriadi']); ?>" size="32" readonly="readonly" /></div>
            <?php echo $tNGs->displayFieldHint("musteriadi");?> <?php echo $tNGs->displayFieldError("credituser", "musteriadi"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="method">Ödeme Yöntemi:</label></td>
          <td height="45"><div class="input-group col-xs-4">
          <input type="hidden" name="method" id="method" value="<?php echo $row_rscredituser['method']; ?>"/>
          <?php 

if ($row_rscredituser['method'] == '1') 
{ 
    echo ' <span class="btn btn-xs btn-default"><i class="fa fa-money"></i> Banka Havalesi</span> '; 
} 
elseif ($row_rscredituser['method'] == '2') 
{ 
    echo '<span class="btn btn-xs btn-default"><i class="fa fa-cc-paypal"></i> Paypal ile Öde</span>'; 
} 
else 
{ 
    echo '<span class="btn btn-xs btn-danger"><i class="fa fa-exclamation-triangle"></i> 404 Hata</span>'; 
} 

?>
          </div>
            <?php echo $tNGs->displayFieldError("credituser", "method"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="miktar">Miktar:</label></td>
          <td height="45">
          <input type="hidden" name="miktar" id="miktar" value="<?php echo $row_rscredituser['miktar']; ?>"/>
           <div class="input-group col-xs-3">
   
          <input class="form-control" value="<?php echo $row_rscredituser['miktar']; ?>.00" size="32" readonly="readonly" /> <span class="input-group-addon"><i class="fa fa-try" style="font-size:18px"></i></span></div>
            <?php echo $tNGs->displayFieldHint("miktar");?> <?php echo $tNGs->displayFieldError("credituser", "miktar"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="durum">Durum:</label></td>
          <td height="45"><div class="input-group col-xs-4"><select class="form-control" name="durum" id="durum">
            <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rscredituser['durum'])))) {echo "SELECTED";} ?>>ÖDENDİ</option>
            <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute($row_rscredituser['durum'])))) {echo "SELECTED";} ?>>ÖDEME BEKLİYOR</option>
          </select></div>
            <?php echo $tNGs->displayFieldError("credituser", "durum"); ?></td>
        </tr>
        <tr class="KT_buttons">
          <td height="45">&nbsp;</td>
          <td height="45"><input type="submit" class="btn btn-primary" name="KT_Update1" id="KT_Update1" value="KAYDET ve ONAYLA" /></td>
        </tr>
      </table>
    </form>
    <p>&nbsp;</p>
  </div>
</div>

</body>
</html>