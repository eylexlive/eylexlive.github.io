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

<?php 
		$id = $_POST["id"];

		$update = mysql_query("UPDATE credituser SET eklendimi = '1' where id = '$id'");
		
		if($update){
			echo '<meta http-equiv="refresh" content="3;URL=index.php?mc=credit" />';
			
		}else{
			echo "Bir hata oluştu ".mysql_Error()."";
			}
		?>

		<?php 
		$musteriID= $_POST["musteriID"];
		$miktar = $_POST["miktar"];
		$madsoyad = $_POST["madsoyad"];

		$insert = mysql_query("UPDATE credit SET miktar=miktar+'$miktar' where musteriID='$musteriID'");
		
		if($insert){
			echo '<div class="alert alert-dismissible alert-success">
<button type="button" class="close" data-dismiss="alert">X</button>
<strong>BAŞARILI!</strong> <strong style="font-size:18px">'.$madsoyad.'</strong> İsimli Müşteri Hesabına <strong style="font-size:18px">'.$miktar.'.00 TL</strong> Kredi başarıyla eklenmiştir <br>Yönlendiriliyorsunuz lütfen bekleyiniz...</div> <meta http-equiv="refresh" content="4;URL=index.php?mc=credit" />';
	
			
		}else{
			echo "Bir hata oluştu ".mysql_Error()."";
			}
		?>
