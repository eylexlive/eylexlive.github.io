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
$formValidation->addField("pageheadtitle", true, "text", "", "", "", "Zorunlu Alan...");
$formValidation->addField("pagetitle", true, "text", "", "", "", "Zorunlu Alan...");
$formValidation->addField("pageactive", true, "text", "", "", "", "Zorunlu Alan...");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an update transaction instance
$upd_pages = new tNG_update($conn_baglanti);
$tNGs->addTransaction($upd_pages);
// Register triggers
$upd_pages->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_pages->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_pages->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=pages");
// Add columns
$upd_pages->setTable("pages");
$upd_pages->addColumn("pageheadtitle", "STRING_TYPE", "POST", "pageheadtitle");
$upd_pages->addColumn("pagetitle", "STRING_TYPE", "POST", "pagetitle");
$upd_pages->addColumn("pagecontent", "STRING_TYPE", "POST", "pagecontent");
$upd_pages->addColumn("pagedate", "DATE_TYPE", "POST", "pagedate");
$upd_pages->addColumn("pageactive", "STRING_TYPE", "POST", "pageactive");
$upd_pages->setPrimaryKey("id", "NUMERIC_TYPE", "GET", "id");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rspages = $tNGs->getRecordset("pages");
$row_rspages = mysql_fetch_assoc($rspages);
$totalRows_rspages = mysql_num_rows($rspages);
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
	<li><a href="index.php?mc=pages"><i class="fa fa-file-o"></i> Sayfaları Listele</a></li>
  <li><a href="?mc=pagesadd"><i class="fa fa-file-o"></i> Yeni Sayfa Ekle</a></li>
  
</ul>
<hr />
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-file-o"></i> Sayfayı Düzenle</h3>
  </div>
  <div class="panel-body">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <table width="100%" cellpadding="2" cellspacing="0" class="KT_tngtable">
        <tr>
          <td width="23%" height="45" class="KT_th"><label for="pageheadtitle">Sayfa Üst Başlık:</label></td>
          <td width="77%" height="45"><input class="form-control" type="text" name="pageheadtitle" id="pageheadtitle" value="<?php echo KT_escapeAttribute($row_rspages['pageheadtitle']); ?>" size="32" />
            <?php echo $tNGs->displayFieldHint("pageheadtitle");?> <?php echo $tNGs->displayFieldError("pages", "pageheadtitle"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="pagetitle">Sayfa Başlığı:</label></td>
          <td height="45"><input class="form-control" type="text" name="pagetitle" id="pagetitle" value="<?php echo KT_escapeAttribute($row_rspages['pagetitle']); ?>" size="32" />
            <?php echo $tNGs->displayFieldHint("pagetitle");?> <?php echo $tNGs->displayFieldError("pages", "pagetitle"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="pagecontent">Sayfa İçeriği:</label></td>
          <td height="45"><textarea name="pagecontent" id="pagecontent" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rspages['pagecontent']); ?></textarea>
           <script src='ckeditor/ckeditor.js' type='text/javascript'> </script>
                    <script type='text/javascript'>
//< ![CDATA[
CKEDITOR.replace( 'pagecontent' );
//]]>
                    </script>
            <?php echo $tNGs->displayFieldHint("pagecontent");?> <?php echo $tNGs->displayFieldError("pages", "pagecontent"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="pagedate">Tarih:</label></td>
          <td height="45"><input name="pagedate" type="text" class="form-control" id="pagedate" value="<?php echo tarihcevir(date("m/d/Y H:i")); ?>" size="32" readonly="readonly" />
            <?php echo $tNGs->displayFieldHint("pagedate");?> <?php echo $tNGs->displayFieldError("pages", "pagedate"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="pageactive">Sayfa Yayınlansınmı?:</label></td>
          <td height="45"><select name="pageactive" id="pageactive">
            <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rspages['pageactive'])))) {echo "SELECTED";} ?>>Evet</option>
            <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute($row_rspages['pageactive'])))) {echo "SELECTED";} ?>>Hayır</option>
          </select>
            <?php echo $tNGs->displayFieldError("pages", "pageactive"); ?></td>
        </tr>
        <tr class="KT_buttons">
          <td height="45">&nbsp;</td>
          <td height="45"><input type="submit" class="btn btn-primary" name="KT_Update1" id="KT_Update1" value="KAYDET" /> <a href="index.php?mc=pages" class="btn btn-warning">GERİ DÖN</a></td>
        </tr>
      </table>
    </form>
    <p>&nbsp;</p>
  </div>
</div>

</body>
</html>