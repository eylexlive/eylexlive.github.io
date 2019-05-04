<?php
  if (!session("login") AND cookie("CWACCOUNTID")) {
    $uye_token = @htmlspecialchars(addslashes(trim(cookie("CWACCOUNTID"))));
    $cookie = query("SELECT * FROM uyeler WHERE uye_token = '$uye_token'");
    if(rows($cookie) > 0){
      $row = row($cookie);
      session_olustur(array(
        'login' => true,
        'uye_id' => $row['uye_id'],
        'uye_hesap_tur' => $row['uye_hesap_tur']
      ));
      cookie_olustur("CWACCOUNTID",$row["uye_token"],"600000");
      go(URL);
    }else{
      cookie_sil("CWACCOUNTID");
    }
  }
?>
<?php if (session("two_factor") == true): ?>
  <?php
    if (isset($_POST["kontrol"])) {
      require_once 'includes/class/GoogleAuthenticator.php';
      $ga = new PHPGangsta_GoogleAuthenticator();
      
      $oneCode = post("code1").post("code2").post("code3").post("code4").post("code5").post("code6");
      $oneCode = empty($oneCode)?"999999":$oneCode;
      $uyeQ = query("SELECT * FROM uyeler WHERE uye_id = '".session("uye_o_id")."'");
      $uRow = row($uyeQ);
      
      $checkResult = $ga->verifyCode($uRow["uye_oauth_uid"], $oneCode, 2);
      if ($checkResult) {
        session_sil("two_factor");
        session_sil("uye_o_id");
        
        session_olustur(array(
          'login' => true,
          'uye_id' => $uRow['uye_id']
        ));
        cookie_olustur("CWACCOUNTID", $uRow["uye_token"], "600000");
        echo alert("Giriş işlemi başarılı. Sayfa Yenileniyor...", "success");
        go(ANLIK_URL, 2);
      }else{
        echo alert("Güvenlik Kodu Hatalı!", "danger");
      }
    }
  ?>
  <div class="panel panel-default">
    <div class="panel-heading">Güvenlik Kodu</div>
    <div class="panel-body">
      <form action="" method="post">
        <div class="form-group">
          <label for="login-username">Güvenlik Kodu:</label>
          <div class="gkod">
            <input type="text" class="form-control input-lg" maxlength="1" name="code1">
            <input type="text" class="form-control input-lg" maxlength="1" name="code2">
            <input type="text" class="form-control input-lg" maxlength="1" name="code3">
            <span class="cizgi">-</span>
            <input type="text" class="form-control input-lg" maxlength="1" name="code4">
            <input type="text" class="form-control input-lg" maxlength="1" name="code5">
            <input type="text" class="form-control input-lg" maxlength="1" name="code6">
          </div>
        </div>
        <p>Authenticator Uygulamanızdaki 6 Haneli giriş kodunu girmelisiniz.</p>
        <div class="form-group" style="margin-bottom:0;">
          <button class="btn btn-block btn-success" name="kontrol" value="true" type="submit">Giriş Yap</button>
        </div>
      </form>
    </div>
  </div>
  <script type="text/javascript">
    var input = $(".gkod input");
    for (var i = 0; i < input.length; i++) {
      input[i].index = i;
      input[i].oncopy = function(e) {
        return false;
      }
      input[i].oncut = function(e) {
        return false;
      }
      input[i].onpaste = function(e) {
        return false;
      }
    }
    for (var i = 0; i < input.length; i++) {
      input[i].onkeyup = function(e) {
        if (e.keyCode == 8) {
          input[this.index - 1].focus();
        }
        if (/[0-9]/.test(this.value)) {
          if (input.length != this.index + 1) {
            input[this.index + 1].focus();
          }else{
            $("button[name=kontrol]").click();
          }
        } else {
          this.value = "";
        }
      }
    }
  </script>
<?php endif; ?>