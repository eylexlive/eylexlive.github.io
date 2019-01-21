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
$formValidation->addField("bankaadi", true, "text", "", "", "", "Zorunlu Alan !");
$formValidation->addField("hesabsahibi", true, "text", "", "", "", "Zorunlu Alan !");
$formValidation->addField("hesapno", true, "text", "", "", "", "Zorunlu Alan !");
$formValidation->addField("sube", true, "text", "", "", "", "Zorunlu Alan !");
$formValidation->addField("aktif", true, "text", "", "", "", "Zorunlu Alan !");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_bankahesaplari = new tNG_insert($conn_baglanti);
$tNGs->addTransaction($ins_bankahesaplari);
// Register triggers
$ins_bankahesaplari->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_bankahesaplari->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_bankahesaplari->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=bankadetay");
// Add columns
$ins_bankahesaplari->setTable("bankahesaplari");
$ins_bankahesaplari->addColumn("bankaadi", "STRING_TYPE", "POST", "bankaadi");
$ins_bankahesaplari->addColumn("hesabsahibi", "STRING_TYPE", "POST", "hesabsahibi");
$ins_bankahesaplari->addColumn("ibanno", "STRING_TYPE", "POST", "ibanno");
$ins_bankahesaplari->addColumn("hesapno", "STRING_TYPE", "POST", "hesapno");
$ins_bankahesaplari->addColumn("sube", "STRING_TYPE", "POST", "sube");
$ins_bankahesaplari->addColumn("aktif", "STRING_TYPE", "POST", "aktif");
$ins_bankahesaplari->setPrimaryKey("id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsbankahesaplari = $tNGs->getRecordset("bankahesaplari");
$row_rsbankahesaplari = mysql_fetch_assoc($rsbankahesaplari);
$totalRows_rsbankahesaplari = mysql_num_rows($rsbankahesaplari);
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
<ul class="nav nav-pills">
	<li><a href="index.php?mc=bankadetay"><i class="fa fa-university"></i> Eklenen Bankalar</a></li>
    <li  class="active"><a href="?mc=bankaadd"><i class="fa fa-university"></i> Yeni Banka Ekle</a></li>
  
</ul>

<hr />
<?php
	echo $tNGs->getErrorMsg();
?>
<form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
    <tr>
      <td width="121" height="45" class="KT_th"><label for="bankaadi">Banka Adı:</label></td>
      <td width="376" height="45"><input class="form-control" type="text" name="bankaadi" id="bankaadi" value="<?php echo KT_escapeAttribute($row_rsbankahesaplari['bankaadi']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("bankaadi");?> <?php echo $tNGs->displayFieldError("bankahesaplari", "bankaadi"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label  for="hesabsahibi">Hesap Sahibi:</label></td>
      <td height="45"><input type="text" name="hesabsahibi" class="form-control" id="hesabsahibi" value="<?php echo KT_escapeAttribute($row_rsbankahesaplari['hesabsahibi']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("hesabsahibi");?> <?php echo $tNGs->displayFieldError("bankahesaplari", "hesabsahibi"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="ibanno">IBANno:</label></td>
      <td height="45"><input type="text" class="form-control" name="ibanno" id="ibanno" value="<?php echo KT_escapeAttribute($row_rsbankahesaplari['ibanno']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("ibanno");?> <?php echo $tNGs->displayFieldError("bankahesaplari", "ibanno"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="hesapno">Hesap NO:</label></td>
      <td height="45"><input type="text" class="form-control" name="hesapno" id="hesapno" value="<?php echo KT_escapeAttribute($row_rsbankahesaplari['hesapno']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("hesapno");?> <?php echo $tNGs->displayFieldError("bankahesaplari", "hesapno"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="sube">Şube Kodu:</label></td>
      <td height="45"><input type="text" class="form-control" name="sube" id="sube" value="<?php echo KT_escapeAttribute($row_rsbankahesaplari['sube']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("sube");?> <?php echo $tNGs->displayFieldError("bankahesaplari", "sube"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="aktif">Durum:</label></td>
      <td height="45"><select class="form-control" name="aktif" id="aktif">
        <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rsbankahesaplari['aktif'])))) {echo "SELECTED";} ?>>Aktif</option>
        <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute($row_rsbankahesaplari['aktif'])))) {echo "SELECTED";} ?>>Pasif</option>
      </select>
        <?php echo $tNGs->displayFieldError("bankahesaplari", "aktif"); ?></td>
    </tr>
    <tr class="KT_buttons">
      <td height="45">&nbsp;</td>
      <td height="45"><input class="btn btn-primary" type="submit" name="KT_Insert1" id="KT_Insert1" value="KAYDET" /></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>