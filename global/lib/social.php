<?php
function fb_post($link, $message = '')
{
	define('FACEBOOK_SDK_V4_SRC_DIR', GLOBAL_PATH . 'plugins/Facebook/');
	require_once(FACEBOOK_SDK_V4_SRC_DIR . 'autoload.php');
	$config = array(
		'app_id' => '1583160898605101',
		'app_secret' => 'bf72ee7904423e43d23034685de75028',
		'access_token' => 'EAAWf4F770C0BAD7IACpGyn2mTCe9ZCZC29FsOd2smZAqGuz4YWOp5gprL5Sv95k4Qi8a5aHm6IVmQAaFKq3HaIRXIYq8DpZAH4LksReZA4iXsjQX9dtX95nhpJXHTR0UI5lXo4LHQmz42HjaKD2zuYKHWki7agywSoYrpUzft1gZDZD',
		'default_graph_version' => 'v2.6'
	);
	//URL for token https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=1583160898605101&client_secret=bf72ee7904423e43d23034685de75028&fb_exchange_token=EAAWf4F770C0BAMhF6SLZC8NoGHqY2LCLf8J1VExwKZAdfK7Pc1bQLd9DISYJeO6VCljKTgOI3DcE2jGPf1i0P1Nab6q3EcsfOCcr18qfW4BvhwJCB4X4YJIRSErrNOCI4wMqL5vEV2gnH7AMVaIeM9TP2byNRbIzW4g8QV5YrZBU48ZCgXQK
	$fb = new Facebook\Facebook($config);
	//Post property to Facebook
	$linkData = array(
		'link' => $link,
		'message' => $message
	);
	$pageAccessToken = $config['access_token'];
	try {
		$response = $fb->post('/mycyber.media.solutions/feed', $linkData, $pageAccessToken);
	} catch (Facebook\Exceptions\FacebookResponseException $e) {
		//echo 'Graph returned an error: '.$e->getMessage();
		return 0;
	} catch (Facebook\Exceptions\FacebookSDKException $e) {
		//echo 'Facebook SDK returned an error: '.$e->getMessage();
		return 0;
	}
	$graphNode = $response->getGraphNode();
	return $graphNode;
}


function twitter_post($status, $picture = '')
{
	define('TWITTER_API_DIR', GLOBAL_PATH . 'plugins/codebird-twitter-api/');
	require_once(TWITTER_API_DIR . 'codebird.php');

	\Codebird\Codebird::setConsumerKey("ebpAzbx82aeVpmjZ3oSvWPIm3", "9xYLhEP1t9SGb1dYGq9va3eFzlucOxjrQkLL63gu9t1Bo87Lk0");
	$cb = \Codebird\Codebird::getInstance();
	$cb->setToken("2835361284-5RS5mRPyvxR6RYi2qnqc0e2WiFAr8WlyGq8AXIW", "DpqeZn3VO0nTFQ3lpsDyDi2eEHjNr4NkSwAAe2d2WM0tB");
	$with_media = $picture && file_exists($picture);
	$params = $with_media ?
		array(
			'status' => $status,
			'media[]' => $picture
		) :
		array(
			'status' => $status
		);
	$reply = $with_media ?
		$cb->statuses_updateWithMedia($params) :
		$cb->statuses_update($params);
	return $reply;
}

function get_social_metas($metas)
{
	$res = '
<!-- Schema.org markup for Google+ -->
<meta itemprop="name" content="' . @$metas["title"] . '">
<meta itemprop="description" content="' . @$metas["description"] . '">
<meta itemprop="image" content="' . @$metas["image"] . '">
<!-- Twitter Card data -->
<meta name="twitter:card" content="' . @$metas["card"] . '">
<meta name="twitter:site" content="' . @$metas["creator"] . '">
<meta name="twitter:title" content="' . @$metas["title"] . '">
<meta name="twitter:description" content="' . @$metas["description"] . '">
<meta name="twitter:creator" content="' . @$metas["creator"] . '">
<!-- Twitter summary card with large image must be at least 280x150px -->
<meta name="twitter:image:src" content="' . @$metas["image"] . '">
<meta name="twitter:image" content="' . @$metas["image"] . '">
<!-- Open Graph data -->
<meta property="og:title" content="' . @$metas["title"] . '" />
<meta property="og:type" content="' . @$metas["type"] . '" />
<meta property="og:url" content="' . @$metas["url"] . '" />
<meta property="og:image" content="' . @$metas["image"] . '" />
<meta property="og:description" content="' . @$metas["description"] . '" />
<meta property="og:site_name" content="' . @$metas["site_name"] . '" />
<meta property="article:published_time" content="' . @$metas["published_time"] . '" />
<meta property="article:modified_time" content="' . @$metas["modified_time"] . '" />
<meta property="article:section" content="' . @$metas["section"] . '" />
<meta property="article:tag" content="' . @$metas["tag"] . '" />
<meta property="fb:admins" content="' . @$metas["fb_admin"] . '" />';

	return $res;
}
