<?php
    session_destroy();
    cookie_sil("CWACCOUNTID");
    go_js(URL,2);
    echo alert("Çıkış başarılı. Yönlendiriliyorsunuz...","success");
?>