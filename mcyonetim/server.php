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
$formValidation->addField("sadi", true, "text", "", "", "", "Zorunlu Alan !");
$formValidation->addField("sip", true, "text", "", "", "", "Zorunlu Alan !");
$formValidation->addField("port", true, "text", "", "", "", "Zorunlu Alan !");
$formValidation->addField("soyun", true, "text", "", "", "", "Zorunlu Alan !");
$tNGs->prepareValidation($formValidation);
// End trigger

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
$query_serhead = "SELECT * FROM serverconfig WHERE id = 1";
$serhead = mysql_query($query_serhead, $baglanti) or die(mysql_error());
$row_serhead = mysql_fetch_assoc($serhead);
$totalRows_serhead = mysql_num_rows($serhead);

// Make an update transaction instance
$upd_serverconfig = new tNG_update($conn_baglanti);
$tNGs->addTransaction($upd_serverconfig);
// Register triggers
$upd_serverconfig->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$upd_serverconfig->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$upd_serverconfig->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=server");
// Add columns
$upd_serverconfig->setTable("serverconfig");
$upd_serverconfig->addColumn("sadi", "STRING_TYPE", "POST", "sadi");
$upd_serverconfig->addColumn("sip", "STRING_TYPE", "POST", "sip");
$upd_serverconfig->addColumn("port", "STRING_TYPE", "POST", "port");
$upd_serverconfig->addColumn("soyun", "STRING_TYPE", "POST", "soyun");
$upd_serverconfig->setPrimaryKey("id", "NUMERIC_TYPE", "VALUE", "1");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsserverconfig = $tNGs->getRecordset("serverconfig");
$row_rsserverconfig = mysql_fetch_assoc($rsserverconfig);
$totalRows_rsserverconfig = mysql_num_rows($rsserverconfig);
?>
<?php 
$APIkey = $row_serhead['sadi'];
$json = file_get_contents("http://minecraft-mp.com/api/?object=servers&element=detail&key=$APIkey");
$data = json_decode($json);
	
	$serveradi = $data->name;
	$bizeoyver = $data->id;
	$serverip = $data->address;
	$sport = $data->port;
	$ulke = $data->location;
	$durum = $data->is_online;
	$online = $data->players;
	$max = $data->maxplayers;
	$versiyon = $data->version;
	$oylar = $data->votes;
	$rank = $data->score;
	$up = $data->uptime;
	$link = $data->url;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
</head><body>
<div class="panel panel-default">
 <div class="panel-heading"><i class="fa fa-server"></i> SERVER AYARLARI</div>
  <div class="panel-body">
    <form class="form-horizontal" method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
      <table width="100%" cellpadding="2" cellspacing="0" class="table">
      
        <tr>
          <th width="15%" height="40" class="KT_th"><label for="sadi">APIKEY</label></th>
          <td width="85%" height="40"><div class="form-group has-success has-feedback col-xs-7">
          <input class="form-control" type="text" name="sadi" id="sadi" aria-describedby="inputSuccess3Status" value="<?php echo KT_escapeAttribute($row_rsserverconfig['sadi']); ?>" size="32" /><span class="help-block">APIKEY'i <a href="http://minecraft-mp.com/">http://minecraft-mp.com/</a> adresinden serverinizi ekleyerek ala bilirsiniz</span>
            <?php echo $tNGs->displayFieldHint("sadi");?> <?php echo $tNGs->displayFieldError("serverconfig", "sadi"); ?></div></td>
        </tr>
        <tr>
          <th height="40" class="KT_th"><label for="sip">Server IP</label></th>
          <td height="40"><div class="col-xs-4"><input class="form-control" type="text"  name="sip" id="sip" value="<?php echo KT_escapeAttribute($row_rsserverconfig['sip']); ?>" size="32" /></div>
            <?php echo $tNGs->displayFieldHint("sip");?> <?php echo $tNGs->displayFieldError("serverconfig", "sip"); ?></td>
        </tr>
        <tr>
          <th height="40" class="KT_th"><label for="port">Server Port</label></th>
          <td height="40">
          <div class="col-xs-2">
          <input class="form-control" type="text" style="text-align:center" name="port" id="port" value="<?php echo KT_escapeAttribute($row_rsserverconfig['port']); ?>" size="32" />
            <?php echo $tNGs->displayFieldHint("port");?> <?php echo $tNGs->displayFieldError("serverconfig", "port"); ?></div><span class="help-block">query.port server.properties ayarlarında <b>enable-query=true</b> olması gerikir</span></td>
        </tr>
        <tr>
          <th height="40" class="KT_th"><label for="soyun">Oyun Türleri:</label></th>
          <td height="40"><div class="col-xs-7"><input class="form-control" type="text" name="soyun" id="soyun" value="<?php echo KT_escapeAttribute($row_rsserverconfig['soyun']); ?>" style="font-size:12px" size="32" /></div><br /><span class="help-block">Oyun türlerini (,) virgül ile ayırınız.</span>
            <?php echo $tNGs->displayFieldHint("soyun");?> <?php echo $tNGs->displayFieldError("serverconfig", "soyun"); ?>
            Eklenen Oyun Türleri; <?php 
