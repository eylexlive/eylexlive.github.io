<?php 
  defined("ADMIN") ? null: die('Hacking?');
  ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
?>
<div class="block block-bordered">
  <div class="block-header bg-gray-lighter">
    <h3 class="block-title">Kredi Gönder</h3>
  </div>
  <div class="block-content">
    <?php 
    
    if ($_POST) {
      $uye_kadi 	= post("uye_kadi");
      $kredi_tip 			= post("kredi_tip");
      $kredi_miktar 	= post("kredi_miktar");
      
      if (!$uye_kadi || !$kredi_tip || !$kredi_miktar) {
        echo alert("Boş alan bırakmayınız!","danger");
      }else{
        $varmi = query("SELECT * FROM uyeler WHERE uye_kadi = '$uye_kadi'");
        if(rows($varmi) < 1){
          echo alert("Böyle bir üye bulunamadı.","danger");
        }else{
          $row = row($varmi);
          $insert = query("INSERT INTO krediler SET
          kredi_ekleyen = '".$row["uye_id"]."',
          kredi_miktar = '$kredi_miktar',
          odeme_slug = '$kredi_tip',
          kredi_hesap = '-'");
          
          $yeniKredi = $row['uye_kredi']+$kredi_miktar;
          $update = query("UPDATE uyeler SET uye_kredi = '$yeniKredi' WHERE uye_kadi = '$uye_kadi'");
          if ($insert AND $update) {
            echo alert("Kredi gönderme işlemi başarılı.","success");
          }else{
            echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
          }
        }
      }
    }
    
    ?>
    <form action="" method="post">
      <div class="form-group">
        <label for="uye_kadi">Üye Kullanıcı Adı:</label>
        <input type="text" class="form-control" id="uye_kadi" name="uye_kadi" value="<?=isset($uye_kadi)?$uye_kadi:null;?>">
      </div>
    
      <div class="form-group">
        <label for="kredi_miktar">Gönderilecek Kredi:</label>
        <input type="number" class="form-control" id="kredi_miktar" name="kredi_miktar" value="0">
      </div>
      
      <div class="form-group">
        <label for="kredi_tip">Gönderilecek Method:</label>
        <select class="form-control" name="kredi_tip" id="kredi_tip">
          <?php $s = query("SELECT * FROM odeme WHERE odeme_durum='1'"); ?>
          <?php while($row = row($s)): ?>
            <option value="<?=$row["odeme_slug"]?>"><?=$row["odeme_adi"]?></option>
          <?php endwhile; ?>
        </select>
      </div>
      
      <div class="form-group">
        <button type="submit" name="gonder" value="true" class="btn btn-block btn-primary">Kredi Gönder</button>
      </div>
    </form>
  </div>
</div> 