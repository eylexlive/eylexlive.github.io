<?php
// Load the common classes
require_once('includes/common/KT_common.php');
?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');
?>
<?php
// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");
?>
<?php
// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);
?>

<?php
// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("email", true, "text", "email", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger
?>
<?php
//start Trigger_ForgotPasswordCheckEmail trigger
//remove this line if you want to edit the code by hand
function Trigger_ForgotPasswordCheckEmail(&$tNG) {
  return Trigger_ForgotPassword_CheckEmail($tNG);
}
//end Trigger_ForgotPasswordCheckEmail trigger
?>
<?php
//start Trigger_ForgotPassword_Email trigger
//remove this line if you want to edit the code by hand
function Trigger_ForgotPassword_Email(&$tNG) {
  $emailObj = new tNG_Email($tNG);
  $emailObj->setFrom("{KT_defaultSender}");
  $emailObj->setTo("{email}");
  $emailObj->setCC("");
  $emailObj->setBCC("");
  $emailObj->setSubject("Forgot password email");
  //FromFile method
  $emailObj->setContentFile("includes/mailtemplates/forgot.html");
  $emailObj->setEncoding("ISO-8859-1");
  $emailObj->setFormat("HTML/Text");
  $emailObj->setImportance("Normal");
  return $emailObj->Execute();
}
//end Trigger_ForgotPassword_Email trigger
?>
<?php
// Make an update transaction instance
$forgotpass_transaction = new tNG_update($conn_baglanti);
$tNGs->addTransaction($forgotpass_transaction);
// Register triggers
$forgotpass_transaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$forgotpass_transaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$forgotpass_transaction->registerTrigger("BEFORE", "Trigger_ForgotPasswordCheckEmail", 20);
$forgotpass_transaction->registerTrigger("AFTER", "Trigger_ForgotPassword_Email", 1);
$forgotpass_transaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "{kt_login_redirect}");
// Add columns
$forgotpass_transaction->setTable("authme");
$forgotpass_transaction->addColumn("email", "STRING_TYPE", "POST", "email");
$forgotpass_transaction->setPrimaryKey("email", "STRING_TYPE", "POST", "email");
?>
<?php
// Execute all the registered transactions
$tNGs->executeTransactions();
?>
<?php
// Get the transaction recordset
$rsauthme = $tNGs->getRecordset("authme");
$row_rsauthme = mysql_fetch_assoc($rsauthme);
$totalRows_rsauthme = mysql_num_rows($rsauthme);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>

<?php echo $tNGs->displayValidationRules();?>
</head>

<body>
	<form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
<table cellpadding="2" cellspacing="0" class="KT_tngtable">
			<tr>
	<td class="KT_th"><label for="email"> Email:</label></td>
	<td>
		<input type="text" name="email" id="email" value="<?php echo KT_escapeAttribute($row_rsauthme['email']); ?>" size="32" />
		<?php echo $tNGs->displayFieldHint("email");?>
		<?php echo $tNGs->displayFieldError("authme", "email"); ?>
	</td>
</tr>
			<tr class="KT_buttons"> 
				<td colspan="2">
					<input type="submit" name="KT_Update1" id="KT_Update1" value="G&Ouml;NDER" />
				</td>
			</tr>
			<tr class="KT_buttons">
			  <td colspan="2"><blockquote>
			    <p>Sisteme kay&#305;tl&#305; E-posta adresinizi girerrek yeni parolan&#305;z&#305; ala bilirsiniz. E-posta adresinize gelen mesaj spam klas&ouml;r&uuml;ne d&uuml;&#351;e bilir. Kontrol etmeyi unutmay&#305;n&#305;z.</p>
		      </blockquote></td>
    </tr>      
		</table>
		
</form>
	<p>&nbsp;</p>

</body>
</html>