$parcala = explode(',', $row_serhead['soyun']); 
for($i=0; $i<count($parcala); $i++){ 
$kelime = $parcala[$i]; 
echo '<span class="label label-success">'.$kelime.'</span> '; 
} 
?>
            </td>
        </tr>
        <tr class="KT_buttons">
          <td height="40">&nbsp;</td>
          <td height="40"><input class="btn btn-primary" type="submit" name="KT_Update1" id="KT_Update1" value="KAYDET" /></td>
        </tr>
      </table>
</form>
  </div>
  <div class="panel-footer">Sunucu Ayarlarınız</div>
</div>






<div class="panel panel-success">
  <div class="panel-heading"><i class="fa fa-server"></i> SERVER DURUMU</div>
  <div class="panel-body">
  
  
  
  
    <table width="100%" border="0" class="table-bordered">
  <tr class="success">
  

    <td><i class="fa fa-play"></i> SUNUCU ADRESI</td>
  </tr>
  <tr>
    <td><img src="http://cdn.minecraft-mp.com/images/flags/Turkey.png" alt="Turkey" title="Turkey" border="0"/> <strong><?php echo $serverip; ?>:<?php echo $sport; ?></strong></td>
  </tr>
  <tr class="success">
    <td><i class="fa fa-group"></i> ONLINE PLAYER</td>
  </tr>
  <tr>
    <td>
   <center><button class="btn btn-small btn-success" type="button"><?php echo $online; ?></button> / <button class="btn btn-small btn-warning" type="button"><?php echo $max; ?></button></center>
    </td>
  </tr>
  <tr>
    <td>
    <table class="table table-bordered">
			<tbody>
				<tr>
					<td width="40%"><i class="fa fa-signal"></i> <strong>Uptime (<?php echo $up; ?>%)</strong></td>
					<td>
                    <div class="progress progress-striped">
  <div class="progress-bar progress-bar-info" style="width: <?php echo $up; ?>%"></div>
</div></td>
				</tr>
				<tr>
					<td><i class="fa fa-thumbs-o-up"></i> <strong>Vote(s)</strong></td>
					<td><?php echo $oylar; ?></td>
				</tr>
				<tr>
				  <td><i class="fa fa-cog"></i> <strong>Versiyon</strong></td>
				  <td><a class="label label-warning"><b><?php echo $versiyon; ?></b></a></td>
			  </tr>
				<tr>
				  <td><i class="fa fa-power-off"></i> <strong>Durum</strong></td>
				  <td>
                  <?php 

if ($durum == '1') 
{ 
    echo ' <span class="badge badge-success">Online</span> '; 
} 
elseif ($durum == '0') 
{ 
    echo '<span class="badge badge-important">Offline</span>'; 
} 
else 
{ 
    echo 'BOSTA'; 
} 

?>
                  </td>
			  </tr>
				
			</tbody>
	  </table>
    </td>
  </tr>
  
  <tr>
    <td></td>
  </tr>
  <tr class="success">
    <td>SERVERDE BULUNAN OYUN T&Uuml;RLERI</td>
  </tr>
  <tr>
    <td><?php 
$parcala = explode(',', $row_serhead['soyun']); 
for($i=0; $i<count($parcala); $i++){ 
$kelime = $parcala[$i]; 
echo '<span class="label label-info">'.$kelime.'</span> '; 
} 
?></td>
  </tr>
  
  
</table>

  </div>
</div>


</body>
</html>
<?php
mysql_free_result($serhead);
?>
