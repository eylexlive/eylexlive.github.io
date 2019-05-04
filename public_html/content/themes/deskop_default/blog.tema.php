<?php
	@$link = get("link") ? get("link") : false;
	if(!$link){
		go(URL);
		exit;
	}
	$query = query("SELECT * FROM yazi INNER JOIN kategoriler_yazi ON kategoriler_yazi.kategori_id = yazi.yazi_kategori_id INNER JOIN uyeler ON uyeler.uye_id = yazi.yazi_olusturan_id WHERE yazi_link = '$link' AND yazi_durum = '1'");
	if(rows($query)){
		$row = row($query);
?>
<div class="panel panel-default">
	<div class="panel-heading"><?php echo ss($row["yazi_baslik"]); ?></div>
	<div class="panel-body">
		<?php echo nl2br($row["yazi_icerik"]); ?>
	</div>
	<div class="panel-footer text-center">
		Yazan: <?=$row["uye_kadi"]?> | Kategori: <?=ss($row["kategori_baslik"])?> | Tarih: <?php echo tarih_t($row["yazi_tarih"],"d m Y h:i"); ?><br>
		Etiketler: <?=$row["yazi_etiket"]?>
	</div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">Yorum Ekle</div>
  <div class="panel-body">
    <?php if (session("login") == true): ?>
			<form action="" method="post" onsubmit="return false;" id="yorum_gonder">
				<div class="form-group">
					<label for="yorum">Yorumunuz:</label>
					<textarea id="yorum" rows="3" class="form-control" style="resize: none;"></textarea>
				</div>
				<button type="submit" id="ekle" class="btn btn-block btn-success" onclick="yorum_gonder();">Yorum Yaz</button>
				<div id="cevap"></div>
			</form>
			<script type="text/javascript">
			function yorum_gonder() {
				var yorum = $("#yorum_gonder #yorum").val();
				var yazi = "<?=$row["yazi_id"]?>";
				if (yorum != "") {
					$("#yorum_gonder #ekle").html('<i class="fa fa-refresh fa-spin"></i>');
					$.ajax({
						type: 'POST',
						url: INC_PATH+'/ajax/yorum.php',
						dataType: "json",
						data: "yorum="+yorum+"&yazi="+yazi,
						success: function(cevap) {
							$("#yorum_gonder #ekle").html('Yorum Yaz');
							$("#yorum_gonder #cevap").html('<br><div style="margin-bottom: 0;" class="alert alert-'+cevap.class+'">'+cevap.mesaj+'</div>');
						}
					});
				}else{
					alert("Lütfen boş alan bırakmayınız.");
				}
			}
			</script>
    <?php else: ?>
			<?php echo alert("Yorum yapmak için giriş yapmanız gerekmektedir."); ?>
    <?php endif; ?>
  </div>
</div>
<div id="yorumlar">
	<?php
		$yazi_id = $row["yazi_id"];
		$liste = query("SELECT yorum_icerik,uye_kadi,yorum_tarih FROM yorumlar INNER JOIN uyeler ON uyeler.uye_id=yorumlar.yorum_gonderen WHERE yorum_yazi='$yazi_id' AND yorum_onay='1'");
		if (rows($liste) > 0) {
			while ($yRow = row($liste)) {
				echo '<div class="panel panel-default">';
				echo '<div class="panel-body">';
				echo $yRow["uye_kadi"];
				echo '<span class="pull-right">'.date("d-m-Y H:i",$yRow["yorum_tarih"]).'</span>';
				echo "<hr style='margin-top: 10px;margin-bottom: 10px;'>";
				echo $yRow["yorum_icerik"];
				echo "</div>";
				echo "</div>";
			}
		}else{
			echo alert("Bu yazıya hiç yorum yapılmamış. İlk yorum yapan sen ol!","warning text-center");
		}
	?>
</div>



<?php }else{
	go(URL);
} ?>