<?php 
$page = file_get_contents('https://www.victoriaweather.ca/station.php?id=74');
$doc = new DOMDocument();
$doc->loadHtml($page);
$alldata = $doc->getElementById('current_obs')->nodeValue;
$windspeed= explode('Wind Speed:',$alldata);
$exp = explode('Explainer', $windspeed[1]);
$gust = explode('Gust:', $exp[0]);
if($gust[1]) {
	$s = explode(' km/hr', $gust[0]);
	$speed = trim($s[0]);
	$gs = explode(' km/hr', $gust[1]);
	$gustspeed = trim($gs[0]);
	$direction = trim($s[1]);
} else {
	$s = explode(' km/hr', $gust[0]);
	$speed = trim($s[0]);
	$gustspeed = 0;
	$direction = trim($s[1]);
}

$message = <<<EOT
Speed: $speed, Gust Speed: $gustspeed, Direction: $direction
EOT;


require 'vendor/autoload.php';
use \Mailjet\Resources;
$mj = new \Mailjet\Client('8e8c62a673ee09fd0089d20f3ae1a443','2acee43d45b994dc02751814e6ded109',true,['version' => 'v3.1']);
$body = [
'Messages' => [
	[
	'From' => [
		'Email' => "allankh@icloud.com",
		'Name' => "Allan"
	],
	'To' => [
		[
		'Email' => "allankh@icloud.com",
		'Name' => "Allan"
		]
	],
	'Subject' => "Wind Warning",
	'TextPart' => $message
	]
]
];
$response = $mj->post(Resources::$Email, ['body' => $body]);
$response->success() && var_dump($response->getData());

