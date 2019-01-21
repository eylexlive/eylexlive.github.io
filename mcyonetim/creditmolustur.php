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

	$musteriID = $_POST["musteriID"];
	$musteriadi = $_POST["musteriadi"];
	
		if(!$musteriID){
			echo '<div class="alert alert-dismissible alert-danger">
  <button type="button" class="close" data-dismiss="alert">X</button>
  <strong>HATA!</strong> <a href="#" class="alert-link">Bir hata oluştu bazı alanlar eksik.
</div><meta http-equiv="refresh" content="1;URL=index.php?mc=credit" />';
			}else {
				$kontrol = mysql_query("SELECT * FROM credit WHERE musteriadi='$musteriadi'");
				if(mysql_affected_rows()){
					echo '<div class="alert alert-dismissible alert-warning">
  <button type="button" class="close" data-dismiss="alert">X</button>
  <h4>HATA!</h4>
  <p> <strong>'.$musteriadi.'</strong> Bu hesap daha önce oluşturulmuştur</p>
</div><meta http-equiv="refresh" content="1;URL=index.php?mc=credit" />';
					}else{
						$insert = mysql_query("INSERT INTO credit SET 
										musteriID = '$musteriID',
										musteriadi = '$musteriadi'
										");
						
						if($insert){
							echo '<div class="alert alert-dismissible alert-success">
  <button type="button" class="close" data-dismiss="alert">X</button>
  <strong>BAŞARILI!</strong> Müşteri kredi heabı başarıyla eklenmiştir </div> <meta http-equiv="refresh" content="1;URL=index.php?mc=credit" />';
  							
							}else{
								echo "Bir hata oluştu ".mysql_Error()."";
								}
						
						}
					
					
				}
	
	

?>