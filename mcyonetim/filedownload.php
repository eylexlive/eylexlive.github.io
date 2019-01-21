<?php require_once('../Connections/baglanti.php'); ?>
<?php
// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_baglanti, "../");
//Grand Levels: Level
$restrict->addLevel("2");
$restrict->Execute();
//End Restrict Access To Page
?>
<ol class="breadcrumb">
  <li><a href="https://www.spigotmc.org/resources/categories/bukkit.4/" target="_blank">Plugin İndir</a></li>
  <li class="active">Dosya İndir</li>
</ol>


<?php
$oku = file_get_contents("http://getspigot.org");

$bol = explode('<center>', $oku);
$bol = explode('<div id="notad">', $bol[1]);
echo $bol[0];
?>
