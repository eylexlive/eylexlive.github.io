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
$formValidation->addField("baslik", true, "text", "", "", "", "Zorunlu Alan!..");
$formValidation->addField("kisaaciklama", true, "text", "", "", "", "Zorunlu Alan!..");
$formValidation->addField("ozellik1", true, "text", "", "", "", "Zorunlu Alan!..");
$formValidation->addField("miktar", true, "numeric", "", "", "", "Zorunlu Alan!..");
$formValidation->addField("fiyati", true, "numeric", "", "", "", "Zorunlu Alan!..");
$formValidation->addField("gunu", true, "text", "", "", "", "Zorunlu Alan!..");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_market = new tNG_insert($conn_baglanti);
$tNGs->addTransaction($ins_market);
// Register triggers
$ins_market->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_market->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_market->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=shop");
// Add columns
$ins_market->setTable("market");
$ins_market->addColumn("baslik", "STRING_TYPE", "POST", "baslik");
$ins_market->addColumn("kisaaciklama", "STRING_TYPE", "POST", "kisaaciklama");
$ins_market->addColumn("ozellik1", "STRING_TYPE", "POST", "ozellik1");
$ins_market->addColumn("ozellik2", "STRING_TYPE", "POST", "ozellik2");
$ins_market->addColumn("ozellik3", "STRING_TYPE", "POST", "ozellik3");
$ins_market->addColumn("miktar", "NUMERIC_TYPE", "POST", "miktar");
$ins_market->addColumn("fiyati", "NUMERIC_TYPE", "POST", "fiyati");
$ins_market->addColumn("gunu", "STRING_TYPE", "POST", "gunu");
$ins_market->addColumn("sablon", "STRING_TYPE", "POST", "sablon");
$ins_market->addColumn("eklemetarihi", "STRING_TYPE", "POST", "eklemetarihi");
$ins_market->addColumn("marketdurumu", "STRING_TYPE", "POST", "marketdurumu");
$ins_market->setPrimaryKey("marketID", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsmarket = $tNGs->getRecordset("market");
$row_rsmarket = mysql_fetch_assoc($rsmarket);
$totalRows_rsmarket = mysql_num_rows($rsmarket);
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
  <li class="active"><a href="?mc=shopadd"><i class="fa fa-cart-plus"></i> Yeni Ürün Ekle</a></li>
  <li><a href="?mc=shop">Ürünleri Listele</a></li>
</ul>
<hr />
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-cart-plus"></i> YENİ MARKET ÜRÜNÜ EKLE</h3>
  </div>
  <div class="panel-body">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <table width="100%" cellpadding="2" cellspacing="0" class="KT_tngtable">
        <tr>
          <td height="45" class="KT_th"><label for="baslik">Ürün Adı:</label></td>
          <td height="45"><input class="form-control" type="text" name="baslik" id="baslik" value="<?php echo KT_escapeAttribute($row_rsmarket['baslik']); ?>" size="32" />
            <?php echo $tNGs->displayFieldHint("baslik");?> <?php echo $tNGs->displayFieldError("market", "baslik"); ?></td>
        </tr>
        <tr>
          <td height="22" class="KT_th"><label for="kisaaciklama">Ürün Açıklaması:</label></td>
          <td height="45" rowspan="2"><textarea class="form-control" name="kisaaciklama" id="kisaaciklama" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsmarket['kisaaciklama']); ?></textarea>
            <?php echo $tNGs->displayFieldHint("kisaaciklama");?> <?php echo $tNGs->displayFieldError("market", "kisaaciklama"); ?></td>
        </tr>
        <tr>
          <td height="23" class="KT_th">&nbsp;</td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="ozellik1">Ürün Özellik 1:</label></td>
          <td height="45"><input type="text" class="form-control" name="ozellik1" id="ozellik1" value="<?php echo KT_escapeAttribute($row_rsmarket['ozellik1']); ?>" size="32" />
            <?php echo $tNGs->displayFieldHint("ozellik1");?> <?php echo $tNGs->displayFieldError("market", "ozellik1"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="ozellik2">Ürün Özellik 2:</label></td>
          <td height="45"><input type="text" class="form-control" name="ozellik2" id="ozellik2" value="<?php echo KT_escapeAttribute($row_rsmarket['ozellik2']); ?>" size="32" />
            <?php echo $tNGs->displayFieldHint("ozellik2");?> <?php echo $tNGs->displayFieldError("market", "ozellik2"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="ozellik3">Ürün Özellik 3:</label></td>
          <td height="45"><input type="text" class="form-control" name="ozellik3" id="ozellik3" value="<?php echo KT_escapeAttribute($row_rsmarket['ozellik3']); ?>" size="32" />
            <?php echo $tNGs->displayFieldHint("ozellik3");?> <?php echo $tNGs->displayFieldError("market", "ozellik3"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="miktar">Ürün Miktarı:</label></td>
          <td height="45"><input type="text" class="form-control" name="miktar" id="miktar" value="<?php echo KT_escapeAttribute($row_rsmarket['miktar']); ?>" size="32" />
            <?php echo $tNGs->displayFieldHint("miktar");?> <?php echo $tNGs->displayFieldError("market", "miktar"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="fiyati">Ürün Fiyati:</label></td>
          <td height="45"><input type="text" class="form-control" name="fiyati" id="fiyati" value="<?php echo KT_escapeAttribute($row_rsmarket['fiyati']); ?>" size="32" />
            <?php echo $tNGs->displayFieldHint("fiyati");?> <?php echo $tNGs->displayFieldError("market", "fiyati"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="gunu">Ürün Süresi:</label></td>
          <td height="45"><select class="form-control" name="gunu" id="gunu">
            <option value="Ayl&#305;k" <?php if (!(strcmp("Aylık", KT_escapeAttribute($row_rsmarket['gunu'])))) {echo "SELECTED";} ?>>Aylık</option>
            <option value="Y&#305;ll&#305;k" <?php if (!(strcmp("Yıllık", KT_escapeAttribute($row_rsmarket['gunu'])))) {echo "SELECTED";} ?>>Yıllık</option>
            <option value="Haftal&#305;k" <?php if (!(strcmp("Haftalık", KT_escapeAttribute($row_rsmarket['gunu'])))) {echo "SELECTED";} ?>>Haftalık</option>
            <option value="S&uuml;resiz" <?php if (!(strcmp("Süresiz", KT_escapeAttribute($row_rsmarket['gunu'])))) {echo "SELECTED";} ?>>Süresiz</option>
          </select>
            <?php echo $tNGs->displayFieldError("market", "gunu"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="sablon">Ürün Şablonu:</label></td>
          <td height="45"><select class="form-control" name="sablon" id="sablon">
            <option value="panel-default" <?php if (!(strcmp("panel-default", KT_escapeAttribute($row_rsmarket['sablon'])))) {echo "SELECTED";} ?>>Default Tema</option>
            <option value="panel-danger" <?php if (!(strcmp("panel-danger", KT_escapeAttribute($row_rsmarket['sablon'])))) {echo "SELECTED";} ?>>Kırmızı Tema</option>
            <option value="panel-info" <?php if (!(strcmp("panel-info", KT_escapeAttribute($row_rsmarket['sablon'])))) {echo "SELECTED";} ?>>Açık Mavi Tema</option>
            <option value="panel-success" <?php if (!(strcmp("panel-success", KT_escapeAttribute($row_rsmarket['sablon'])))) {echo "SELECTED";} ?>>Yeşil Tema</option>
            <option value="panel-primary" <?php if (!(strcmp("panel-primary", KT_escapeAttribute($row_rsmarket['sablon'])))) {echo "SELECTED";} ?>>Koyu Mavi</option>
            <option value="panel-warning" <?php if (!(strcmp("panel-warning", KT_escapeAttribute($row_rsmarket['sablon'])))) {echo "SELECTED";} ?>>Turuncu Tema</option>
          </select>
            <?php echo $tNGs->displayFieldError("market", "sablon"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="eklemetarihi">Tarih:</label></td>
          <td height="45"><input class="form-control" type="text" name="eklemetarihi" id="eklemetarihi" value="<?php echo KT_escapeAttribute($row_rsmarket['eklemetarihi']); ?>" size="32" />
            <?php echo $tNGs->displayFieldHint("eklemetarihi");?> <?php echo $tNGs->displayFieldError("market", "eklemetarihi"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="marketdurumu">Market Durumu:</label></td>
          <td height="45"><select class="form-control" name="marketdurumu" id="marketdurumu">
            <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rsmarket['marketdurumu'])))) {echo "SELECTED";} ?>>Aktif</option>
            <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute($row_rsmarket['marketdurumu'])))) {echo "SELECTED";} ?>>Pasif</option>
          </select>
            <?php echo $tNGs->displayFieldError("market", "marketdurumu"); ?></td>
        </tr>
        <tr class="KT_buttons">
          <td height="45">&nbsp;</td>
          <td height="45"><input class="btn btn-primary" type="submit" name="KT_Insert1" id="KT_Insert1" value="KAYDET" /></td>
        </tr>
      </table>
    </form>
    <p>&nbsp;</p>
  </div>
</div>

</body>
</html>