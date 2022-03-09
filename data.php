<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://formulae.brew.sh/api/formula.json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Authority: formulae.brew.sh';
$headers[] = 'Cache-Control: max-age=0';
$headers[] = 'Upgrade-Insecure-Requests: 1';
$headers[] = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1';
$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
$headers[] = 'Sec-Fetch-Site: same-origin';
$headers[] = 'Sec-Fetch-Mode: navigate';
$headers[] = 'Sec-Fetch-User: ?1';
$headers[] = 'Sec-Fetch-Dest: document';
$headers[] = 'Referer: https://formulae.brew.sh/formula/';
$headers[] = 'Accept-Language: en-US,en;q=0.9,fa;q=0.8,la;q=0.7';
$headers[] = 'If-None-Match: W/\"62288dd2-eae9b5\"';
$headers[] = 'If-Modified-Since: Wed, 09 Mar 2022 11:21:54 GMT';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$arr = json_decode($result, true);

$res = "";
$resarr = [];
$fullarr = [];

foreach($arr as $item){
	$json = json_encode($item, JSON_PRETTY_PRINT);
	$iscompatible = strpos($json,"\/opt\/homebrew\/") !== false;
	if($iscompatible){
		$res = $res . $item["full_name"] . "\n";
		array_push($resarr, $item["full_name"]);
		array_push($fullarr, $item);
	}
}

$base64 = base64_encode($res);

file_put_contents("simplified.data", $res);
file_put_contents("simplified.json", json_encode($resarr, JSON_PRETTY_PRINT));
file_put_contents("full.json", json_encode($fullarr, JSON_PRETTY_PRINT));
file_put_contents("base64.hash", $base64);