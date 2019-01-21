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
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an update transaction instance
$upd_smtsettings = new tNG_update($conn_baglanti);
$tNGs->addTransaction($upd_smtsettings);
// Register triggers
$upd_smtsettings->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_smtsettings->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_smtsettings->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=smtp");
// Add columns
$upd_smtsettings->setTable("smtsettings");
$upd_smtsettings->addColumn("debug_email_to", "STRING_TYPE", "POST", "debug_email_to");
$upd_smtsettings->addColumn("debug_email_subject", "STRING_TYPE", "POST", "debug_email_subject");
$upd_smtsettings->addColumn("debug_email_from", "STRING_TYPE", "POST", "debug_email_from");
$upd_smtsettings->addColumn("email_host", "STRING_TYPE", "POST", "email_host");
$upd_smtsettings->addColumn("email_user", "STRING_TYPE", "POST", "email_user");
$upd_smtsettings->addColumn("email_port", "STRING_TYPE", "POST", "email_port");
$upd_smtsettings->addColumn("email_password", "STRING_TYPE", "POST", "email_password");
$upd_smtsettings->addColumn("email_defaultFrom", "STRING_TYPE", "POST", "email_defaultFrom");
$upd_smtsettings->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", "1");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rssmtsettings = $tNGs->getRecordset("smtsettings");
$row_rssmtsettings = mysql_fetch_assoc($rssmtsettings);
$totalRows_rssmtsettings = mysql_num_rows($rssmtsettings);
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
  <li class="active">SMTP Mail Ayarları</li>
</ul>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-database"></i> SMTP Mail Ayarları</h3>
  </div>
  <div class="panel-body">
<form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
  <table width="100%" cellpadding="2" cellspacing="0" class="KT_tngtable">
    <tr>
      <td width="17%" height="45" class="KT_th"><label for="debug_email_to">Debug Email To:</label></td>
      <td width="83%" height="45"><input class="form-control" type="text" name="debug_email_to" id="debug_email_to" value="<?php echo KT_escapeAttribute($row_rssmtsettings['debug_email_to']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("debug_email_to");?> <?php echo $tNGs->displayFieldError("smtsettings", "debug_email_to"); ?><p class="help-block">E-Posta Adresi</p></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="debug_email_subject">Debug Email Subject:</label></td>
      <td height="45"><input class="form-control" type="text" name="debug_email_subject" id="debug_email_subject" value="<?php echo KT_escapeAttribute($row_rssmtsettings['debug_email_subject']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("debug_email_subject");?> <?php echo $tNGs->displayFieldError("smtsettings", "debug_email_subject"); ?> <p class="help-block">E-Posta Başlığı</p></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="debug_email_from">Debug Email From:</label></td>
      <td height="45"><input class="form-control" type="text" name="debug_email_from" id="debug_email_from" value="<?php echo KT_escapeAttribute($row_rssmtsettings['debug_email_from']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("debug_email_from");?> <?php echo $tNGs->displayFieldError("smtsettings", "debug_email_from"); ?> <p class="help-block">E-Posta Adresi</p></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="email_host">Email Host:</label></td>
      <td height="45"><input class="form-control" type="text" name="email_host" id="email_host" value="<?php echo KT_escapeAttribute($row_rssmtsettings['email_host']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("email_host");?> <?php echo $tNGs->displayFieldError("smtsettings", "email_host"); ?> <p class="help-block">smt &amp;&amp; pop3 Ben hotmail smtp ayarlarını tercih ederim!</p></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="email_user">Email User:</label></td>
      <td height="45"><input class="form-control" type="text" name="email_user" id="email_user" value="<?php echo KT_escapeAttribute($row_rssmtsettings['email_user']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("email_user");?> <?php echo $tNGs->displayFieldError("smtsettings", "email_user"); ?> <p class="help-block">Mail Username</p></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="email_port">Email Port:</label></td>
      <td height="45"><input class="form-control" type="text" name="email_port" id="email_port" value="<?php echo KT_escapeAttribute($row_rssmtsettings['email_port']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("email_port");?> <?php echo $tNGs->displayFieldError("smtsettings", "email_port"); ?><p class="help-block">smt &amp; pop3 hangisini girdiyseniz onun port adresi SSL veya default port number</p></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="email_password">Email Password:</label></td>
      <td height="45"><input class="form-control" type="password" name="email_password" id="email_password" value="<?php echo KT_escapeAttribute($row_rssmtsettings['email_password']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("email_password");?> <?php echo $tNGs->displayFieldError("smtsettings", "email_password"); ?><p class="help-block">Parolanız</p></td>
    </tr>
    <tr>
      <td height="45" class="KT_th"><label for="email_defaultFrom">Email Default From:</label></td>
      <td height="45"><input class="form-control" type="text" name="email_defaultFrom" id="email_defaultFrom" value="<?php echo KT_escapeAttribute($row_rssmtsettings['email_defaultFrom']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("email_defaultFrom");?> <?php echo $tNGs->displayFieldError("smtsettings", "email_defaultFrom"); ?> <p class="help-block">Default E-Posta Adresi</p></td>
    </tr>
    <tr class="KT_buttons">
      <td height="45">&nbsp;</td>
      <td height="45"><input class="btn btn-primary" type="submit" name="KT_Update1" id="KT_Update1" value="KAYDET" /></td>
    </tr>
  </table>
</form>
</div>
</div>
</body>
</html>