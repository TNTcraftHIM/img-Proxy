<?php

function isValidUrl($url){ 
	return preg_match('/^http[s]?:\/\/'.  
    '(([0-9]{1,3}\.){3}[0-9]{1,3}'. // IP形式的URL- 199.194.52.184  
    '|'. // 允许IP和DOMAIN（域名）  
    '([0-9a-z_!~*\'()-]+\.)*'. // 域名- www.  
    '([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.'. // 二级域名  
    '[a-z]{2,6})'.  // first level domain- .com or .museum  
    '(:[0-9]{1,4})?'.  // 端口- :80  
    '((\/\?)|'.  // a slash isn't required if there is no file name  
    '(\/[0-9a-zA-Z_!~\'
\.;\?:@&=\+\$,%#-\/^\*\|]*)?)$/',  
    $url) == 1;
}
function getHost($url){
	preg_match("/^(http:\/\/|https:\/\/)?([^\/]+)/i", $url, $matches); 
	return $matches;
}
function findReferer($host){
	$dict=array( //指定网站的referer
	'i.pximg.net'=> 'www.pixiv.net',
	'domain'=> 'referer'
	);
	$referer = $dict[$host[2]];
	if (empty($referer)) $referer=$host[0];
	else if ($referer) $referer=$host[1].$referer;
	return $referer;
}

if(empty($_GET["url"])) echo "参数错误<br><br>可用参数：<br>url（必选）- 目标图片URL<br>ref（可选）- 请求头部Referer";
else if ($_GET["url"]) {
	
	$URL = $_GET["url"];
	
	if (!isValidUrl($URL)) {
		echo "URL不合法";
		exit;
	}
	
	$Host = getHost($URL);
	if ($_GET["ref"]) $referer = $_GET["ref"];
	else if(empty($_GET["ref"])) $referer = findReferer($Host);
	
	$hdrs = array(
	  'http' =>array('header' => 
	   "Accept: image/webp,image/*,*/*;q=0.8\r\n" .
	   "Referer: ".$referer."/\r\n" .
	   "User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36\r\n" .
	   "Accept-Language: zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3\r\n",
	   'timeout'=>5
	  ),
	);
	$context = stream_context_create($hdrs);
	
	header('content-type: image/jpeg'); 
	echo file_get_contents($URL, 0, $context);
}
?>
