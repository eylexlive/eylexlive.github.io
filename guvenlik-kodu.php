<?php
session_start();
$md5yap = md5(rand(0, 9999));
$dogrulamakodu = strtoupper(substr($md5yap, 8, 6));
$_SESSION["dogrulamakodu"] = $dogrulamakodu;
$en = 75;
$boy = 25;
$image = ImageCreate($en, $boy);
$beyaz = ImageColorAllocate($image, 255, 255, 255);
$siyah = ImageColorAllocate($image, 0, 0, 0);
$kirmizi = ImageColorAllocate($image, 242, 0, 0);
ImageFill($image, 0, 0, $beyaz);
ImageString($image, 6, 9, 5, $_SESSION["dogrulamakodu"], $siyah);

imageline($image, 0, 2, $en, 2, $kirmizi);
imageline($image, 0, 25, $boy, 0, $kirmizi);
imageline($image, $en, $boy, 40, 0, $kirmizi);
imageline($image, 0, 23, $en, 23, $kirmizi);

header("Content-Type: image/jpeg");

ImageJpeg($image);

ImageDestroy($image);
exit();
?>