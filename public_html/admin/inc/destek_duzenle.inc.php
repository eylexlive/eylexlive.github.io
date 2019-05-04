<?php
  defined("ADMIN") ? null: die('Hacking?');
  ($user["uye_rutbe"] > 0) ? null: go(ADMIN_URL);
?>
<?php
	$tid = get("tid");
	$varmi = query("SELECT * FROM ticketler INNER JOIN uyeler ON uyeler.uye_id = ticketler.ticket_atan_id INNER JOIN kategoriler_ticket ON kategoriler_ticket.kategori_id = ticketler.ticket_kategori WHERE ticket_id = '$tid' and ticket_ana_id = '$tid'");
	if(rows($varmi) < 1){
		go(URL."/admin/index.php?go=ticketler");
		exit;
	}else{
		$ticRow = row($varmi);
		$uid = $ticRow["ticket_atan_id"];
	}
	if($_POST && $ticRow["ticket_turu"] != 4){
		$ticket_baslik = post("ticket_baslik");
		$ticket_kategori = $ticRow["ticket_kategori"];
		$ticket_id = $ticRow["ticket_id"];
		$ticket_icerik = post("ticket_icerik");
		if(!$ticket_icerik){
			echo alert('Lütfen boş alan bırakmayınız.');
		}else{
			$userid = session("uye_id");
			$insert = query("INSERT INTO ticketler SET
			ticket_baslik = '$ticket_baslik',
			ticket_atan_id = '$userid',
			ticket_kategori = '$ticket_kategori',
			ticket_icerik = '$ticket_icerik',
			ticket_turu = 2,
			ticket_durum = 0,
			ticket_ana_id = '$ticket_id'");
			if($insert){
				$update = query("UPDATE ticketler SET ticket_turu = 2,ticket_baslik = '$ticket_baslik' WHERE ticket_id = '$tid'");
        $update_a = query("UPDATE ticketler SET ticket_baslik = '$ticket_baslik' WHERE ticket_ana_id = '$tid'");
				if($update){
					echo alert('Destek Talebine başarıyla cevap verilmiştir. Yönlendiriliyorsunuz.',"success");
          $sayfa = isset($_GET["sayfa"])?get("sayfa"):"1";
          $tur = isset($_GET["tur"])?get("tur"):"1";
					go(ADMIN_URL."/?go=destekler&sayfa=$sayfa&tur=$tur",2);
				}else{
          echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
				}
			}else{
        echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
			}
		}
	}
	if($ticRow["ticket_turu"] != 4){
		$atan_kisi = row(query("SELECT * FROM uyeler WHERE uye_id='$uid'"));
?>
<div class="block block-bordered">
  <div class="block-content">
    <form action="" method="post">
      <div class="form-group">
        <label for="">Destek Başlığı:</label>
        <input type="text" value="<?php echo ss($ticRow["ticket_baslik"]); ?>" name="ticket_baslik" class="form-control">
        <span class="help-block">Başlığı değiştirmeniz Talebi Doğrudan etkiler.</span>
      </div>
      <div class="row">
        <div class="col-md-7">
          <div class="form-group">
            <label for="">Destek Cevap:</label>
            <textarea name="ticket_icerik" id="ticket_icerik" cols="30" rows="10" class="form-control">Merhaba <?=$atan_kisi["uye_kadi"]?>,



İyi oyunlar dileriz.
Saygılarımızla, 
<?=mset("site_adi")?> Destek Ekibi</textarea>
            <div class="help-text">Ticketinize yeni bir şey ekleyebilirsiniz.</div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <label for="">Hızlı Cevap Seçimi:</label>
            <select size="10" style="height: 214px;" class="form-control" id="h-cevap" onchange="yazdir();">
              <?php
              $kquery = query("SELECT * FROM ticket_cevap");
              if(rows($kquery) < 1){
                  echo '<option disabled value="false">Hazır Cevap oluşturmamışsınız.</option>';
              }else{
                  while($krow = row($kquery)){
                      echo '<option value="'.$krow['cevap_yazi'].'">'.ss($krow["cevap_adi"]).'</option>';
                  }
              }
              ?>
            </select>
            <div class="help-text">Seçtikten sonra sol kısımda düzenleyebilirsiniz.</div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <button class="btn btn-success btn-block" type="submit">Cevapla</button>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
	function yazdir() {
		var e = document.getElementById("h-cevap");
		var strUser = e.options[e.selectedIndex].value;
		
		var ticket_icerik = $("#ticket_icerik").val();
		
		var r = confirm("Bu işlemi onaylaman Destek Cevap içeriğinin tamamını değiştirecekir.");
		if (r == true) {
			$("#ticket_icerik").val("Merhaba <?=$atan_kisi["uye_kadi"]?>,\n\n"+strUser+"\n\nİyi oyunlar dileriz.\nSaygılarımızla,\n<?=mset("site_adi")?> Destek Ekibi");
		}
	}
</script>

<?php } ?>
<style media="screen">
	#icerik .panel:last-child {
		margin-bottom: 0px;
	}
</style>
<div class="block block-bordered block-themed">
  <div class="block-header bg-primary-dark">
      <ul class="block-options">
          <span class="label label-primary"><?=$ticRow["kategori_adi"]?></span>
      </ul>
      <h3 class="block-title"><?php echo ss($ticRow["ticket_baslik"]); ?></h3>
  </div>

		<?php
			$varmi2 = query("SELECT * FROM ticketler INNER JOIN uyeler ON uyeler.uye_id = ticketler.ticket_atan_id INNER JOIN kategoriler_ticket ON kategoriler_ticket.kategori_id = ticketler.ticket_kategori WHERE ticket_ana_id = '$tid' ORDER BY ticket_id DESC");
			while($ticRow = row($varmi2)){
		?>
    <div class="block-content block-content-full block-content-mini bg-gray-light">
      <span class="text-muted pull-right"><em><?=tarih_t($ticRow["ticket_tarih"],"d m Y H:i")?></em></span>
      <a href="<?=ADMIN_URL?>/?go=profil&uid=<?=$ticRow["uye_id"]?>"><?=$ticRow["uye_kadi"]?></a> 
      - <span class="<?=($ticRow["uye_rutbe"]!=0)?'font-w600':null?>">
        <?=($ticRow["uye_rutbe"]=="1")?'Yönetici':null;?>
        <?=($ticRow["uye_rutbe"]=="2")?'Moderatör':null;?>
        <?=($ticRow["uye_rutbe"]=="3")?'Ticket Yetkilisi':null;?>
        <?=($ticRow["uye_rutbe"]<="0")?'Üye':null;?>
      </span>
    </div>
    <div class="block-content">
      <p><?php echo nl2br(ss($ticRow["ticket_icerik"])); ?></p>
    </div>
    <?php if ($ticRow["ticket_resim"]): ?>
    <div class="block-content bg-gray-lighter">
      <p><a href="<?=$ticRow["ticket_resim"]?>" target="_blank" style="color: #5f5f5f;"><i style="margin-right: 6px;" class="fa fa-file-image-o"></i>Resim Eki [Görüntülemek İçin Tıklayın]</a></p>
    </div>
    <?php endif; ?>
		<?php } ?>

</div>