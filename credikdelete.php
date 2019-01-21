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


// Make an instance of the transaction object
$del_credituser = new tNG_delete($conn_baglanti);
$tNGs->addTransaction($del_credituser);
// Register triggers
$del_credituser->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "token");
$del_credituser->registerTrigger("END", "Trigger_Default_Redirect", 99, "kredilerim#kredit");
// Add columns
$del_credituser->setTable("credituser");
$del_credituser->setPrimaryKey("token", "STRING_TYPE", "GET", "token");

// Execute all the registered transactions
$tNGs->executeTransactions();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
</head>

<body>
<?php
	echo $tNGs->getErrorMsg();
?>
</body>
</html>