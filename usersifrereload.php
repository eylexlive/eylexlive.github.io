<?php require_once('Connections/baglanti.php'); 
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_baglanti, "");
//Grand Levels: Level
$restrict->addLevel("1");
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
$formValidation->addField("password", true, "text", "", "", "", "Zorunlu Alan...");
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
$upd_authme->registerTrigger("END", "Trigger_Default_Redirect", 99, "ClientArea");
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
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
<!--
.panel .content table tr td .parola {
	margin: 10px;
}
-->
</style>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
</head>

<body>
<!-- Default -->
<div class="panel" >
    <div class="heading">
        <span class="title"><i class="fa fa-unlock-alt"></i> ŞİFRE AYARLARI</span></div>
    <div class="content">
      <table width="100%" border="0">
        <tr>
          <td height="45" bgcolor="#E9E9E9">&nbsp;&nbsp;<strong>&nbsp;Parola değişikliği</strong></td>
        </tr>
        <tr>
<td><div class="parola">

<form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
    <table cellpadding="2" cellspacing="0" class="KT_tngtable">
      <tr>
        <td class="KT_th"><label for="old_password">Eski Şifre:</label></td>
        <td><div class="input-control text error"><input type="password" name="old_password" id="old_password" value="" size="32" /></div>
          <?php echo $tNGs->displayFieldError("authme", "old_password"); ?></td>
        </tr>
      <tr>
        <td class="KT_th"><label for="password">Yeni Şifre:</label></td>
        <td><div class="input-control text success"><input type="password" name="password" id="password" value="" size="32" /></div>
          <?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("authme", "password"); ?></td>
        </tr>
      <tr>
        <td class="KT_th"><label for="re_password">Tekrar Şifre:</label></td>
        <td><div class="input-control text success"><input type="password" name="re_password" id="re_password" value="" size="32" /></div></td>
        </tr>
      <tr class="KT_buttons">
        <td><input type="button" value="&lt;&lt; GERİ" class="btn btn-large" onclick="window.location='ClientArea'" /></td>
        <td><input type="submit" class="button primary" name="KT_Update1" id="KT_Update1" value="GÜNCELLE" /></td>
        </tr>
      </table>
  </form>

</div>
  
  </td>
        </tr>
      </table>
</div></div>
</body>
</html>