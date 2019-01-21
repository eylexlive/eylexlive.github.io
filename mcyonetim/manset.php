<?php require_once('../Connections/baglanti.php'); 

// Load the common classes
require_once('../includes/common/KT_common.php');


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
$formValidation->addField("sira", true, "numeric", "", "", "", "Zorunlu Alan... (Sadece Numeriz sayı giriniz)");
$formValidation->addField("resim", true, "", "", "", "", "Zorunlu Alan...");
$formValidation->addField("resimadi", true, "text", "", "", "", "Zorunlu Alan...");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_manset = new tNG_insert($conn_baglanti);
$tNGs->addTransaction($ins_manset);
// Register triggers
$ins_manset->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_manset->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_manset->registerTrigger("END", "Trigger_Default_Redirect", 99, "index.php?mc=manset");
$ins_manset->registerTrigger("AFTER", "Trigger_ImageUpload", 97);
// Add columns
$ins_manset->setTable("manset");
$ins_manset->addColumn("sira", "NUMERIC_TYPE", "POST", "sira");
$ins_manset->addColumn("resim", "FILE_TYPE", "FILES", "resim");
$ins_manset->addColumn("resimadi", "STRING_TYPE", "POST", "resimadi");
$ins_manset->addColumn("active", "STRING_TYPE", "POST", "active");
$ins_manset->setPrimaryKey("id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsmanset = $tNGs->getRecordset("manset");
$row_rsmanset = mysql_fetch_assoc($rsmanset);
$totalRows_rsmanset = mysql_num_rows($rsmanset);
?>
<?php
// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');
?>
<?php
// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);
?>
<?php
//start Trigger_ImageUpload trigger
//remove this line if you want to edit the code by hand 
function Trigger_ImageUpload(&$tNG) {
  $uploadObj = new tNG_ImageUpload($tNG);
  $uploadObj->setFormFieldName("resim");
  $uploadObj->setDbFieldName("resim");
  $uploadObj->setFolder("../upload/");
  $uploadObj->setMaxSize(2500);
  $uploadObj->setAllowedExtensions("gif, jpg, jpe, jpeg, png");
  $uploadObj->setRename("auto");
  return $uploadObj->Execute();
}
//end Trigger_ImageUpload trigger

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
$query_mansetlist = "SELECT * FROM manset ORDER BY sira ASC";
$mansetlist = mysql_query($query_mansetlist, $baglanti) or die(mysql_error());
$row_mansetlist = mysql_fetch_assoc($mansetlist);
$totalRows_mansetlist = mysql_num_rows($mansetlist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="../includes/common/js/base.js" type="text/javascript"></script>
<script src="../includes/common/js/utility.js" type="text/javascript"></script>
<script src="../includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
<script src="js/jquery.colorbox.js" type="text/javascript"></script>
<script>
			$(document).ready(function(){
				//Examples of how to assign the Colorbox event to elements
				$(".group1").colorbox({rel:'group1'});
				$(".group2").colorbox({rel:'group2', transition:"fade"});
				$(".group3").colorbox({rel:'group3', transition:"none", width:"75%", height:"75%"});
				$(".group4").colorbox({rel:'group4', slideshow:true});
				$(".ajax").colorbox();
				$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
				$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
				$(".inline").colorbox({inline:true, width:"50%"});
				$(".callbacks").colorbox({
					onOpen:function(){ alert('onOpen: colorbox is about to open'); },
					onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
					onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
					onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
					onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
				});

				$('.non-retina').colorbox({rel:'group5', transition:'none'})
				$('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
				
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
		</script>
<link rel="stylesheet" href="css/colorbox.css" />

</head>
<body>
<ul class="breadcrumb">
  <li><a href="?mc=manset">Sayfayı Yenile</a></li>
  <li class="active">Anasayfa Manşet Ayarları</li>
</ul>

<div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-picture-o"></i> Yeni Manşet Ekle</h3>
  </div>
  <div class="panel-body">
    <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>" enctype="multipart/form-data">
      <table width="100%" cellpadding="2" cellspacing="0" class="table">
        <tr>
          <td width="16%" height="45" class="KT_th"><label for="sira">Manşet Sıra:</label></td>
          <td width="84%" height="45"><input style="text-align:center" type="text" name="sira" id="sira" value="1<?php echo KT_escapeAttribute($row_rsmanset['sira']); ?>" size="5" />
          <?php echo $tNGs->displayFieldHint("sira");?> <?php echo $tNGs->displayFieldError("manset", "sira"); ?>Sadece numeric sayılar giriniz aksi taktirde hatalar ile karşıla bilirsiniz...</td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="resim">Resim:</label></td>
          <td height="45"><input type="file" name="resim" id="resim" size="32" />
            <?php echo $tNGs->displayFieldError("manset", "resim"); ?>Resim Boyutu Genişlik; 800px Yükseklik; 250px boyutlarında olmalıdır.</td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="resimadi">Resim Adı:</label></td>
          <td height="45"><input type="text" name="resimadi" id="resimadi" value="<?php echo KT_escapeAttribute($row_rsmanset['resimadi']); ?>" size="32" />
          <?php echo $tNGs->displayFieldHint("resimadi");?> <?php echo $tNGs->displayFieldError("manset", "resimadi"); ?></td>
        </tr>
        <tr>
          <td height="45" class="KT_th"><label for="active">Yayınlansınmı?:</label></td>
          <td height="45"><select name="active" id="active">
            <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rsmanset['active'])))) {echo "SELECTED";} ?>>Evet</option>
            <option value="0" <?php if (!(strcmp(0, KT_escapeAttribute($row_rsmanset['active'])))) {echo "SELECTED";} ?>>Hayır</option>
          </select>
          <?php echo $tNGs->displayFieldError("manset", "active"); ?></td>
        </tr>
        <tr class="KT_buttons">
          <td height="45">&nbsp;</td>
          <td height="45"><input type="submit" class="btn btn-success btn-sm" name="KT_Insert1" id="KT_Insert1" value="KAYDET" />Eklediğiniz resimler Sıra numarasına göre sıralanır</td>
        </tr>
      </table>
    </form>
  </div>
</div>


<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-camera-retro"></i> Eklenen Manşet Resimleri</h3>
  </div>
  <div class="panel-body">
    <?php if ($totalRows_mansetlist == 0) { // Show if recordset empty ?>
  <center><h3>Ekli manşet bulunamadı...</h3></center>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_mansetlist > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" class="table">
    <tr>
      <th width="10%" height="35">SIRA</th>
      <th width="49%" height="35">RESİM ADI</th>
      <th width="18%" height="35">RESİM</th>
      <th width="14%" align="center">DURUM</th>
      <th width="9%" height="35">İŞLEM</th>
    </tr>
    <?php do { ?>
      <tr>
        <td><i class="fa fa-sort" style="cursor:pointer"></i> <?php echo $row_mansetlist['sira']; ?></td>
        <td><?php echo $row_mansetlist['resimadi']; ?> <small>(<i class="fa fa-file-image-o"></i> <?php echo $row_mansetlist['resim']; ?>)</small></td>
        <td><a class="group1 btn btn-primary btn-xs" title="<?php echo $row_mansetlist['resimadi']; ?>" href="../upload/<?php echo $row_mansetlist['resim']; ?>"><i class="fa fa-crosshairs"></i> RESMİ ÖNİZLE</a></td>
        <td align="center"><?php 

if ($row_mansetlist['active'] == '1') 
{ 
    echo ' <span class="btn btn-xs btn-success">AKTİF</span> '; 
} 
elseif ($row_mansetlist['active'] == '0') 
{ 
    echo '<span class="btn btn-xs btn-warning">PASİF</span>'; 
}
else 
{ 
    echo '<span class="btn btn-xs btn-danger">404 Hata</span>'; 
} 

?></td>
        <td align="center"><a onclick="if(deleteConfirm()) window.location='?mc=mansetdelete&amp;id=<?php echo $row_mansetlist['id']; ?>';" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> SİL</a></td>
      </tr>
      <?php } while ($row_mansetlist = mysql_fetch_assoc($mansetlist)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>
</div>


</body>
</html>
<?php
mysql_free_result($mansetlist);
?>
