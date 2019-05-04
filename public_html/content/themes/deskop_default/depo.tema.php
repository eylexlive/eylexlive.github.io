<?php !session('login') ? go(URL."/kayitol") : null; ?>
<div class="panel panel-default">
  <div class="panel-heading">Depo</div>
  <div class="table-responsive">
    <table class="table table-striped table-advance table-hover" style="margin-bottom: 0">
      <thead>
        <tr>
          <th class="text-center">#</th>
          <th>Ürün Adı</th>
          <th class="text-center">Süre</th>
          <th class="text-center">Alım Tarih</th>
          <th class="text-center">İşlemler</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $uye_id = session("uye_id");
          $query = query("SELECT * FROM depo INNER JOIN urunler ON urunler.urun_id=depo.depo_urun_id INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu WHERE depo_uye='$uye_id' AND depo_durum='0' ORDER BY depo_id DESC");
          if(rows($query) < 1){
            echo '<tr><td class="text-center" colspan="5">Henüz hiç Ürün satın almamışsınız.</td></tr>';
          }else{
            while($row = row($query)){
        ?>
        <tr id="urun_<?=$row["depo_id"]?>">
          <td class="text-center"><?=$row["depo_id"]?></td>
          <td>[<?=$row['sunucu_link']?>] <a href="<?=URL?>/market/<?=$row['sunucu_link']?>/<?=$row['kategori_link']?>"><?=$row["urun_adi"]?></a> <?php if ($row["depo_tur"] == "1"): ?><i data-toggle="tooltip" data-placement="right" class="fa fa-gift fa-fw" title="Hediye"></i><?php endif; ?></td>
          <td class="text-center"><?php if ($row["depo_urun_gun"] != "0"): ?><?=$row["depo_urun_gun"]?><?php else: ?><span class="label label-danger">Sınırsız</span><?php endif; ?></td>
          <td class="text-center"><?=date("d-m-Y",$row["depo_tarih1"])?></td>
          <td style="width:160px" class="text-center">
            <a href="#" urun-id="<?=$row["depo_id"]?>" urun-title="<?=$row["urun_adi"]?>" class="hediye_gonder btn btn-primary btn-xs">Hediye Gönder</a>
            <a onclick="return confirm('Bu ürünü aktifleştirmek istediğinden emin misin? Geri dönüşü yoktur.')" href="<?=URL?>/kullan/<?=$row["depo_id"]?>" class="btn btn-success btn-xs">Kullan</a>
          </td>
        </tr>
        <?php } } ?>
      </tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
  $(".hediye_gonder").click(function() {
    $("#hediye_gonder").modal();
    $("#g_o").val($(this).attr("urun-title"));
    $("#urun").val($(this).attr("urun-id"));
    $("#uye_kadi").val("");
    $(".sonuc").html('');
    $(".gonder").prop("disabled", false);
  });
  function hediye_et() {
    var s = confirm('Gerçekten göndermek istiyor musunuz? Geri dönüşü yoktur.');
    if (s == true) {
      var urun = $("#urun").val();
      var uye_kadi = $("#uye_kadi").val();
      if (urun != "" && uye_kadi != "") {
        $(".gonder").html('<i class="fa fa-refresh fa-spin"></i>');
        $.ajax({
          type: 'POST',
          url: INC_PATH+'/ajax/hediye_gonder.php',
          dataType: "json",
          data: "urun="+urun+"&uye_kadi="+uye_kadi,
          success: function(cevap) {
            $(".gonder").prop("disabled", true).html("Gönder");
            $('.sonuc').html('<div class="alert alert-'+cevap.class+'">'+cevap.mesaj+'</div>');
            if (cevap.basari == "true") {
              $("#urun_"+urun).remove();
            }else{
              $(".gonder").prop("disabled", false);
            }
          }
        });
      }else{
        alert("Lütfen boş alan bırakmayınız.");
      }
    }else{
      $("#hediye_gonder").modal("hide");
    }
  }
</script>

<div id="hediye_gonder" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Hediye Ürün Gönder</h4>
      </div>
      <div class="modal-body">
        <div class="sonuc">
          
        </div>
        <div class="form-group">
          <label>Gönderilecek Ürün</label>
          <input type="text" class="form-control" id="g_o" disabled>
        </div>
        <form action="" method="post" onsubmit="return false;">
          <div class="form-group" id="veriler">
            <label for="uye_kadi">Alıcı Kullanıcı Adı:</label>
            <input type="text" class="form-control" id="uye_kadi" placeholder="Göndereceğiniz kişinin kullanıcı adı.">
            <input type="hidden" id="urun" value="">
          </div>
          <button type="submit" class="btn btn-block btn-success gonder" onclick="hediye_et()">Gönder</button>
        </form>
      </div>
    </div>

  </div>
</div>