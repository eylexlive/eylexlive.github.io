<?php !session('login') ? go(URL."/kayitol") : null; ?>
<div class="panel panel-default">
  <div class="panel-heading">Destek Bildirimleri</div>
  <div class="panel-body">
		<?php
			$ub = query("SELECT * FROM ticket_engel WHERE uye_id='".session("uye_id")."'");
		?>
		<?php if (rows($ub) > 0): ?>
			<?php $ubr = row($ub); ?>
			<div class="alert alert-danger" style="margin-bottom: 0;">Destek Sistemiden engellendiniz!<br><b>Engellenme Nedeni:</b><br><?=nl2br($ubr["engel_neden"])?></div>
		<?php else: ?>
			<a href="<?php echo URL."/destek/yeni/"; ?>" class="btn btn-success btn-block btn-lg">Yeni Destek Bildirimi</a>
		<?php endif; ?>
  </div>
  <div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th>Ticket Başlığı</th>
					<th class="text-center">Açılma Tarihi</th>
					<th class="text-center">Durumu</th>
					<th class="text-center">İşlemler</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$link = "destek";
					@$sayfa = get("s")?get("s"):1;
					$ksayisi = rows(query("SELECT * FROM ticketler WHERE ticket_atan_id = '".session("uye_id")."' and ticket_durum = 1 and ticket_id = ticket_ana_id"));
					$limit = 20;
					$ssayisi = ceil($ksayisi/$limit);
					$baslangic = $sayfa * $limit - $limit;
					if($ksayisi > $limit){
						$query = query("SELECT * FROM ticketler INNER JOIN kategoriler_ticket ON kategoriler_ticket.kategori_id = ticketler.ticket_kategori WHERE ticket_atan_id = '".session('uye_id')."' and ticket_id = ticket_ana_id and ticket_durum = 1 ORDER BY ticket_id DESC LIMIT $baslangic,$limit");
					}else{
						$query = query("SELECT * FROM ticketler INNER JOIN kategoriler_ticket ON kategoriler_ticket.kategori_id = ticketler.ticket_kategori WHERE ticket_atan_id = '".session('uye_id')."' and ticket_id = ticket_ana_id and ticket_durum = 1 ORDER BY ticket_id DESC");
					}
					
					if(rows($query) < 1){
						echo '<tr><td class="text-center" colspan="5">Destek talebi bulunamadı.</td></tr>';
					}else{
						while($row = row($query)){
				?>
					<tr>
						<td class="text-center"><?php echo $row["ticket_id"]; ?></td>
						<td><a href="<?php echo URL.'/destek/duzenle/'.$row["ticket_id"]; ?>"><?php echo ss($row["ticket_baslik"]); ?></a></td>
						<td class="text-center"><?php echo tarih_t($row["ticket_tarih"],"d m Y h:i:s"); ?></td>
						<td class="text-center">
							<?php echo $row["ticket_turu"] == 1 ? '<span class="label label-success">Açık</span>' : null; ?>
							<?php echo $row["ticket_turu"] == 2 ? '<span class="label label-info">Yanıtlandı</span>' : null; ?>
							<?php echo $row["ticket_turu"] == 3 ? '<span class="label label-warning">Kullanıcı Yanıtı</span>' : null; ?>
							<?php echo $row["ticket_turu"] == 4 ? '<span class="label label-danger">Kapandı</span>' : null; ?>
						</td>
						<td class="text-center" style="width: 90px;">
							<?php if($row["ticket_turu"] == 4){ ?>
								<a href="<?php echo URL."/destek/duzenle/".$row["ticket_id"]; ?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
							<?php }else{ ?>
								<a href="<?php echo URL."/destek/duzenle/".$row["ticket_id"]; ?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
								<a href="<?php echo URL."/destek/kapat/".$row["ticket_id"]; ?>" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>
							<?php } ?>
						</td>
					</tr>
					<?php
						}
					}
				?>
			</tbody>
		</table>
  </div>
</div>
<?php if ($ksayisi > 0): ?>
		<nav class="text-center">
				<?php
				$sayfa_goster = 6;

				$en_az_orta = ceil($sayfa_goster/2);
				$en_fazla_orta = ($ssayisi+1) - $en_az_orta;

				$sayfa_orta = $sayfa;
				if($sayfa_orta < $en_az_orta) $sayfa_orta = $en_az_orta;
				if($sayfa_orta > $en_fazla_orta) $sayfa_orta = $en_fazla_orta;

				$sol_sayfalar = round($sayfa_orta - (($sayfa_goster-1) / 2));
				$sag_sayfalar = round((($sayfa_goster-1) / 2) + $sayfa_orta);

				if($sol_sayfalar < 1) $sol_sayfalar = 1;
				if($sag_sayfalar > $ssayisi) $sag_sayfalar = $ssayisi;
				?>
				<ul class="pagination">
						<?php if ($sayfa != 1): ?>
								<li><a href="<?=URL?>/<?=$link?>/s/1"><i class="fa fa-angle-double-left"></i></a></li>
						<?php else: ?>
								<li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-double-left"></i></a></li>
						<?php endif; ?>

						<?php if ($sayfa != 1): ?>
								<li><a href="<?=URL?>/<?=$link?>/s/<?=($sayfa-1)?>"><i class="fa fa-angle-left"></i></a></li>
						<?php else: ?>
								<li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-left"></i></a></li>
						<?php endif; ?>

						<?php
						for($s = $sol_sayfalar; $s <= $sag_sayfalar; $s++) {
								if($sayfa == $s) {
										echo '<li class="active"><a href="'.URL.'/'.$link.'/s/'.$s.'">'.$s.'</a></li>';
								} else {
										echo '<li><a href="'.URL.'/'.$link.'/s/'.$s.'">'.$s.'</a></li>';
								}
						}
						?>
						<?php if ($sayfa != $ssayisi): ?>
								<li><a href="<?=URL?>/<?=$link?>/s/<?=($sayfa+1)?>"><i class="fa fa-angle-right"></i></a></li>
						<?php else: ?>
								<li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-right"></i></a></li>
						<?php endif; ?>

						<?php if ($sayfa != $ssayisi): ?>
								<li><a href="<?=URL?>/<?=$link?>/s/<?=$ssayisi?>"><i class="fa fa-angle-double-right"></i></a></li>
						<?php else: ?>
								<li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-double-right"></i></a></li>
						<?php endif; ?>
				</ul>
		</nav>
<?php endif; ?>