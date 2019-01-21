// JavaScript Document CNGame Minecraft Panel
<!--

var secs = 120;

var wait = secs * 1000;

document.form1.KT_Insert1.disabled=true;

	

for(i=1;i<=secs;i++) {

 window.setTimeout("update(" + i + ")", i * 1000);

}



window.setTimeout("timer()", wait);



function update(num) {

 if(num == (wait/1000)) {

  document.form1.KT_Insert1.value = "KREDİ YÜKLE";

 }

 else {

  printnr = (wait/1000)-num;

  document.form1.KT_Insert1.value = "Lütfen (" + printnr + ") saniye bekleyin !";

 }

}



function timer() {

 document.form1.KT_Insert1.disabled=false;

}

//-->