<?php
    defined("ADMIN") ? null: die('Hacking?');
    ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
?>
<?php
if(isset($_GET['sid'])){
    $sid = get('sid');
    $varmi = query("SELECT * FROM sayfalar WHERE sayfa_id = '$sid'");
    if(rows($varmi) < 1){
        go(ADMIN_URL.'/?go=sayfalar');
        exit;
    }else{
        $delete = query("DELETE FROM sayfalar WHERE sayfa_id = '$sid'");
        if($delete){
            echo alert('Yazı başarıyla silindi. Yönlendiriliyorsunuz.','success');
            go(ADMIN_URL.'/?go=sayfalar',1);
        }else{
            echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
        }
    }
}
?>

<div class="block block-bordered">
    <div class="block-header bg-gray-lighter">
        <ul class="block-options">
            <span class="label label-default">Sayfa Sayısı: <?=rows(query("SELECT * FROM sayfalar"))?></span>
        </ul>
        <h3 class="block-title">Tüm Sayfalar</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" style="margin-bottom: 0">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Sayfa Başlığı</th>
                <th class="text-center">Sayfa Tarihi</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            @$sayfa = get("sayfa") ? get("sayfa") : 1;
            $ksayisi = rows(query("SELECT * FROM sayfalar"));
            $limit = 10;
            $ssayisi = ceil($ksayisi/$limit);
            $baslangic = $sayfa * $limit - $limit;
            if($ksayisi > $limit){
                $query = query("SELECT * FROM sayfalar ORDER BY sayfa_id DESC LIMIT $baslangic,$limit");
                $oncekiSayfa = $sayfa > 1 ? $sayfa - 1 : 1;
                $onceki = ADMIN_URL.'/?go=sayfalar&sayfa='.$oncekiSayfa;
                $sonrakiSayfa = $sayfa < $ssayisi ? $sayfa + 1 : $ssayisi;
                $sonraki = ADMIN_URL.'/?go=sayfalar&sayfa='.$sonrakiSayfa;
            }else{
                $query = query("SELECT * FROM sayfalar ORDER BY sayfa_id DESC");
            }
            if(rows($query)){
                while($row = row($query)){
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $row['sayfa_id']; ?></td>
                        <td><a href="<?=ADMIN_URL?>/?go=sayfa_duzenle&sid=<?=$row["sayfa_id"]?>"><?php echo ss($row['sayfa_baslik']); ?></a></td>
                        <td class="text-center"><?php echo tarih($row['sayfa_tarih']); ?></td>
                        <td width="100px" class="text-center">
                            <a href="<?=URL?>/<?=$row['sayfa_link']?>.html" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                            <a href="<?=ADMIN_URL?>/?go=sayfa_duzenle&sid=<?=$row["sayfa_id"]?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                            <a onclick="SwSil('sayfalar','<?=$row["sayfa_id"]?>','sid',$(this));" href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                <?php }}else{ ?>
                <tr>
                    <td class="text-center" colspan="4">Henüz hiç sayfa eklememişsiniz.</td>
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
                <li><a href="<?=ADMIN_URL?>/?go=sayfalar&sayfa=1"><i class="fa fa-angle-double-left"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-double-left"></i></a></li>
            <?php endif; ?>

            <?php if ($sayfa != 1): ?>
                <li><a href="<?=ADMIN_URL?>/?go=sayfalar&sayfa=<?=($sayfa-1)?>"><i class="fa fa-angle-left"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-left"></i></a></li>
            <?php endif; ?>

            <?php
            for($s = $sol_sayfalar; $s <= $sag_sayfalar; $s++) {
                if($sayfa == $s) {
                    echo '<li class="active"><a href="'.ADMIN_URL.'/?go=sayfalar&sayfa='.$s.'">'.$s.'</a></li>';
                } else {
                    echo '<li><a href="'.ADMIN_URL.'/?go=sayfalar&sayfa='.$s.'">'.$s.'</a></li>';
                }
            }
            ?>
            <?php if ($sayfa != $ssayisi): ?>
                <li><a href="<?=ADMIN_URL?>/?go=sayfalar&sayfa=<?=($sayfa+1)?>"><i class="fa fa-angle-right"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-right"></i></a></li>
            <?php endif; ?>

            <?php if ($sayfa != $ssayisi): ?>
                <li><a href="<?=ADMIN_URL?>/?go=sayfalar&sayfa=<?=$ssayisi?>"><i class="fa fa-angle-double-right"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-double-right"></i></a></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>