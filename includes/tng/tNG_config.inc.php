<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_baglanti, $baglanti);
$query_smtp = "SELECT * FROM smtsettings WHERE id = 1";
$smtp = mysql_query($query_smtp, $baglanti) or die(mysql_error());
$row_smtp = mysql_fetch_assoc($smtp);
$totalRows_smtp = mysql_num_rows($smtp);
?>

<?php
$debug_email_to      = $row_smtp['debug_email_to'];
$debug_email_subject = $row_smtp['debug_email_subject'];
$debug_email_from    = $row_smtp['debug_email_from'];
$email_host          = $row_smtp['email_host'];
$email_user          = $row_smtp['email_user'];
$email_port          = $row_smtp['email_port'];
$email_password      = $row_smtp['email_password'];
$email_defaultFrom   = $row_smtp['email_defaultFrom'];

// Array definitions
  $tNG_login_config = array();
  $tNG_login_config_session = array();
  $tNG_login_config_redirect_success  = array();
  $tNG_login_config_redirect_failed  = array();
  $tNG_login_config_redirect_success = array();
  $tNG_login_config_redirect_failed = array();

// Start Variable definitions
  $tNG_debug_mode = "DEVELOPMENT";
  $tNG_debug_log_type = "";
  $tNG_debug_email_to = "$debug_email_to";
  $tNG_debug_email_subject = "$debug_email_subject";
  $tNG_debug_email_from = "$debug_email_from";
  $tNG_email_host = "$email_host";
  $tNG_email_user = "$email_user";
  $tNG_email_port = "$email_port";
  $tNG_email_password = "$email_password";
  $tNG_email_defaultFrom = "$email_defaultFrom";
  $tNG_login_config["connection"] = "baglanti";
  $tNG_login_config["table"] = "authme";
  $tNG_login_config["pk_field"] = "id";
  $tNG_login_config["pk_type"] = "NUMERIC_TYPE";
  $tNG_login_config["email_field"] = "email";
  $tNG_login_config["user_field"] = "username";
  $tNG_login_config["password_field"] = "password";
  $tNG_login_config["level_field"] = "level";
  $tNG_login_config["level_type"] = "STRING_TYPE";
  $tNG_login_config["randomkey_field"] = "";
  $tNG_login_config["activation_field"] = "";
  $tNG_login_config["password_encrypt"] = "true";
  $tNG_login_config["autologin_expires"] = "30";
  $tNG_login_config["redirect_failed"] = "error";
  $tNG_login_config["redirect_success"] = "ClientArea";
  $tNG_login_config["login_page"] = "login";
  $tNG_login_config["max_tries"] = "";
  $tNG_login_config["max_tries_field"] = "";
  $tNG_login_config["max_tries_disableinterval"] = "";
  $tNG_login_config["max_tries_disabledate_field"] = "";
  $tNG_login_config["registration_date_field"] = "";
  $tNG_login_config["expiration_interval_field"] = "";
  $tNG_login_config["expiration_interval_default"] = "";
  $tNG_login_config["logger_pk"] = "id";
  $tNG_login_config["logger_table"] = "historylog";
  $tNG_login_config["logger_user_id"] = "users";
  $tNG_login_config["logger_ip"] = "ipdadres";
  $tNG_login_config["logger_datein"] = "lastlogindate";
  $tNG_login_config["logger_datelastactivity"] = "lastactivitydate";
  $tNG_login_config["logger_session"] = "session";
  $tNG_login_config_redirect_success["1"] = "ClientArea";
  $tNG_login_config_redirect_failed["1"] = "";
  $tNG_login_config_redirect_success["2"] = "mcyonetim/index.php";
  $tNG_login_config_redirect_failed["2"] = "mcyonetim/login.php";
  $tNG_login_config_session["kt_login_id"] = "id";
  $tNG_login_config_session["kt_login_user"] = "username";
  $tNG_login_config_session["kt_login_level"] = "level";
  $tNG_login_config_session["kt_username"] = "username";
  $tNG_login_config_session["kt_ip"] = "ip";
  $tNG_login_config_session["kt_lastlogin"] = "lastlogin";
  $tNG_login_config_session["kt_x"] = "x";
  $tNG_login_config_session["kt_y"] = "y";
  $tNG_login_config_session["kt_z"] = "z";
  $tNG_login_config_session["kt_world"] = "world";
  $tNG_login_config_session["kt_isLogged"] = "isLogged";
  $tNG_login_config_session["kt_fimza"] = "fimza";
  $tNG_login_config_session["kt_email"] = "email";
// End Variable definitions
?>