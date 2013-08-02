<?php

/**
 * @file
 * UI template for Banckle Online Meeting Widget
 *
 * @see banckle_online_meeting_theme()
 *
 * @ingroup themeable
 */
 
function curlRequest($url, $method="GET", $headerType="XML", $xmlsrc="") {

	 $method = strtoupper($method);
	 $headerType = strtoupper($headerType);
	 $session = curl_init();
	 curl_setopt($session, CURLOPT_URL, $url);
	 if ($method == "GET") {
		  curl_setopt($session, CURLOPT_HTTPGET, 1);
	 } else {
		  curl_setopt($session, CURLOPT_POST, 1);
		  curl_setopt($session, CURLOPT_POSTFIELDS, $xmlsrc);
		  curl_setopt($session, CURLOPT_CUSTOMREQUEST, $method);
	 }
	 curl_setopt($session, CURLOPT_HEADER, false);
	 if ($headerType == "XML") {
		  curl_setopt($session, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));
	 } else {
		  curl_setopt($session, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	 }
	 curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	 if (preg_match("/^(https)/i", $url))
		  curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
	 $result = curl_exec($session);
	 curl_close($session);
	 return $result;
}


	$jsonText = curlRequest('https://apps.banckle.com/meeting/api/widget?wid=' . variable_get('banckle_online_meeting_widget_code'), "GET", "JSON", "");

	$arr = array();
	$jsonError = false;

	if ($jsonText !== false) {
		$arr = json_decode($jsonText, true);
		switch (json_last_error ()) {
			case JSON_ERROR_NONE:
				$jsonError = false;
				break;
			default:
				$jsonError = true;
				break;
		}

		if ($jsonError === true) {
?>
			<iframe src="https://apps.banckle.com/meeting/api/widget?wid=<?php echo variable_get('banckle_online_meeting_widget_code'); ?>&showlogo=<?php echo variable_get('banckle_online_meeting_show_logo'); ?>" style="width:<?php echo variable_get('banckle_online_meeting_widget_width'); ?>px;height:<?php echo variable_get('banckle_online_meeting_widget_height'); ?>px;" frameborder="0"></iframe>
<?php
		} else {
			if (array_key_exists('error', $arr)) {
				echo "Oops, Meeting is expired!";
			}
		}
	}
?>