<?php
  defined("ADMIN") ? null: die('Hacking?');
  (($user["uye_rutbe"] == 1) OR ($user["uye_rutbe"] == 2)) ? null: go(ADMIN_URL);
?>
<div class="block block-bordered">
  <div class="block-header bg-gray-lighter">
    <div class="block-options">
      <a onclick="return false;" href='javascript:{(function(w,d){var uye_kadi=prompt("Oyuncu Arama (Kullanıcı Adı, Id, Mail, IP)","");if(uye_kadi!=null){window.open("<?=ADMIN_URL?>/?go=uye_ara&s="+uye_kadi+"&kadi=Berk","_blank")}})(window,document)}' class="btn btn-primary btn-xs">Hızlı Arama Pini</a>
    </div>
    <h3 class="block-title">Üye Ara</h3>
  </div>
  <div class="block-content">
    <form type="get">
      <div class="form-group">
        <label for="s">Oyuncu Arama (Kullanıcı Adı, Id, Mail, IP):</label>
        <input name="go" type="hidden" value="uye_ara">
        <div class="input-group">
          <input name="s" type="text" value="<?=isset($_GET["s"])?get("s"):null?>" id="s" class="form-control" placeholder="Aramak istediğiniz kişinin bilgilerini yazınız." >
          <span class="input-group-btn">
            <button type="submit" class="btn btn-default"><i class="fa fa-fw fa-search hidden-xs"></i> <span class="visible-xs">Ara</span></button>
          </span>
        </div>
      </div>
    </form>
  </div><hr style="margin:0;">
  <div class="table-responsive">
    <table class="table table-striped table-advance table-hover" style="margin-bottom: 0;">
      <thead>
        <tr>
          <th class="text-center">#</th>
          <th>Kullanıcı Adı</th>
          <th class="text-center">Eposta</th>
          <th class="text-center">Kredi</th>
          <th class="text-center">Rütbe</th>
          <?php if ($user["uye_rutbe"] == 1): ?><th class="text-center"></th><?php endif; ?>
          <th class="text-center">İşlemler</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(isset($_GET["s"])){
          $arama = get("s");
          $query = query("SELECT * FROM uyeler WHERE uye_kadi LIKE '%$arama%' or uye_id LIKE '%$arama%' or uye_email LIKE '%$arama%' or uye_ip LIKE '%$arama%' ORDER BY uye_id ASC");
          if(rows($query) < 1){
            echo '<tr><td class="text-center" colspan="6">Böyle bir üye bulunamadı.</td></tr>';
          }else{
            while($row = row($query)){
              ?>
              <tr>
                <td class="text-center"><?php echo $row["uye_id"]; ?></td>
                <td><a href="index.php?go=uye_duzenle&uid=<?php echo $row["uye_id"]; ?>"><?php echo ss($row["uye_kadi"]); ?></a></td>
                <td class="text-center"><?php echo ss($row["uye_email"]); ?></td>
                <td class="text-center"><?php echo $row["uye_kredi"]; ?></td>
                <td class="text-center">
                  <?php if ($row["uye_rutbe"] == "1"): ?>
                    <span class="label label-danger">Yönetici</span>
                  <?php elseif ($row["uye_rutbe"] == "2"): ?>
                    <span class="label label-warning">Moderatör</span>
                  <?php elseif ($row["uye_rutbe"] == "3"): ?>
                    <span class="label label-info">Ticket Yetkilisi</span>
                  <?php else: ?>
                    <span class="label label-default">Üye</span>
                  <?php endif; ?>
                </td>
                <?php if ($user["uye_rutbe"] == 1): ?>
                  <td style="width:1px" class="text-center">
                    <?php if ($row["uye_oauth_uid"] != "0"): ?>
                      <a href="<?=ADMIN_URL?>/?go=uyeler&kilit=<?=$row['uye_id']?>" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="left" title="İki Adımda Doğrulamayı Kapat"><i class="fa fa-unlock"></i></a>
                    <?php else: ?>
                      <a class="btn btn-danger btn-xs" disabled><i class="fa fa-unlock"></i></a>
                    <?php endif; ?>
                  </td>
                <?php endif; ?>
                <td style="width:100px" class="text-center">
                  <a href="<?=ADMIN_URL?>/?go=profil&uid=<?=$row['uye_id']?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                  <?php if ($user["uye_rutbe"] == 1): ?>
                    <a href="<?=ADMIN_URL?>/?go=uye_duzenle&uid=<?php echo $row["uye_id"]; ?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                  <?php endif; ?>
                </td>
              </tr>
        <?php } } } ?>
      </tbody>
    </table>
  </div>
</div>