<?php

//função para resgatar conteudo da url
function webcheck ($url) {
$ch = curl_init ($url) ;
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($ch, CURLOPT_HEADER, 0);
//curl_setopt($ch, CURLOPT_COOKIEFILE, "cookiefile");
//curl_setopt($ch, CURLOPT_COOKIEJAR, "cookiefile");
curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id());
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$res = curl_exec($ch) ;
curl_close ($ch);
return ($res);
}

?>

