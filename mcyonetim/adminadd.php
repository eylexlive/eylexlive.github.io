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

//start Trigger_CheckPasswords trigger
//remove this line if you want to edit the code by hand
function Trigger_CheckPasswords(&$tNG) {
  $myThrowError = new tNG_ThrowError($tNG);
  $myThrowError->setErrorMsg("Passwords do not match.");
  $myThrowError->setField("password");
  $myThrowError->setFieldErrorMsg("The two passwords do not match.");
  return $myThrowError->Execute();
}
//end Trigger_CheckPasswords trigger

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("username", true, "text", "", "", "", "Zorunlu Alan...");
$formValidation->addField("password", true, "text", "", "", "", "Zorunlu Alan...");
$formValidation->addField("email", true, "text", "email", "", "", "Zorunlu Alan...");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_authme = new tNG_insert($conn_baglanti);
$tNGs->addTransaction($ins_authme);
// Register triggers
$ins_authme->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_authme->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_authme->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=admin");
$ins_authme->registerConditionalTrigger("{POST.password} != {POST.re_password}", "BEFORE", "Trigger_CheckPasswords", 50);
// Add columns
$ins_authme->setTable("authme");
$ins_authme->addColumn("username", "STRING_TYPE", "POST", "username");
$ins_authme->addColumn("password", "STRING_TYPE", "POST", "password");
$ins_authme->addColumn("email", "STRING_TYPE", "POST", "email");
$ins_authme->addColumn("level", "STRING_TYPE", "POST", "level", "2");
$ins_authme->setPrimaryKey("id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsauthme = $tNGs->getRecordset("authme");
$row_rsauthme = mysql_fetch_assoc($rsauthme);
$totalRows_rsauthme = mysql_num_rows($rsauthme);
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
  <li><a href="index.php?mc=admin"><i class="fa fa-user-md"></i> Adminleri Listele</a></li>
  <li  class="active"><a href="?mc=adminadd"><i class="fa fa-user-plus"></i> Yeni Admin Ekle</a></li>
</ul>
<hr />
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-user-plus"></i> YÖNETİCİ EKLE</h3>
  </div>
  <div class="panel-body">
    <?php
	echo $tNGs->getErrorMsg();
?>
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <table width="100%" cellpadding="2" cellspacing="0" class="KT_tngtable">
        <tr>
          <td class="KT_th">&nbsp;</td>
          <td class="KT_th">(*) ile işaretli alanlara bilgi girişi zorunludur...</td>
        </tr>
        <tr>
          <td width="15%" height="45" class="KT_th"><label for="username">Kullanıcı Adı:</label></td>
          <td width="85%" height="45"><div class="input-group col-xs-5"><input class="form-control" type="text" name="username" id="username" value="<?php echo KT_escapeAttribute($row_rsauthme['username']); ?>" size="32" placeholder="Yönetici giriş adı" required /></div>
            <?php echo $tNGs->displayFieldHint("username");?> <?php echo $tNGs->displayFieldError("authme", "username"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="password">Şifre:</label></td>
          <td height="45"><div class="input-group col-xs-5"><input type="password" class="form-control" placeholder="Parola" name="password" id="password" value="" size="32" required /></div>
            <?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("authme", "password"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="re_password">Tekrar Şifre:</label></td>
          <td height="45"><div class="input-group col-xs-5"><input class="form-control" placeholder="Tekrar Parola" type="password" name="re_password" id="re_password" value="" size="32" required /></div></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="email">Email:</label></td>
          <td height="45"><div class="input-group col-xs-5"><input class="form-control" placeholder="E-Posta Adresi" type="text" name="email" id="email" value="<?php echo KT_escapeAttribute($row_rsauthme['email']); ?>" size="32" required />
            <?php echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("authme", "email"); ?></div></td>
        </tr>
        <tr class="KT_buttons">
          <td height="45">&nbsp;</td>
          <td height="45"><input class="btn active btn-info" title="Yöneticiyi Kaydet" type="submit" name="KT_Insert1" id="KT_Insert1" value="KAYDET" /> </td>
        </tr>
        <tr class="KT_buttons">
          <td height="45" colspan="2"></td>
        </tr>
      </table>
      <input type="hidden" name="level" id="level"  value="<?php echo KT_escapeAttribute($row_rsauthme['level']); ?>" />
    </form>
    <p>&nbsp;</p>
  </div>
</div>

</body>
</html>