<script type="text/javascript">
  function getData() {
    $.ajax({
      url: "https://eu.mc-api.net/v3/server/info/<?=mset("sunucu_ip")?>",
      dataType: "json",
      method: "GET",
      success: function(data) {
        if (data["online"] == false) {
          $("#durum").addClass("label-danger").removeClass("label-success").html("Kapalı");
        }else{
          $("#durum").removeClass("label-danger").addClass("label-success").html("Açık");
          $("#online").html(data["players"]["online"]);
        }
      }
    });
  }getData();
  setInterval(function() {
    getData();
  }, 5000); // 5 saniye
</script>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Sunucu Durumu</h3>
  </div>
  <div class="panel-body text-center">
		<strong>Sunucu Durumu:</strong><br>
		<span class="label label-success" id="durum">Açık</span><br><br>
		<strong>Online Oyuncu Sayısı:</strong><br>
		<span class="label label-warning" id="online">Yükleniyor</span><br><br>
		<strong>Sunucu Sürümü:</strong><br>
		<span class="label label-default"><?=mset("sunucu_surum")?></span><br><br>
		<strong>Sunucu Adresi:</strong><br>
		<span class="label label-danger" style="font-size:0.9em"><?=mset("sunucu_ip")?></span><br>
	</div>
</div>