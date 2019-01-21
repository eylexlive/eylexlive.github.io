
<?php
require_once('../Connections/baglanti.php'); 

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
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an update transaction instance
$upd_ayarlar = new tNG_update($conn_baglanti);
$tNGs->addTransaction($upd_ayarlar);
// Register triggers
$upd_ayarlar->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_ayarlar->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_ayarlar->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=websettings");
$upd_ayarlar->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$upd_ayarlar->setTable("ayarlar");
$upd_ayarlar->addColumn("sitelogo", "FILE_TYPE", "FILES", "sitelogo");
$upd_ayarlar->addColumn("siteurl", "STRING_TYPE", "POST", "siteurl");
$upd_ayarlar->addColumn("title", "STRING_TYPE", "POST", "title");
$upd_ayarlar->addColumn("description", "STRING_TYPE", "POST", "description");
$upd_ayarlar->addColumn("keywords", "STRING_TYPE", "POST", "keywords");
$upd_ayarlar->addColumn("copy", "STRING_TYPE", "POST", "copy");
$upd_ayarlar->addColumn("google_analytics", "STRING_TYPE", "POST", "google_analytics");
$upd_ayarlar->addColumn("template", "STRING_TYPE", "POST", "template");
$upd_ayarlar->addColumn("facebook", "STRING_TYPE", "POST", "facebook");
$upd_ayarlar->addColumn("twitter", "STRING_TYPE", "POST", "twitter");
$upd_ayarlar->addColumn("youtube", "STRING_TYPE", "POST", "youtube");
$upd_ayarlar->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", "1");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsayarlar = $tNGs->getRecordset("ayarlar");
$row_rsayarlar = mysql_fetch_assoc($rsayarlar);
$totalRows_rsayarlar = mysql_num_rows($rsayarlar);

//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("sitelogo");
  $uploadObj->setDbFieldName("sitelogo");
  $uploadObj->setFolder("../images/");
  $uploadObj->setMaxSize(2500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger
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
  <li class="active">Site Ayarları</li>
</ul>
<?php
	echo $tNGs->getErrorMsg();
?>
<form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
  <table width="100%" cellpadding="2" cellspacing="0" class="KT_tngtable">
    <tr>
      <td width="19%" height="45" class="KT_th">Şuanki Logo:</td>
      <td width="81%" height="45"><img src="../images/<?php echo $row_rsayarlar['sitelogo']; ?>" /></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="sitelogo">Site Logo:(300px - 60px)</label></td>
      <td height="45"><input type="file" name="sitelogo" id="sitelogo" size="32" />
        <?php echo $tNGs->displayFieldError("ayarlar", "sitelogo"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="siteurl">Site URL:</label></td>
      <td height="45"><input class="form-control" type="text" name="siteurl" id="siteurl" value="<?php echo KT_escapeAttribute($row_rsayarlar['siteurl']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("siteurl");?> <?php echo $tNGs->displayFieldError("ayarlar", "siteurl"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="title">Site Başlık:</label></td>
      <td height="45"><input class="form-control" type="text" name="title" id="title" value="<?php echo KT_escapeAttribute($row_rsayarlar['title']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("title");?> <?php echo $tNGs->displayFieldError("ayarlar", "title"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="description">Açıklama:</label></td>
      <td height="45"><textarea class="form-control" name="description" cols="32" id="description"><?php echo KT_escapeAttribute($row_rsayarlar['description']); ?></textarea>
        <?php echo $tNGs->displayFieldHint("description");?> <?php echo $tNGs->displayFieldError("ayarlar", "description"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="keywords">Anahtar Kelimeler:</label></td>
      <td height="45"><input class="form-control" type="text" name="keywords" id="keywords" value="<?php echo KT_escapeAttribute($row_rsayarlar['keywords']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("keywords");?> <?php echo $tNGs->displayFieldError("ayarlar", "keywords"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="copy">Copyright:</label></td>
      <td height="45"><input class="form-control" type="text" name="copy" id="copy" value="<?php echo KT_escapeAttribute($row_rsayarlar['copy']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("copy");?> <?php echo $tNGs->displayFieldError("ayarlar", "copy"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="google_analytics">Google Analytics:</label></td>
      <td height="45"><textarea class="form-control" name="google_analytics" cols="32" id="google_analytics"><?php echo KT_escapeAttribute($row_rsayarlar['google_analytics']); ?></textarea>
        <?php echo $tNGs->displayFieldHint("google_analytics");?> <?php echo $tNGs->displayFieldError("ayarlar", "google_analytics"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="template">Template:</label></td>
      <td height="45"><select class="form-control" name="template" id="template">
        <option value="tema1.css" <?php if (!(strcmp("tema1.css", KT_escapeAttribute($row_rsayarlar['template'])))) {echo "SELECTED";} ?>>Tema 1</option>
        <option value="tema2.css" <?php if (!(strcmp("tema2.css", KT_escapeAttribute($row_rsayarlar['template'])))) {echo "SELECTED";} ?>>Tema 2</option>
        <option value="tema3.css" <?php if (!(strcmp("tema3.css", KT_escapeAttribute($row_rsayarlar['template'])))) {echo "SELECTED";} ?>>Tema 3</option>
        <option value="tema4.css" <?php if (!(strcmp("tema4.css", KT_escapeAttribute($row_rsayarlar['template'])))) {echo "SELECTED";} ?>>Tema 4</option>
        <option value="tema5.css" <?php if (!(strcmp("tema5.css", KT_escapeAttribute($row_rsayarlar['template'])))) {echo "SELECTED";} ?>>Tema 5</option>
      </select>
        <?php echo $tNGs->displayFieldError("ayarlar", "template"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="facebook">Facebook:</label></td>
      <td height="45"><input class="form-control" type="text" name="facebook" id="facebook" value="<?php echo KT_escapeAttribute($row_rsayarlar['facebook']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("facebook");?> <?php echo $tNGs->displayFieldError("ayarlar", "facebook"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="twitter">Twitter:</label></td>
      <td height="45"><input class="form-control" type="text" name="twitter" id="twitter" value="<?php echo KT_escapeAttribute($row_rsayarlar['twitter']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("twitter");?> <?php echo $tNGs->displayFieldError("ayarlar", "twitter"); ?></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="youtube">Youtube:</label></td>
      <td height="45"><input class="form-control" type="text" name="youtube" id="youtube" value="<?php echo KT_escapeAttribute($row_rsayarlar['youtube']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("youtube");?> <?php echo $tNGs->displayFieldError("ayarlar", "youtube"); ?></td>
    </tr>
    <tr class="KT_buttons">
      <td height="45">&nbsp;</td>
      <td height="45"><input class="btn btn-primary" type="submit" name="KT_Update1" id="KT_Update1" value="   KAYDET   " /></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>