<?php
  defined("ADMIN") ? null: die('Hacking?');
  ($user["uye_rutbe"] > 0) ? null: go(ADMIN_URL);
?>
<?php 
  $sayfa = isset($_GET["sayfa"])?get("sayfa"):"1";
  $tur = get("tur");
  switch ($tur) {
    case '1':
      $wh = null;
      $wl = "1";
      break;
    case '2':
      $wh = "ticket_turu = 1 AND";
      $wl = "2";
      break;
    case '3':
      $wh = "(ticket_turu = 1 or ticket_turu = 3) AND";
      $wl = "3";
      break;
    case '4':
      $wh = "ticket_turu = 2 AND";
      $wl = "4";
      break;
    case '5':
      $wh = "ticket_turu = 4 AND";
      $wl = "5";
      break;
    default:
      $wh = null;
      $wl = "1";
      break;
  }
?>
<div class="row">
  <div class="col-md-4">                
    <div class="block block-bordered">
        <div class="block-header">
            <h3 class="block-title">Destek Talebi Özet</h3>
        </div>
        <div class="block-content">
            <div class="pull-r-l pull-t push">
                <table class="block-table text-center bg-gray-lighter border-b">
                    <tbody>
                        <tr>
                            <td class="border-r" style="width: 50%;">
                                <div class="h1 font-w700"><?php echo rows(query("SELECT ticket_id FROM ticketler WHERE (ticket_turu = 1 or ticket_turu = 3) AND ticket_id = ticket_ana_id")); ?></div>
                                <div class="h5 text-muted text-uppercase push-5-t">Bekleyen Talep</div>
                            </td>
                            <td>
                                <div class="push-30 push-30-t">
                                    <i class="si si-envelope fa-3x text-black-op"></i>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="list-group">
              <a class="list-group-item <?=($wl == "1")?"active":null;?>" href="<?=ADMIN_URL?>/?go=destekler&sayfa=<?=$sayfa?>&tur=1">
                <span class="badge"><?php echo rows(query("SELECT ticket_id FROM ticketler WHERE ticket_id = ticket_ana_id")); ?></span>
                <i class="fa fa-fw fa-inbox push-5-r"></i> Tüm Talepler
              </a>
              <a class="list-group-item <?=($wl == "2")?"active":null;?>" href="<?=ADMIN_URL?>/?go=destekler&sayfa=<?=$sayfa?>&tur=2">
                <span class="badge"><?php echo rows(query("SELECT ticket_id FROM ticketler WHERE ticket_turu = 1 AND ticket_id = ticket_ana_id")); ?></span>
                <i class="fa fa-fw fa-send push-5-r"></i> Açık Talepler
              </a>
              <a class="list-group-item <?=($wl == "3")?"active":null;?>" href="<?=ADMIN_URL?>/?go=destekler&sayfa=<?=$sayfa?>&tur=3">
                <span class="badge"><?php echo rows(query("SELECT ticket_id FROM ticketler WHERE (ticket_turu = 1 OR ticket_turu = 3) AND ticket_id = ticket_ana_id")); ?></span>
                <i class="fa fa-fw fa-edit push-5-r"></i> Cevap Bekleyenler
              </a>
              <a class="list-group-item <?=($wl == "4")?"active":null;?>" href="<?=ADMIN_URL?>/?go=destekler&sayfa=<?=$sayfa?>&tur=4">
                <span class="badge"><?php echo rows(query("SELECT ticket_id FROM ticketler WHERE ticket_turu = 2 AND ticket_id = ticket_ana_id")); ?></span>
                <i class="fa fa-fw fa-archive push-5-r"></i> Cevaplananlar
              </a>
              <a class="list-group-item <?=($wl == "5")?"active":null;?>" href="<?=ADMIN_URL?>/?go=destekler&sayfa=<?=$sayfa?>&tur=5">
                <span class="badge"><?php echo rows(query("SELECT ticket_id FROM ticketler WHERE ticket_turu = 4 AND ticket_id = ticket_ana_id")); ?></span>
                <i class="fa fa-fw fa-trash push-5-r"></i> Kapatılanlar
              </a>
            </div>
        </div>
        <hr style="margin: 0;">
        <div class="block-content" style="padding: 20px">
          Sesli Bildirim:
          <label class="css-input switch switch-sm switch-primary" style="float:right;margin: 1px;">
            <input type="checkbox" id="sesli_bildirim"><span></span>
          </label>
        </div>
    </div>
  </div>
  <div class="col-md-8">
    <?php
    if(isset($_GET['uid'])){
        $sid = get('uid');
        $varmi = query("SELECT * FROM ticketler WHERE ticket_id = '$sid'");
        if(rows($varmi) < 1){
            go(ADMIN_URL."/?go=destekler&sayfa=$sayfa&tur=$wl");
            exit;
        }else{
            $delete = query("DELETE FROM ticketler WHERE ticket_id = '$sid'");
            $delete_k = query("DELETE FROM ticketler WHERE ticket_ana_id = '$sid'");
            if($delete AND $delete_k){
                echo alert('Destek Talebi başarıyla silindi. Yönlendiriliyorsunuz.','success');
                go(ADMIN_URL."/?go=destekler&sayfa=$sayfa&tur=$wl",1);
            }else{
                echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
            }
        }
    }
    if(isset($_GET['kapat'])){
        $uid = get('kapat');
        $varmi = query("SELECT * FROM ticketler WHERE ticket_id = '$uid'");
        if(rows($varmi) < 1){
            go(ADMIN_URL."/?go=destekler&sayfa=$sayfa&tur=$wl");
            exit;
        }else{
            $kapat = query("UPDATE ticketler SET ticket_turu='4' WHERE ticket_id = '$uid'");
            if($kapat){
                echo alert('Destek Talebi başarıyla kapatıldı. Yönlendiriliyorsunuz.','success');
                go(ADMIN_URL."/?go=destekler&sayfa=$sayfa&tur=$wl",1);
            }else{
                echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
            }
        }
    }
    ?>
    <div class="block block-bordered">
      <div class="block-header bg-gray-lighter" style="border-bottom: 0px;">
          <h3 class="block-title">Destek Talepleri</h3>
      </div>
      <div class="table-responsive">
        <table class="table table-hover table-vcenter" style="margin-bottom: 0px;">
          <tbody>
            <?php
            
              $link = "destekler";
              @$sayfa = get("sayfa") ? get("sayfa") : 1;
              $ksayisi = rows(query("SELECT * FROM ticketler WHERE $wh ticket_durum = 1"));
              $limit = 10;
              $ssayisi = ceil($ksayisi/$limit);
              $baslangic = $sayfa * $limit - $limit;
              if($ksayisi > $limit){
                $query = query("SELECT * FROM ticketler INNER JOIN uyeler ON uyeler.uye_id = ticketler.ticket_atan_id INNER JOIN kategoriler_ticket ON kategoriler_ticket.kategori_id = ticket_kategori WHERE $wh ticket_durum = 1 ORDER BY ticket_guncelleme DESC,ticket_id DESC LIMIT $baslangic,$limit");
              }else{
                $query = query("SELECT * FROM ticketler INNER JOIN uyeler ON uyeler.uye_id = ticketler.ticket_atan_id INNER JOIN kategoriler_ticket ON kategoriler_ticket.kategori_id = ticket_kategori WHERE $wh ticket_durum = 1 ORDER BY ticket_guncelleme DESC,ticket_id DESC");
              }
              if(rows($query) < 1){
                echo '<tr><td class="text-center" colspan="4">Destek talebi bulunamadı.</td></tr>';
              }else{
                while($row = row($query)){
            ?>
              <tr id="<?=$row["ticket_id"]?>">
                  <td style="width: 55%;">
                      <a class="font-w600" href="<?=ADMIN_URL?>/?go=destek_duzenle&sayfa=<?=$sayfa?>&tur=<?=$wl?>&tid=<?=$row["ticket_id"]?>"><?=$row["ticket_baslik"]?></a>
                      <div class="text-muted">
                          <em><?=tarih_t($row["ticket_tarih"],"d m Y")?></em> &nbsp;/&nbsp; <a href="<?=ADMIN_URL?>/?go=profil&uid=<?=$row["uye_id"]?>"><?=$row["uye_kadi"]?></a>
                      </div>
                  </td>
                  <td class="hidden-xs hidden-sm hidden-md text-muted">
                      <em><?=ss($row["kategori_adi"])?></em>
                  </td>
                  <td class="hidden-xs hidden-sm hidden-md text-center">
                    <?php echo $row["ticket_turu"] == 1 ? '<span class="label label-success label-mini">Açık</span>' : null; ?>
                    <?php echo $row["ticket_turu"] == 2 ? '<span class="label label-default label-mini">Cevaplandı</span>' : null; ?>
                    <?php echo $row["ticket_turu"] == 3 ? '<span class="label label-warning label-mini">Kullanıcı Yanıtı</span>' : null; ?>
                    <?php echo $row["ticket_turu"] == 4 ? '<span class="label label-danger label-mini">Kapandı</span>' : null; ?>
                  </td>
                  <td style="width:100px" class="text-center">
                    <?php if($row["ticket_turu"] != 4){ ?>
                      <a href="<?=ADMIN_URL?>/?go=destek_duzenle&sayfa=<?=$sayfa?>&tur=<?=$wl?>&tid=<?=$row["ticket_id"]?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                      <a href="<?=ADMIN_URL?>/?go=destekler&sayfa=<?=$sayfa?>&tur=<?=$wl?>&kapat=<?=$row["ticket_id"]?>" class="btn btn-warning btn-xs"><i class="fa fa-times"></i></a>
                    <?php }else{ ?>
                      <a href="<?=ADMIN_URL?>/?go=destek_duzenle&sayfa=<?=$sayfa?>&tur=<?=$wl?>&tid=<?=$row["ticket_id"]?>" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                    <?php } ?>
                      <a href="#" onclick="SwSil('destekler&sayfa=<?=$sayfa?>&tur=<?=$wl?>','<?=$row["ticket_id"]?>','uid',$(this));" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
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
  </div>
</div>
<audio id="bildirim" src="<?=ADMIN_URL?>/assets/notification.mp3"></audio>
<script type="text/javascript">
  var talep = 0;
  function functionName() {
    $.ajax({
      type: 'POST',
      url: './ajax/destek.ajax.php',
      dataType: "json",
      data: "",
      success: function(cevap) {
        if (cevap == "0") {
          document.title = "CraftWeb Minecraft Scripti v5 Admin Panel Sayfası";  
        } else {
          document.title = "("+cevap+") CraftWeb Minecraft Scripti v5 Admin Panel Sayfası";
        }
        if (talep < cevap) {
          if (talep != 0 || (talep == 0 && cevap > 0)) {
            if (localStorage.getItem("sesli_bildirim") == "true") {
              var vid = document.getElementById("bildirim"); 
              vid.play();
            }  
          }
        }

        talep = cevap;
      }
    });
  }

  setInterval(function() {
    functionName();
  },1000);

  $("#sesli_bildirim").change(function() {
    if (localStorage.getItem("sesli_bildirim") == "true") {
      localStorage.setItem('sesli_bildirim',"false");
    }else{
      localStorage.setItem('sesli_bildirim',"true");  
    }
  });
  if (localStorage.getItem("sesli_bildirim") == "true") {
    $("#sesli_bildirim").prop("checked",true);
  }
</script>