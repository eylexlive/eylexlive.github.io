<?php require_once('Connections/baglanti.php'); ?>
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
$query_newsrss = "SELECT * FROM news order by id desc limit 30";
$newsrss = mysql_query($query_newsrss, $baglanti) or die(mysql_error());
$row_newsrss = mysql_fetch_assoc($newsrss);
$totalRows_newsrss = mysql_num_rows($newsrss);

mysql_select_db($database_baglanti, $baglanti);
$query_ayar = "SELECT * FROM ayarlar WHERE id = 1";
$ayar = mysql_query($query_ayar, $baglanti) or die(mysql_error());
$row_ayar = mysql_fetch_assoc($ayar);
$totalRows_ayar = mysql_num_rows($ayar);


$xml = new DOMDocument('1.0', 'utf-8'); // Versiyon ve Karakter Kodlama


	for($i = 0 ; $i < 30 ; $i++){
$xml_item = $xml->createElement("item");
$xml_title = $xml->createElement("title");
$xml_description = $xml->createElement("description");
$xml_link = $xml->createElement("link");

$xml_title->nodeValue = $row_newsrss['title'];
$xml_description->nodeValue = "".$row_newsrss['subcontent']." - ".$row_newsrss['tarih']."";
$xml_link->nodeValue = "".$row_ayar['siteurl']."".permalink($row_newsrss['title'])."-h".$row_ayar['id'].".html";

$xml_item->appendChild( $xml_title );
$xml_item->appendChild( $xml_description );
$xml_item->appendChild( $xml_link );
}
$xml->appendChild( $xml_item );

$xml->save("rssatom.xml");


?>
<?php header('Location: rssatom.xml'); 
mysql_free_result($newsrss);

mysql_free_result($ayar);
?>
