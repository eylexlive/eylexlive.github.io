<?php
  defined("ADMIN") ? null: die('Hacking?');
  ($user["uye_rutbe"] > 0) ? null: go(ADMIN_URL);
    
	if(isset($_GET['sil'])){
		$sil = get('sil');
		$varmi = query("SELECT * FROM ticket_engel WHERE uye_id = '$sil'");
		if(rows($varmi) < 1){
			go(ADMIN_URL.'/?go=destek_engellenenler');
			exit;
		}else{
			$delete = query("DELETE FROM ticket_engel WHERE uye_id = '$sil'");
			if($delete){
				echo alert('Engellenen Üye başarıyla silindi. Yönlendiriliyorsunuz.','success');
				go(ADMIN_URL.'/?go=destek_engellenenler',1);
			}else{
				echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
			}
		}
	}
?>
<div class="block block-bordered">
  <div class="block-header bg-gray-lighter">Tüm Üyeler</div>
  <div class="table-responsive">
    <table class="table table-striped table-advance table-hover" style="margin-bottom: 0">
      <thead>
        <tr>
          <th>Kullanıcı Adı</th>
          <th>Engel Nedeni</th>
          <th class="text-center">Tarih</th>
          <th class="text-center"></th>
        </tr>
      </thead>
      <tbody>
      <?php
        $link = "destek_engellenenler";
        @$sayfa = get("sayfa") ? get("sayfa") : 1;
        $ksayisi = rows(query("SELECT * FROM ticket_engel"));
        $limit = 20;
        $ssayisi = ceil($ksayisi/$limit);
        $baslangic = $sayfa * $limit - $limit;
        if($ksayisi > $limit){
          $query = query("SELECT * FROM ticket_engel INNER JOIN uyeler ON uyeler.uye_id=ticket_engel.uye_id ORDER BY engel_id DESC LIMIT $baslangic,$limit");
        }else{
          $query = query("SELECT * FROM ticket_engel INNER JOIN uyeler ON uyeler.uye_id=ticket_engel.uye_id ORDER BY engel_id DESC");
        }
        if(rows($query) < 1){
          echo '<tr><td class="text-center" colspan="6">Henüz hiç engellenen Üye yok.</td></tr>';
        }else{
          while($uRow = row($query)){
      ?>
        <tr>
          <td><a href="<?=ADMIN_URL?>/?go=profil&uid=<?=$uRow["uye_id"]?>"><?=ss($uRow["uye_kadi"])?></a> (#<?=$uRow["uye_id"]?>)</td>
          <td><span title="Engellenme Nedeni Detayları" data-toggle="popover" data-trigger="hover" data-html="true" data-content="<?php echo $uRow["engel_neden"]; ?>"><?php echo substr($uRow["engel_neden"], 0, 30).'..'; ?></span></td>
          <td class="text-center"><?=date("d/m/Y H:i",$uRow["engel_tarih"])?></td>
          <td style="width:110px" class="text-center">
            <a onclick="SwSil('destek_engellenenler','<?=$uRow["uye_id"]?>','sil',$(this));" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
          </td>
        </tr>
      <?php } } ?>
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
                <li><a href="<?=ADMIN_URL?>/?go=<?=$link?>&sayfa=1"><i class="fa fa-angle-double-left"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-double-left"></i></a></li>
            <?php endif; ?>

            <?php if ($sayfa != 1): ?>
                <li><a href="<?=ADMIN_URL?>/?go=<?=$link?>&sayfa=<?=($sayfa-1)?>"><i class="fa fa-angle-left"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-left"></i></a></li>
            <?php endif; ?>

            <?php
            for($s = $sol_sayfalar; $s <= $sag_sayfalar; $s++) {
                if($sayfa == $s) {
                    echo '<li class="active"><a href="'.ADMIN_URL.'/?go='.$link.'&sayfa='.$s.'">'.$s.'</a></li>';
                } else {
                    echo '<li><a href="'.ADMIN_URL.'/?go='.$link.'&sayfa='.$s.'">'.$s.'</a></li>';
                }
            }
            ?>
            <?php if ($sayfa != $ssayisi): ?>
                <li><a href="<?=ADMIN_URL?>/?go=<?=$link?>&sayfa=<?=($sayfa+1)?>"><i class="fa fa-angle-right"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-right"></i></a></li>
            <?php endif; ?>

            <?php if ($sayfa != $ssayisi): ?>
                <li><a href="<?=ADMIN_URL?>/?go=<?=$link?>&sayfa=<?=$ssayisi?>"><i class="fa fa-angle-double-right"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-double-right"></i></a></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>