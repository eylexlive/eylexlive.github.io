$(function(){
	/* Bildirim sistemini tarayıcıya kaydetme */
	document.title = localStorage.getItem("new_title");
	favicon.badge(localStorage.getItem("alert_count"));
	
	bildirimcek = function(){
		$.ajax({
			type:"POST",
			url:"includes/ajax/deneme.php",
			data:"",
			dataType:"json",
			success:function(cevap){
					
				if(cevap.durum==1){
					favicon.badge(cevap.yenimsj);
					if (cevap.yenimsj == 0) {
						var d_title = TITLE;
					}else{
						var d_title = "("+cevap.yenimsj+") "+TITLE;
					}
					document.title = d_title;
					localStorage.setItem("new_title", d_title);
					localStorage.setItem("alert_count", cevap.yenimsj);
				}else{
					favicon.badge("");
				}
			}
		
		});
	
	}
	
	tekraret = setInterval(bildirimcek,3000);
});