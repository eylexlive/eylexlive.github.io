<?php defined("ADMIN") ? null: die('Hacking?'); ?>
<div class="bg-image overflow-hidden push" style="background-image: url('assets/img/photos/photo36@2x.jpg');">
  <div class="bg-black-op">
    <div class="content content-full text-center">
      <h1 class="h1 font-w700 text-white animated fadeInDown push-50-t push-5">CraftWeb v5</h1>
      <h2 class="h3 font-w600 text-white-op animated fadeInDown push-50">Değişime Açılan Yolda Size Eşlik Eder</h2>
    </div>
  </div>
</div>
<?php $icerik = mset("admin_duyuru", "ayar_deger"); ?>
<?php if ($icerik): ?>
  <div class="alert alert-info"><h5 style="margin-bottom: 3px;">YETKİLİ DUYURUSU!</h5> <?=nl2br($icerik)?></div>
<?php endif; ?>
<div class="row">
  <div class="col-lg-3 col-sm-6">
    <a class="block block-link-hover3 text-center" href="<?=ADMIN_URL?>/?go=uyeler">
      <div class="block-content block-content-full">
        <div class="h1 font-w700 text-primary" id="tum_uyeler"><i class="fa fa-refresh fa-spin"></i></div>
      </div>
      <div class="block-content block-content-full block-content-mini bg-gray-lighter text-muted font-w600">Tüm Üyeler</div>
    </a>
  </div>
  <div class="col-lg-3 col-sm-6">
    <a class="block block-link-hover3 text-center" href="<?=ADMIN_URL?>/?go=destekler&tur=3">
      <div class="block-content block-content-full">
        <div class="h1 font-w700 text-success" id="talepler"><i class="fa fa-refresh fa-spin"></i></div>
      </div>
      <div class="block-content block-content-full block-content-mini bg-gray-lighter text-muted font-w600">Bekleyen Talepler</div>
    </a>
  </div>
  <div class="col-lg-3 col-sm-6">
    <a class="block block-link-hover3 text-center" href="<?=ADMIN_URL?>/?go=aktif_urunler">
      <div class="block-content block-content-full">
        <div class="h1 font-w700" id="kazanc_buGun"><i class="fa fa-refresh fa-spin"></i></div>
      </div>
      <div class="block-content block-content-full block-content-mini bg-gray-lighter text-muted font-w600">Günlük Kazanç</div>
    </a>
  </div>
  <div class="col-lg-3 col-sm-6">
    <a class="block block-link-hover3 text-center" href="<?=ADMIN_URL?>/?go=sayac">
      <div class="block-content block-content-full">
        <div class="h1 font-w700" id="ziyaret"><i class="fa fa-refresh fa-spin"></i></div>
      </div>
      <div class="block-content block-content-full block-content-mini bg-gray-lighter text-muted font-w600">Ziyaretçiler</div>
    </a>
  </div>
