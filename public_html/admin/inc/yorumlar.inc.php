<?php
  defined("ADMIN") ? null: die('Hacking?');
  (($user["uye_rutbe"] == 1) OR ($user["uye_rutbe"] == 2)) ? null: go(ADMIN_URL);

	if(isset($_GET['sil'])){
		$sil = get('sil');
		$varmi = query("SELECT * FROM yorumlar WHERE yorum_id = '$sil'");
		if(rows($varmi) < 1){
			go(ADMIN_URL.'/?go=yorumlar');
			exit;
		}else{
			$delete = query("DELETE FROM yorumlar WHERE yorum_id = '$sil'");
			if($delete){
				echo alert('Yorum başarıyla silindi. Yönlendiriliyorsunuz.','success');
				go(ADMIN_URL.'/?go=yorumlar',1);
			}else{
				echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
			}
		}
  }
  if(isset($_GET['tur'])){
		$tur = get('tur');
		$uid = get('uid');
		$varmi = query("SELECT * FROM yorumlar WHERE yorum_id = '$uid'");
		if(rows($varmi) < 1){
			go(ADMIN_URL.'/?go=yorumlar');
			exit;
		}else{
      $tur = ($tur == "onayla")?"1":"0";
			$update = query("UPDATE yorumlar SET yorum_onay = '$tur'");
			if($update){
				echo alert('Yorum başarıyla güncellendi. Yönlendiriliyorsunuz.','success');
				go(ADMIN_URL.'/?go=yorumlar',1);
			}else{
				echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
			}
		}
	}
?>
<div class="block block-bordered">
  <div class="block-header bg-gray-lighter">
    <ul class="block-options">
      <span class="label label-default">Bekleyen Yorumlar: <?=rows(query("SELECT * FROM yorumlar WHERE yorum_onay=0"))?></span>
    </ul>
    <h3 class="block-title">Yorumlar</h3>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-advance table-hover" style="margin-bottom: 0">
      <thead>
        <tr>
          <th class="text-center">#</th>
          <th class="text-center">Gönderen</th>
          <th>Yazı</th>          
          <th class="text-center">Tarih</th>          
          <th class="text-center">Durum</th>          
          <th class="text-center">İşlemler</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $link = "yorumlar";
        @$sayfa = get("sayfa") ? get("sayfa") : 1;
        $ksayisi = rows(query("SELECT * FROM yorumlar"));
        $limit = 20;
        $ssayisi = ceil($ksayisi/$limit);
        $baslangic = $sayfa * $limit - $limit;
        if($ksayisi > $limit){
          $query = query("SELECT * FROM yorumlar INNER JOIN yazi ON yorumlar.yorum_yazi=yazi.yazi_id INNER JOIN uyeler ON yorumlar.yorum_gonderen=uyeler.uye_id ORDER BY yorum_id DESC LIMIT $baslangic,$limit");
        }else{
          $query = query("SELECT * FROM yorumlar INNER JOIN yazi ON yorumlar.yorum_yazi=yazi.yazi_id INNER JOIN uyeler ON yorumlar.yorum_gonderen=uyeler.uye_id ORDER BY yorum_id DESC");
        }
        if(rows($query) < 1){
          echo '<tr><td class="text-center" colspan="7">Henüz hiç Yorum yazılmamış.</td></tr>';
        }else{
          while($uRow = row($query)){
      ?>
        <tr>
          <td class="text-center"><?php echo $uRow["yorum_id"]; ?></td>
          <td class="text-center"><a href="<?=ADMIN_URL?>/?go=profil&uid=<?php echo $uRow["uye_id"]; ?>"><?php echo ss($uRow["uye_kadi"]); ?></a></td>
          <td><a href="<?=URL?>/blog/<?php echo $uRow["yazi_link"]; ?>"><?php echo ss($uRow["yazi_baslik"]); ?></a></td>
          <td class="text-center"><?php echo tarih_t(date("Y-m-d h:i:s",$uRow["yorum_tarih"]),"d m Y h:i"); ?></td>
          <td class="text-center">
            <?php if ($uRow["yorum_onay"] == "0"): ?>
              <span class="label label-danger">Onaylanmamış</span>
            <?php elseif($uRow["yorum_onay"] == "1"): ?>
              <span class="label label-success">Onaylanmış</span>
            <?php endif; ?>
          </td>
          <td style="width:110px" class="text-center">
            <a href="javascript:void(0)" data-toggle="popover" data-placement="left" data-content="<?=$uRow["yorum_icerik"]?>" data-original-title="Yorum İçeriği" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
            <?php if ($uRow["yorum_onay"] == "0"): ?>
            <a href="<?=ADMIN_URL?>/?go=yorumlar&uid=<?=$uRow['yorum_id']?>&tur=onayla" class="btn btn-success btn-xs"><i class="fa fa-check"></i></a>
            <?php elseif($uRow["yorum_onay"] == "1"): ?>
            <a href="<?=ADMIN_URL?>/?go=yorumlar&uid=<?=$uRow['yorum_id']?>&tur=onaylama" class="btn btn-warning btn-xs"><i class="fa fa-times"></i></a>
            <?php endif; ?>
            <a onclick="SwSil('yorumlar','<?=$uRow["yorum_id"]?>','sil',$(this));" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
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