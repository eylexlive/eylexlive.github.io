<?php
  defined("ADMIN") ? null: die('Hacking?');
  (($user["uye_rutbe"] == 1) OR ($user["uye_rutbe"] == 2)) ? null: go(ADMIN_URL);
    
	if(isset($_GET['sil'])){
		$sil = get('sil');
		$varmi = query("SELECT * FROM uyeler WHERE uye_id = '$sil'");
		if(rows($varmi) < 1){
			go(ADMIN_URL.'/?go=uyeler');
			exit;
		}else{
      $delete = query("DELETE FROM uyeler WHERE uye_id = '$sil'");
			if($delete){
				echo alert('Üye başarıyla silindi. Yönlendiriliyorsunuz.','success');
				go(ADMIN_URL.'/?go=uyeler',1);
			}else{
				echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
			}
		}
	}
  if(isset($_GET['kilit'])){
		$kilit = get('kilit');
		$varmi = query("SELECT * FROM uyeler WHERE uye_id = '$kilit' AND uye_oauth_uid != '0' ");
		if(rows($varmi) < 1){
			go(ADMIN_URL.'/?go=uyeler');
			exit;
		}else{
      $delete = query("UPDATE uyeler SET uye_oauth_uid='0' WHERE uye_id = '$kilit'");
			if($delete){
				echo alert('2 Adımda Doğrulama başarıyla kapatıldı. Yönlendiriliyorsunuz.','success');
				go(ADMIN_URL.'/?go=uyeler',1);
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
          <th class="text-center">#</th>
          <th>Kullanıcı Adı</th>
          <th class="text-center">Email</th>
          <th class="text-center">Kredi</th>
          <th class="text-center">Rütbe</th>
          <?php if ($user["uye_rutbe"] == 1): ?><th class="text-center"></th><?php endif; ?>
          <th class="text-center"></th>
        </tr>
      </thead>
      <tbody>
      <?php
        $link = "uyeler";
        @$sayfa = get("sayfa") ? get("sayfa") : 1;
        $ksayisi = rows(query("SELECT * FROM uyeler"));
        $limit = 20;
        $ssayisi = ceil($ksayisi/$limit);
        $baslangic = $sayfa * $limit - $limit;
        if($ksayisi > $limit){
          $query = query("SELECT * FROM uyeler ORDER BY uye_id DESC LIMIT $baslangic,$limit");
        }else{
          $query = query("SELECT * FROM uyeler ORDER BY uye_id DESC");
        }
        if(rows($query) < 1){
          echo '<tr><td class="text-center" colspan="6">Henüz hiç Kayıtlı Üye yok.</td></tr>';
        }else{
          while($uRow = row($query)){
      ?>
        <tr>
          <td class="text-center"><?php echo $uRow["uye_id"]; ?></td>
          <td><a href="<?=ADMIN_URL?>/?go=uye_duzenle&uid=<?=$uRow["uye_id"]?>"><?=ss($uRow["uye_kadi"])?></a> <i class="fa fa-user fa-fw" title="Detay" data-toggle="popover" data-trigger="hover" data-html="true" data-content="Kayıt Tarihi: <?php echo date("d/m/Y",$uRow["uye_kayit_tarih"]); ?><br>Kayıt IP: <?php echo $uRow["uye_kayit_ip"]; ?>"></i></td>
          <td class="text-center"><?php echo $uRow["uye_email"]; ?></td>
          <td class="text-center"><?php echo $uRow["uye_kredi"]; ?></td>
          <td class="text-center">
            <?php if ($uRow["uye_rutbe"] == "1"): ?>
              <span class="label label-danger">Yönetici</span>
            <?php elseif ($uRow["uye_rutbe"] == "2"): ?>
              <span class="label label-warning">Moderatör</span>
            <?php elseif ($uRow["uye_rutbe"] == "3"): ?>
              <span class="label label-info">Ticket Yetkilisi</span>
            <?php else: ?>
              <span class="label label-default">Üye</span>
            <?php endif; ?>
          </td>
          <?php if ($user["uye_rutbe"] == 1): ?>
            <td style="width:1px" class="text-center">
              <?php if ($uRow["uye_oauth_uid"] != "0"): ?>
                <a href="<?=ADMIN_URL?>/?go=uyeler&kilit=<?=$uRow['uye_id']?>" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="left" title="İki Adımda Doğrulamayı Kapat"><i class="fa fa-unlock"></i></a>
              <?php else: ?>
                <a class="btn btn-danger btn-xs" disabled><i class="fa fa-unlock"></i></a>
              <?php endif; ?>
            </td>
          <?php endif; ?>
          <td style="width:110px" class="text-center">
            <a href="<?=ADMIN_URL?>/?go=profil&uid=<?=$uRow['uye_id']?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
            <?php if ($user["uye_rutbe"] == 1): ?>
              <a href="<?=ADMIN_URL?>/?go=uye_duzenle&uid=<?=$uRow['uye_id']?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
              <a onclick="SwSil('uyeler','<?=$uRow["uye_id"]?>','sil',$(this));" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
            <?php endif; ?>
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