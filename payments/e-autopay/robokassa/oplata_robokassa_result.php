<?php

$post_data = '';
foreach ($_POST As $k_e_y => $value) {
    if (get_magic_quotes_gpc())
        $value = stripslashes($value);
    if ($post_data != '')
        $post_data.="&$k_e_y=" . urlencode($value);
    else
        $post_data = "$k_e_y=" . urlencode($value);
}
$len = strlen($post_data);
$fp = @fsockopen("sbsedu.e-autopay.com", 80);
if ($fp) {
    $request = "POST /ordering/oplata_roboxchange_result.php HTTP/1.0\r\n";
    $request.="Host: sbsedu.e-autopay.com\r\n";
    $request.="Content-type: application/x-www-form-urlencoded\r\n";
    $request.="Content-Length: $len\r\n\r\n";
    $request.="$post_data";
    fputs($fp, $request);
    while (!feof($fp))
        echo fgets($fp, 1024);
    fclose($fp);
}
?>