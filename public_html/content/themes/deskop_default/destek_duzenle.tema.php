<?php
!session('login') ? go(URL."/kayitol") : null;
	if(isset($_GET["tid"])){
		$tid = get("tid");
		if(!$tid){
			go(URL."/destek");
			exit;
		}else{
			$varmi = query("SELECT * FROM ticketler WHERE ticket_id = '$tid' and ticket_durum = 1 and ticket_atan_id = '".session("uye_id")."'");
			if(rows($varmi) < 1){
				go(URL."/destek");
				exit;
			}else{
				$varmi = query("SELECT * FROM ticketler INNER JOIN uyeler ON uyeler.uye_id = '".session("uye_id")."' INNER JOIN kategoriler_ticket ON kategoriler_ticket.kategori_id = ticketler.ticket_kategori WHERE ticket_id = '$tid' and ticket_durum = 1 and ticket_atan_id = '".session("uye_id")."'");
				$ticRow = row($varmi);
			}
		}
	}
?>

<div class="panel panel-default">
  <div class="panel-heading"><?php echo ss($ticRow["ticket_baslik"])." [#".ss($ticRow["ticket_id"])."]"; ?> <i style="float: right"><?=$ticRow["kategori_adi"]?></i></div>
  <div class="panel-body">
		<?php
			$ub = query("SELECT * FROM ticket_engel WHERE uye_id='".session("uye_id")."'");
		?>
		<?php if (rows($ub) > 0): ?>
			<?php $ubr = row($ub); ?>
			<div class="alert alert-danger">Destek Sistemiden engellendiniz!<br><b>Engellenme Nedeni:</b><br><?=nl2br($ubr["engel_neden"])?></div>
		<?php else: ?>
			<?php
				if(isset($_POST["ticket_olustur"]) && $ticRow["ticket_turu"] != 4){
					$ticket_baslik = $ticRow["ticket_baslik"];
					$ticket_kategori = $ticRow["ticket_kategori"];
					$ticket_id = $ticRow["ticket_id"];
					$ticket_icerik = post("ticket_icerik");
					
					if(!$ticket_icerik){
						echo alert("Lütfen boş alan bırakmayınız!","danger");
					}else{
						$userid = session("uye_id");
						$insert = query("INSERT INTO ticketler SET
						ticket_baslik = '$ticket_baslik',
						ticket_atan_id = '$userid',
						ticket_kategori = '$ticket_kategori',
						ticket_icerik = '$ticket_icerik',
						ticket_turu = 3,
						ticket_durum = 0,
						ticket_ana_id = '$ticket_id'");
						if($insert){
							$update = query("UPDATE ticketler SET ticket_guncelleme = '".time()."',ticket_turu = 3 WHERE ticket_id = '$tid'");
							if($update){
								echo alert("Destek Talebi yanıtlama işleminiz başarılı. En kısa zaman içinde yanıtlanacaktır.",'success');
								go(ANLIK_URL);
							}else{
								echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
							}
						}else{
							echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
						}
					}
				}
			?>
		<?php if($ticRow["ticket_turu"] != 4){ ?>
			<script type="text/javascript">
				$(function(){
					$("#gizle-form").slideUp(0);
					
					$("#goster-form").on('click',function(e){
						e.preventDefault();
						$("#gizle-form").stop().slideToggle(800,function(){
							$("#goster-form").stop().toggleClass("btn-success");
						});
					});
				});
			</script>
			<div class="btn-group btn-group-justified">
				<a id="goster-form" href="#" class="btn btn-block btn-info">Tekrar / Cevap Yaz</a>
				<a href="<?php echo URL."/destek/kapat/".$ticRow["ticket_id"]; ?>" class="btn btn-danger" onclick="return confirm('Ticketi kapatmak istediğinize emin misiniz? Birdaha ekleme yapamazsınız.')">Destek Bildirimini Kapat</a>
				<a href="<?php echo URL."/destek"; ?>" class="btn btn-primary btn-block">Destek Bildirimleri</a>
			</div>
			<br>
			<div class="panel panel-default" id="gizle-form">
				<form action="" method="post">
					<div class="panel-body">
						<div class="alert alert-warning">Kanıtlayamadığınız şikayetler hakkında yada bizi ilgilendirmeyen durumlarda lütfen destek bildirimi oluşturmayınız</div>
						<div class="form-group">
							<label>İçerik:</label>
							<textarea name="ticket_icerik" id="" rows="6" class="form-control"></textarea>
							<p class="help-block">Her türlü sorununuzu yazabilirsiniz.</p>
						</div>
						<button name="ticket_olustur" value="ticket_olustur" type="submit" class="btn btn-success btn-block">Tekrar Yaz / Cevapla</button>
					</div>
				</form>
				</div>
				
			<?php }else{ ?>
				<div class="alert alert-warning"><strong>Bu ticket kapalı olduğu için sadece okuyabilirsiniz.</strong></div>
			<?php } ?>
		<?php endif; ?>

			<?php
				$varmi2 = query("SELECT * FROM ticketler INNER JOIN uyeler ON uyeler.uye_id = ticketler.ticket_atan_id INNER JOIN kategoriler_ticket ON kategoriler_ticket.kategori_id = ticketler.ticket_kategori WHERE ticket_ana_id = '$tid' ORDER BY ticket_id DESC");
				while($ticRow = row($varmi2)){
			?>
			<div class="panel panel-default">
			  <div class="panel-heading"><?php echo ss($ticRow["uye_kadi"]); ?>
					- <span style="<?=($ticRow["uye_rutbe"]!=0)?'font-weight: 600':null?>">
		        <?=($ticRow["uye_rutbe"]=="1")?'Yönetici':null;?>
		        <?=($ticRow["uye_rutbe"]=="2")?'Moderatör':null;?>
		        <?=($ticRow["uye_rutbe"]=="3")?'Ticket Yetkilisi':null;?>
		        <?=($ticRow["uye_rutbe"]<="0")?'Üye':null;?>
		      </span>
					<i style="float: right"><?php echo tarih($ticRow["ticket_tarih"]); ?></i>
				</div>
			  <div class="panel-body">
			    <?php echo nl2br($ticRow["ticket_icerik"]); ?>
					<?php if ($ticRow["ticket_resim"]): ?>
					<hr>
					<a href="<?=$ticRow["ticket_resim"]?>" target="_blank" style="color: #5f5f5f;"><i style="margin-right: 6px;" class="fa fa-file-image-o"></i>Resim Eki [Görüntülemek İçin Tıklayın]</a>
					<?php endif; ?>
			  </div>
			</div>
			<?php } ?>
		
  </div>
</div>