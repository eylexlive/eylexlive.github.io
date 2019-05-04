$('[data-toggle="tooltip"]').tooltip();

function gonder() {
  var uye_kadi = $("#giris_yap #uye_kadi").val();
  var uye_sifre = $("#giris_yap #uye_sifre").val();
  
  if (uye_kadi != "" && uye_sifre != "") {
    $("#giris_yap .gonder").html('<i class="fa fa-refresh fa-spin"></i>');
    $.ajax({
      type: 'POST',
      url: INC_PATH+'/ajax/giris_yap.php',
      dataType: "json",
      data: "uye_kadi="+uye_kadi+"&uye_sifre="+uye_sifre,
      success: function(cevap) {
        $("#giris_yap .gonder").prop("disabled", true).html("Gönder");
        $('#giris_yap .sonuc').html('<div class="alert alert-'+cevap.class+'">'+cevap.mesaj+'</div>');
        if (cevap.basari == "true" || cevap.yenile == "true") {
          setTimeout(function(){
            location.href = ANLIK_URL;
          },1000);
        }else{
          $("#giris_yap .gonder").prop("disabled", false);
        }
      }
    });
  }else{
    alert("Lütfen boş alan bırakmayınız.");
  }
  
}