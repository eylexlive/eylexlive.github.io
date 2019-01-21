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
$formValidation->addField("epostaadresi", true, "text", "email", "", "", "Geçerli bir E-Posta adresi giriniz.");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an update transaction instance
$upd_paypalsettings = new tNG_update($conn_baglanti);
$tNGs->addTransaction($upd_paypalsettings);
// Register triggers
$upd_paypalsettings->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_paypalsettings->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_paypalsettings->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=paypalsettings");
// Add columns
$upd_paypalsettings->setTable("paypalsettings");
$upd_paypalsettings->addColumn("epostaadresi", "STRING_TYPE", "POST", "epostaadresi");
$upd_paypalsettings->addColumn("parabirimi", "STRING_TYPE", "POST", "parabirimi");
$upd_paypalsettings->addColumn("ulke", "STRING_TYPE", "POST", "ulke");
$upd_paypalsettings->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", "1");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rspaypalsettings = $tNGs->getRecordset("paypalsettings");
$row_rspaypalsettings = mysql_fetch_assoc($rspaypalsettings);
$totalRows_rspaypalsettings = mysql_num_rows($rspaypalsettings);
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
  <li class="active"><i class="fa fa-paypal"></i> Paypal Ayarları</li>
</ul>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-paypal"></i> Paypal ödeme ayarları</h3>
  </div>
  <div class="panel-body">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <table width="100%" cellpadding="2" cellspacing="0" class="KT_tngtable">
        <tr>
          <td width="201" height="45" class="KT_th"><label for="epostaadresi">Paypal E-Posta :</label></td>
          <td width="877" height="45">
          <div class="col-xs-7">
          <input class="form-control" type="text" name="epostaadresi" id="epostaadresi" value="<?php echo KT_escapeAttribute($row_rspaypalsettings['epostaadresi']); ?>" size="32" /></div>
            <?php echo $tNGs->displayFieldHint("epostaadresi");?> <?php echo $tNGs->displayFieldError("paypalsettings", "epostaadresi"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="parabirimi">Para Birimi :</label></td>
          <td height="45">
          <div class="col-xs-3">
          <input class="form-control" name="parabirimi" type="text" id="parabirimi" value="<?php echo KT_escapeAttribute($row_rspaypalsettings['parabirimi']); ?>" size="32" readonly="readonly" />
          </div>
            <?php echo $tNGs->displayFieldHint("parabirimi");?> <?php echo $tNGs->displayFieldError("paypalsettings", "parabirimi"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="ulke">Ülke :</label></td>
          <td height="45">
          <div class="col-xs-3">
          <input class="form-control" name="ulke" type="text" id="ulke" value="<?php echo KT_escapeAttribute($row_rspaypalsettings['ulke']); ?>" size="32" readonly="readonly" /></div>
            <?php echo $tNGs->displayFieldHint("ulke");?> <?php echo $tNGs->displayFieldError("paypalsettings", "ulke"); ?></td>
        </tr>
        <tr class="KT_buttons">
          <td height="45">&nbsp;</td>
          <td height="45"><input class="btn btn-primary btn-lg" type="submit" name="KT_Update1" id="KT_Update1" value="AYARLARI KAYDET" /></td>
        </tr>
      </table>
    </form>
    <p>&nbsp;</p>
  </div>
</div>


</body>
</html>