</div>
<div class="row">
  <div class="col-md-7">
    <div id="detay">
      <?php if ($user["uye_rutbe"] == 1): ?>
        <div class="block block-bordered" style="margin-bottom: 5px">
          <div class="block-content block-content-full clearfix">
            <span class="h4 font-w700 pull-right"><span id="kazanc_buGun2"><i class="fa fa-refresh fa-spin"></i></span> TL</span>
            <span class="h4 text-muted">Günlük Kazanç</span>
          </div>
        </div>
        <div class="block block-bordered" style="margin-bottom: 5px">
          <div class="block-content block-content-full clearfix">
            <span class="h4 font-w700  pull-right"><span id="kazanc_buAy"><i class="fa fa-refresh fa-spin"></i></span> TL</span>
            <span class="h4 text-muted">Aylık Kazanç</span>
          </div>
        </div>
        <div class="block block-bordered">
          <div class="block-content block-content-full clearfix">
            <span class="h4 font-w700 pull-right"><span id="kazanc_buYil"><i class="fa fa-refresh fa-spin"></i></span> TL</span>
            <span class="h4 text-muted">Yıllık Kazanç</span>
          </div>
        </div>
      <?php endif; ?>
    </div>
    <!--<div class="block block-bordered" id="duyurular">
      <div class="block-header bg-gray-lighter">
        <ul class="block-options">
          <li>
            <button type="button" data-toggle="block-option" data-action="content_toggle" onclick="duyuru_kapa();"><i class="si si-arrow-up"></i></button>
          </li>
        </ul>
        <h3 class="block-title">Duyurular</h3>
      </div>
      <div class="block-content" style="min-height: 150px;">
      </div>
      
    </div>-->
  </div>
  <div class="col-md-5">
    <div class="block block-bordered">
      <div class="block-content block-content-full bg-city-dark text-center">
        <div class="push-10-t push-10">
          <i class="fa fa-4x fa-commenting-o text-white-op push-10"></i>
          <h3 class="h4 text-white">Yorumlar (<span id="yorumlar"> <i class="fa fa-refresh fa-spin"></i> </span>)</h3>
        </div>
      </div>
      <div class="block-content block-content-full clearfix">
        <a class="btn btn-block btn-default" href="<?=ADMIN_URL?>/?go=yorumlar">Yorumları Onayla</a>
      </div>
    </div>
    
    <div class="block block-bordered" id="son_alisveris">
      <div class="block-header">
          <ul class="block-options">
              <li>
                  <button type="button" onclick="son_alisveris_get();"><i class="si si-refresh"></i></button>
              </li>
          </ul>
          <h3 class="block-title">Son Alışverişler</h3>
      </div>
      <div class="block-content bg-gray-lighter">
        <div class="row items-push">
          <?php
            $time = time()-86400;
            $query = query("SELECT market_id FROM market WHERE market_tarih1>$time");
          ?>
          <div class="col-xs-4">
            <div class="text-muted"><small><i class="si si-clock"></i>&nbsp; 24 saat</small></div>
            <div class="font-w600"><?=rows($query)?> Satış</div>
          </div>
          <?php
            $time = time()-604800;
            $query = query("SELECT market_id FROM market WHERE market_tarih1>$time");
          ?>
          <div class="col-xs-4">
            <div class="text-muted"><small><i class="si si-calendar"></i>&nbsp; 7 gün</small></div>
            <div class="font-w600"><?=rows($query)?> Satış</div>
          </div>
          <?php
            $query = query("SELECT market_urun_fiyat,market_id FROM market ORDER BY market_id DESC LIMIT 10"); $bal = 0;
            while ($row = row($query)) {
              $bal = $bal+$row["market_urun_fiyat"];
            }
          ?>
          <div class="col-xs-4 h1 font-w300 text-right"><?=$bal?> TL</div>
        </div>
      </div>
      <div class="block-content" id="liste"></div>
      
      <script type="text/javascript">
        App.blocks('#liste', 'close');
        App.blocks('#son_alisveris', 'state_loading');
        
        function son_alisveris_get() {
          App.blocks('#son_alisveris', 'state_loading');
          $.ajax({
            type: "POST",
            url: ADMIN_URL+"/ajax/son_alisveris.php?cw="+Date.now(),
            success: function(sonuc){
              $("#son_alisveris #liste").html(sonuc);
              App.blocks('#son_alisveris', 'state_normal');
              App.blocks('#liste', 'open');
            }
          });
        }
      </script>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(window).load(function() {
    son_alisveris_get();
    if (localStorage.getItem("duyuru_kapa") == "true") {
      App.blocks('#duyurular', 'content_hide');
    }else{
      App.blocks('#duyurular', 'state_loading');
      cw_duyuru();
    }
    detay();
  });
  function detay() {
    App.blocks('#son_alisveris', 'state_loading');
    $.ajax({
      type: "GET",
      url: ADMIN_URL+"/ajax/kazanc.ajax.php?cw="+Date.now(),
      dataType: "json",
      success: function(cevap){
        if (cevap.hata == "true") {
          alert(cevap.mesaj);
          location.reload();
        }else{
          jQuery.each(cevap, function(i, val) {
            $("#"+i).html(val);
          });
        }
      }
    });
  }
  function cw_duyuru() {
    $.ajax({
      type: "GET",
      url: ADMIN_URL+"/ajax/duyuru.php?cw="+Date.now(),
      success: function(sonuc){
        $("#duyurular .block-content").html(sonuc);
        App.blocks('#duyurular', 'state_normal');
      }
    });
  }
  
  function duyuru_kapa() {
    if (localStorage.getItem("duyuru_kapa") == "true") {
      localStorage.setItem('duyuru_kapa',"false");
      App.blocks('#duyurular', 'state_loading');
      cw_duyuru();
    }else{
      localStorage.setItem('duyuru_kapa',"true");  
    }
  }
</script>