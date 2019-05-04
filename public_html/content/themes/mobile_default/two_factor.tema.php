<?php !session('login') ? go_js(URL."/kayitol") : null; ?>
<?php 
echo alert("<b>İki Faktörlü Doğrulama Sistemini</b> kullanmak veya işlem yapmak için lütfen bilgisayar kullanınız.","info");
?>