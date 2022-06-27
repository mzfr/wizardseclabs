<html>
<body
</b><h2> WEBSITE SCRAPER </h2></b>
<pre> hello, my manager has asked me to write a website scraper. the scraper is still in beta.
		- alex
</pre>
<br>
<img src='chickenjoe.jpg' align='right'>
<form action="" method="GET">
<input type="text" name="command" placeholder="site to scrape">
<br>
<input type="submit" value="scrape">

</form>
</body>
</html>

<?php
$banned_hosts = array();
$banned_hosts[] = "localhost";
$banned_hosts[] = "127.0.0.1";
if (preg_match('/\b'.$_GET['command'].'\b/', 'http') !== false) {
    die('<b><font color="red">ERROR:</b></color> URL must contain http schema');

}
foreach($banned_hosts as $host) {
   if (parse_url($_GET['command'], PHP_URL_HOST) == $host) {
	die('<b><font color="red">ERROR:</b></color> restricted host');
   } 
   if (preg_match('/\b'.$_GET['command'].'\b/', $host)) {
        die('<b><font color="red">ERROR:</b></color> restricted host');

    }
}
// ip ranges 127.0.0.1 - 127.255.255.254 also work using ip2long we can use a range
$ip = '2130706433';
$ip2 = '2147483646';
$gethostname = parse_url($_GET['command'], PHP_URL_HOST);
$hostname = gethostbyname($gethostname);
if (ip2long($hostname) <= $ip2 && $ip <= ip2long($hostname)) {
    die('<b><font color="red">ERROR:</b></color> restricted host');

}
system('curl -s ' . escapeshellcmd($_GET['command']) . ' || echo website not found');
?>
