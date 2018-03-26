<?php
$client = new \GuzzleHttp\Client();
$url = sprintf('https://www.googleapis.com/youtube/v3/playlistItems?part=snippet,contentDetails&playlistId=%s&key=%s', $instance['playlistid'], $instance['apikey']);
$videos = null;
try {
	$res  = $client->request( 'GET', $url );
	$data = json_decode( $res->getBody() );
	$videos = $data->items;
} catch(Exception $e) {}

print '<pre>';
var_dump($videos);
print '</pre>';
?>
<div></div>