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

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("username", true, "text", "", "", "", "Zorunlu Alan...");
$formValidation->addField("email", true, "text", "email", "", "", "Zorunlu Alan...");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an update transaction instance
$upd_authme = new tNG_update($conn_baglanti);
$tNGs->addTransaction($upd_authme);
// Register triggers
$upd_authme->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_authme->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_authme->registerTrigger("END", "Trigger_Default_Redirect", 99, "settings");
// Add columns
$upd_authme->setTable("authme");
$upd_authme->addColumn("username", "STRING_TYPE", "POST", "username");
$upd_authme->addColumn("email", "STRING_TYPE", "POST", "email");
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
<style type="text/css">
<!--
.panel .content table tr td .userdetay {
	margin: 15px;
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
        <span class="title"><i class="fa fa-user"></i> PROFİL AYARLARI</span></div>
    <div class="content">
      <table width="100%" border="0">
        <tr>
          <td height="45" bgcolor="#E9E9E9"><strong>&nbsp;&nbsp;&nbsp;Profil ayarlarınızı yapacağınız alan.</strong></td>
        </tr>
        <tr>
          <td><div class="userdetay">
            <?php
	echo $tNGs->getErrorMsg();
?>
            <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
              <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                <tr>
                  <td height="35" class="KT_th"><label for="username">Kullanıcı Adı:</label></td>
                  <td height="35"><div class="input-control text"><input name="username" type="text" id="username" value="<?php echo KT_escapeAttribute($row_rsauthme['username']); ?>" size="15" readonly="readonly" /></div>
                    <?php echo $tNGs->displayFieldHint("username");?> <?php echo $tNGs->displayFieldError("authme", "username"); ?></td>
                </tr>
                <tr>
                  <td height="35" class="KT_th"><label for="email">E-Posta:</label></td>
                  <td height="35"><div class="input-control text"><input type="text" name="email" id="email" value="<?php echo KT_escapeAttribute($row_rsauthme['email']); ?>" size="32" /></div>
                  <?php echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("authme", "email"); ?></td>
                </tr>
                <tr class="KT_buttons">
                  <td height="35"><input type="button" value="&lt;&lt; GERİ" class="btn btn-large" onclick="window.location='ClientArea'" /></td>
                  <td height="35"><input class="button primary" type="submit" name="KT_Update1" id="KT_Update1" value="GÖNDER" /></td>
                </tr>
                
              </table>
            </form>
            <p>&nbsp;</p>
          </div></td>
        </tr>
      </table>
    </div></div>
</body>
</html>