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
  $myThrowError->setErrorMsg("Could not create account.");
  $myThrowError->setField("password");
  $myThrowError->setFieldErrorMsg("The two passwords do not match.");
  return $myThrowError->Execute();
}
//end Trigger_CheckPasswords trigger

// Start trigger
$formValidation = new tNG_FormValidation();
$tNGs->prepareValidation($formValidation);
// End trigger

//start Trigger_CheckOldPassword trigger
//remove this line if you want to edit the code by hand
function Trigger_CheckOldPassword(&$tNG) {
  return Trigger_UpdatePassword_CheckOldPassword($tNG);
}
//end Trigger_CheckOldPassword trigger

// Make an update transaction instance
$upd_authme = new tNG_update($conn_baglanti);
$tNGs->addTransaction($upd_authme);
// Register triggers
$upd_authme->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_authme->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_authme->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=passeditme");
$upd_authme->registerConditionalTrigger("{POST.password} != {POST.re_password}", "BEFORE", "Trigger_CheckPasswords", 50);
$upd_authme->registerTrigger("BEFORE", "Trigger_CheckOldPassword", 60);
// Add columns
$upd_authme->setTable("authme");
$upd_authme->addColumn("password", "STRING_TYPE", "POST", "password");
$upd_authme->setPrimaryKey("id", "NUMERIC_TYPE", "SESSION", "kt_login_id");

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
<ul class="breadcrumb">
  <li class="active">Parola Değiştir</li>
</ul>
<hr />
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">ŞİFRE DEĞİŞİKLİĞİ</h3>
  </div>
  <div class="panel-body">
  
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <table width="100%" cellpadding="2" cellspacing="0" class="KT_tngtable">
        <tr>
          <td width="15%" height="45" class="KT_th"><label for="old_password">Eski Şifre:</label></td>
          <td width="85%" height="45"><div class="input-group col-xs-4"><input class="form-control" type="password" name="old_password" id="old_password" value="" size="32" required /></div>
            <?php echo $tNGs->displayFieldError("authme", "old_password"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="password">Yeni Şifre:</label></td>
          <td height="45"><div class="input-group col-xs-4"><input class="form-control" type="password" name="password" id="password" value="" size="32" required /></div>
            <?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("authme", "password"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="re_password">Tekrar Şifre:</label></td>
          <td height="45"><div class="input-group col-xs-4"><input class="form-control" type="password" name="re_password" id="re_password" value="" size="32" required /></div></td>
        </tr>
        <tr class="KT_buttons">
          <td height="45">&nbsp;</td>
          <td height="45"><input type="submit" class="btn" name="KT_Update1" id="KT_Update1" value="GÜNCELLE" />
          Şifrenizi değiştirdikten sonra tekrar giriş yapınız.</td>
        </tr>
      </table>
    </form>
    <p>&nbsp;</p>
  </div>
</div>


</body>
</html>