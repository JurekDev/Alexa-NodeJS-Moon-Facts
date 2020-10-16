if (!$recursion && (
			$curl_response_code == '301' ||
			$curl_response_code == '302' ||
			stripos($url_contents,'">Found</a>') !== false ||
			stripos($url_contents,'Moved Permanently') !== false ||
			strpos($url_contents,'Object moved') !== false
	)) {
		// Use cURL redirect URL if provided
		if (!empty($curl_redirect_url)) {
			if ($R34ICS->debug) { $R34ICS->debug_messages[$url]['Load status'][] = 'Recursively loaded URL ' . $curl_redirect_url . ' by following a rewrite returned by the server'; }
			$url_contents = r34ics_url_get_contents($curl_redirect_url, true);
		}
		// Scrape URL from returned HTML if necessary
		else {
			preg_match('/<(a href|A HREF)="([^"]+)"/', $url_contents, $url_match);
			if (isset($url_match[2])) {
				if ($R34ICS->debug) { $R34ICS->debug_messages[$url]['Load status'][] = 'Recursively loaded URL ' . $url_match[2] . ' by following a rewrite returned by the server'; }
				$url_contents = r34ics_url_get_contents($url_match[2], true);
			}
			else {
				if ($R34ICS->debug) { $R34ICS->debug_messages[$url]['Errors'][] = 'No redirect URL provided by server'; }
			}
		}
	}
	// Cannot retrieve file
	if (empty($url_contents)) {
		if ($R34ICS->debug) { $R34ICS->debug_messages[$url]['Errors'][] = 'URL contents empty (' . $url . ')'; }
		$url_contents = false;
	}
	else {
		if ($R34ICS->debug == 2) { $R34ICS->debug_messages[$url]['URL contents retrieved'] = $url_contents; }
		elseif ($R34ICS->debug) { $R34ICS->debug_messages[$url]['URL contents retrieved'] = strlen($url_contents) . ' bytes'; }
	}
	
	return $url_contents;
}
