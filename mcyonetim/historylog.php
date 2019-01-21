<?php require_once('../Connections/baglanti.php'); ?>
<?php
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

$maxRows_log = 20;
$pageNum_log = 0;
if (isset($_GET['pageNum_log'])) {
  $pageNum_log = $_GET['pageNum_log'];
}
$startRow_log = $pageNum_log * $maxRows_log;

mysql_select_db($database_baglanti, $baglanti);
$query_log = "SELECT * FROM historylog ORDER BY lastlogindate DESC";
$query_limit_log = sprintf("%s LIMIT %d, %d", $query_log, $startRow_log, $maxRows_log);
$log = mysql_query($query_limit_log, $baglanti) or die(mysql_error());
$row_log = mysql_fetch_assoc($log);

if (isset($_GET['totalRows_log'])) {
  $totalRows_log = $_GET['totalRows_log'];
} else {
  $all_log = mysql_query($query_log);
  $totalRows_log = mysql_num_rows($all_log);
}
$totalPages_log = ceil($totalRows_log/$maxRows_log)-1;

$colname_user = "-1";
if (isset($_GET['users'])) {
  $colname_user = $_GET['users'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_user = sprintf("SELECT * FROM authme WHERE id = %s", GetSQLValueString($colname_user, "int"));
$user = mysql_query($query_user, $baglanti) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Add jQuery library -->
	<script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="js/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="js/jquery.fancybox.css?v=2.1.5" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="js/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="js/jquery.fancybox-buttons.js?v=1.0.5"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="js/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="js/jquery.fancybox-thumbs.js?v=1.0.7"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="js/jquery.fancybox-media.js?v=1.0.6"></script>
    <script type="text/javascript">
		$(document).ready(function() {
			/*
			 *  Simple image gallery. Uses default settings
			 */

			$('.fancybox').fancybox();

			/*
			 *  Different effects
			 */

			// Change title type, overlay closing speed
			$(".fancybox-effects-a").fancybox({
				helpers: {
					title : {
						type : 'outside'
					},
					overlay : {
						speedOut : 0
					}
				}
			});

			// Disable opening and closing animations, change title type
			$(".fancybox-effects-b").fancybox({
				openEffect  : 'none',
				closeEffect	: 'none',

				helpers : {
					title : {
						type : 'over'
					}
				}
			});

			// Set custom style, close if clicked, change title type and overlay color
			$(".fancybox-effects-c").fancybox({
				wrapCSS    : 'fancybox-custom',
				closeClick : true,

				openEffect : 'none',

				helpers : {
					title : {
						type : 'inside'
					},
					overlay : {
						css : {
							'background' : 'rgba(238,238,238,0.85)'
						}
					}
				}
			});

			// Remove padding, set opening and closing animations, close if clicked and disable overlay
			$(".fancybox-effects-d").fancybox({
				padding: 0,

				openEffect : 'elastic',
				openSpeed  : 150,

				closeEffect : 'elastic',
				closeSpeed  : 150,

				closeClick : true,

				helpers : {
					overlay : null
				}
			});

			/*
			 *  Button helper. Disable animations, hide close button, change title type and content
			 */

			$('.fancybox-buttons').fancybox({
				openEffect  : 'none',
				closeEffect : 'none',

				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,

				helpers : {
					title : {
						type : 'inside'
					},
					buttons	: {}
				},

				afterLoad : function() {
					this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
				}
			});


			/*
			 *  Thumbnail helper. Disable animations, hide close button, arrows and slide to next gallery item if clicked
			 */

			$('.fancybox-thumbs').fancybox({
				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,
				arrows    : false,
				nextClick : true,

				helpers : {
					thumbs : {
						width  : 50,
						height : 50
					}
				}
			});

			/*
			 *  Media helper. Group items, disable animations, hide arrows, enable media and button helpers.
			*/
			$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
					openEffect : 'none',
					closeEffect : 'none',
					prevEffect : 'none',
					nextEffect : 'none',

					arrows : false,
					helpers : {
						media : {},
						buttons : {}
					}
				});

			/*
			 *  Open manually
			 */

			$("#fancybox-manual-a").click(function() {
				$.fancybox.open('1_b.jpg');
			});

			$("#fancybox-manual-b").click(function() {
				$.fancybox.open({
					href : 'iframe.html',
					type : 'iframe',
					padding : 5
				});
			});

			$("#fancybox-manual-c").click(function() {
				$.fancybox.open([
					{
						href : '1_b.jpg',
						title : 'My title'
					}, {
						href : '2_b.jpg',
						title : '2nd title'
					}, {
						href : '3_b.jpg'
					}
				], {
					helpers : {
						thumbs : {
							width: 75,
							height: 50
						}
					}
				});
			});


		});
	</script>
</head>
<body>
<ul class="breadcrumb">
  <li class="active"><a href="index.php?mc=historylog">Siteye Giriş Çıkış Logları</a></li>
</ul>

<div class="panel panel-warning">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-clock-o"></i> Panel HISTORY LOG</h3>
  </div>
  <div class="panel-body">
    <?php if ($totalRows_log == 0) { // Show if recordset empty ?>
  <center>Harika hiç log bulunamadı <i class="fa fa-smile-o"></i></center>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_log > 0) { // Show if recordset not empty ?>
  <table width="100%" border="1" cellpadding="1" cellspacing="1" class="table">
    <tr>
      <th width="7%">U-ID</th>
      <th width="10%">&nbsp;</th>
      <th width="23%">IP ADRES</th>
      <th width="25%">SON GİRİŞ </th>
      <th colspan="2">SON ETKİNLİK </th>
    </tr>
    <?php do { ?>
      <tr>
        <td width="7%"><?php
//Show If User Is Logged In (region1)
$isLoggedIn = new tNG_UserLoggedIn($conn_baglanti);
//Grand Levels: Level
$isLoggedIn->addLevel("2");
if ($isLoggedIn->Execute()) {
?>
          <?php echo $row_log['users']; ?>
          <?php
}
//End Show If User Is Logged In (region1)
?> </td>
        <td align="center"><a  class="fancybox fancybox.ajax" href="usersdetay.php?id=<?php echo $row_log['users']; ?>">KİM BU?</a></td>
        <td><?php echo $row_log['ipdadres']; ?></td>
        <td><?php echo $row_log['lastlogindate']; ?></td>
        <td width="25%"><?php echo $row_log['lastactivitydate']; ?></td>
        <td width="10%" align="center"><a class="btn btn-danger btn-xs" href="index.php?mc=histordel&id=<?php echo $row_log['id']; ?>"><i class="fa fa-trash"></i> SİL</a></td>
      </tr>
      <?php } while ($row_log = mysql_fetch_assoc($log)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<?php
  echo $tNGs->getLoginMsg();
?>
  </div>
</div>


</body>
</html>
<?php
mysql_free_result($log);

mysql_free_result($user);
?>
