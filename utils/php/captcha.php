<?php 
session_start();

$captchaEls = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
$capthcaText = substr(str_shuffle($captchaEls), 0, 5);

$_SESSION["captcha"] = $capthcaText;

$image = imagecreatetruecolor(150, 40);
$bgColor = imagecolorallocate($image, 108, 117, 125);
$textColor = imagecolorallocate($image, 255,255, 255);

imagefill($image, 0, 0, $bgColor);
imagestring($image, 10, 50, 12, $capthcaText, $textColor);

header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
?>