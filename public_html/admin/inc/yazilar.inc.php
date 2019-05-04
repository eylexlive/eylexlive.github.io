<?php
  defined("ADMIN") ? null: die('Hacking?');
  (($user["uye_rutbe"] == 1) OR ($user["uye_rutbe"] == 2)) ? null: go(ADMIN_URL);
?>
<div class="block block-bordered">
    <div class="block-header bg-gray-lighter">
        <ul class="block-options">
            <span class="label label-default">Yazı Sayısı: <?=rows(query("SELECT * FROM yazi WHERE yazi_durum != '3'"))?></span>
        </ul>
        <h3 class="block-title">Tüm Yazılar</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" style="margin-bottom: 0">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Başlık</th>
                    <th class="text-center">Yazan Kişi</th>
                    <th class="text-center">Kategori</th>
                    <th class="text-center">Tarih</th>
                    <th class="text-center">Yazı Durum</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
                @$sayfa = get("sayfa") ? get("sayfa") : 1;
                $ksayisi = rows(query("SELECT * FROM yazi"));
                $limit = 10;
                $ssayisi = ceil($ksayisi/$limit);
                $baslangic = $sayfa * $limit - $limit;
                if($ksayisi > $limit){
                    $query = query("SELECT * FROM yazi INNER JOIN kategoriler_yazi ON kategoriler_yazi.kategori_id = yazi.yazi_kategori_id INNER JOIN uyeler ON uyeler.uye_id = yazi.yazi_olusturan_id WHERE yazi_durum != '3' ORDER BY yazi_id DESC LIMIT $baslangic,$limit");
                    $oncekiSayfa = $sayfa > 1 ? $sayfa - 1 : 1;
                    $onceki = ADMIN_URL.'/?go=yazilar&sayfa='.$oncekiSayfa;
                    $sonrakiSayfa = $sayfa < $ssayisi ? $sayfa + 1 : $ssayisi;
                    $sonraki = ADMIN_URL.'/?go=yazilar&sayfa='.$sonrakiSayfa;
                }else{
                    $query = query("SELECT * FROM yazi INNER JOIN kategoriler_yazi ON kategoriler_yazi.kategori_id = yazi.yazi_kategori_id INNER JOIN uyeler ON uyeler.uye_id = yazi.yazi_olusturan_id WHERE yazi_durum != '3' ORDER BY yazi_id DESC");
                }
                if (rows($query) > 0) {
                    while ($row = row($query)) {
            ?>
                <tr>
                    <td class="text-center"><?=$row["yazi_id"]?></td>
                    <td><a href="<?=ADMIN_URL?>/?go=yazi_duzenle&uid=<?=$row["yazi_id"]?>"><?=$row["yazi_baslik"]?></a></td>
                    <td class="text-center"><?=$row["uye_kadi"]?></td>
                    <td class="text-center"><?=$row["kategori_baslik"]?></td>
                    <td class="text-center"><?=$row["yazi_tarih"]?></td>
                    <td class="text-center">
                        <?php if ($row["yazi_durum"] == "0"): ?>
                            <span class="label label-default">Yayınlanmamış</span>
                        <?php elseif($row["yazi_durum"] == "1"): ?>
                            <span class="label label-success">Yayınlanmış</span>
                        <?php elseif($row["yazi_durum"] == "2"): ?>
                            <span class="label label-warning">Taslak</span>
                        <?php endif; ?>
                    </td>
                    <td style="width:100px" class="text-center">
                        <a href="<?=URL?>/blog/<?=$row["yazi_link"]?>.html" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>
                        <a href="<?=ADMIN_URL?>/?go=yazi_duzenle&uid=<?=$row["yazi_id"]?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
                        <a href="#" onclick="SwSil('yazi_sil','<?=$row["yazi_id"]?>','uid',$(this));" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php }}else{ ?>
                <tr>
                    <td class="text-center" colspan="8">Henüz hiç yazı eklememişsiniz.</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php if ($ksayisi > 0): ?>
    <nav class="text-center">
        <?php
        $sayfa_goster = 6;

        $en_az_orta = ceil($sayfa_goster/2);
        $en_fazla_orta = ($ssayisi+1) - $en_az_orta;

        $sayfa_orta = $sayfa;
        if($sayfa_orta < $en_az_orta) $sayfa_orta = $en_az_orta;
        if($sayfa_orta > $en_fazla_orta) $sayfa_orta = $en_fazla_orta;

        $sol_sayfalar = round($sayfa_orta - (($sayfa_goster-1) / 2));
        $sag_sayfalar = round((($sayfa_goster-1) / 2) + $sayfa_orta);

        if($sol_sayfalar < 1) $sol_sayfalar = 1;
        if($sag_sayfalar > $ssayisi) $sag_sayfalar = $ssayisi;
        ?>
        <ul class="pagination">
            <?php if ($sayfa != 1): ?>
                <li><a href="<?=ADMIN_URL?>/?go=yazilar&sayfa=1"><i class="fa fa-angle-double-left"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-double-left"></i></a></li>
            <?php endif; ?>

            <?php if ($sayfa != 1): ?>
                <li><a href="<?=ADMIN_URL?>/?go=yazilar&sayfa=<?=($sayfa-1)?>"><i class="fa fa-angle-left"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-left"></i></a></li>
            <?php endif; ?>

            <?php
            for($s = $sol_sayfalar; $s <= $sag_sayfalar; $s++) {
                if($sayfa == $s) {
                    echo '<li class="active"><a href="'.ADMIN_URL.'/?go=yazilar&sayfa='.$s.'">'.$s.'</a></li>';
                } else {
                    echo '<li><a href="'.ADMIN_URL.'/?go=yazilar&sayfa='.$s.'">'.$s.'</a></li>';
                }
            }
            ?>
            <?php if ($sayfa != $ssayisi): ?>
                <li><a href="<?=ADMIN_URL?>/?go=yazilar&sayfa=<?=($sayfa+1)?>"><i class="fa fa-angle-right"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-right"></i></a></li>
            <?php endif; ?>

            <?php if ($sayfa != $ssayisi): ?>
                <li><a href="<?=ADMIN_URL?>/?go=yazilar&sayfa=<?=$ssayisi?>"><i class="fa fa-angle-double-right"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-double-right"></i></a></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
