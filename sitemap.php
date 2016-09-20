<?php
	/*
	** Database Credentials
	*/
	// in production this would go in secure config file
	$server = 'someServer';
	$user = 'admin';
	$pass = 'pass';
	$db = 'someDB';
	
	/*
	** Query Database
	*/
	$connectDB = new mysqli($server, $user, $pass, $db);
	if ($connectDB->connect_errno) {
		exit( "Failed to connect to MySQL: (" . $connectDB->connect_errno . ") " . $connectDB->connect_error );
	}
	
	if(!$connectDB->query('Select Distinct(*) From A') = $query) {
		exit( 'Error with query! error: ' . $connectDB->errno . '\n'. $connectDB->error );
	}
	
	/*
	** Hit urls for HTML
	*/
	$export; // we'll use this to house the final xml map
	
	foreach $query as $url {
		$html = file_get_contents($url);
		
		/*
		** Parse HTML filtering off domain links
		*/
		$links = preg_grep("/(http:\/\/www.foreverbride.com\/)|(https:\/\/www.foreverbride.com\/)/", explode("\n", $$html));
		
		/*
		** Build XML
		*/
		$xml = new SimpleXMLElement($url);
		$linksTo = $xml->addChild('linksTo');
		foreach $links as $link {
			$linksTo->addChild('url', $link)
		}
		$export .= $xml;
	}
	
	/*
	** Do something with the xml
	*/
	// can write to file
	file_put_contents ('mySiteMap.xml', $export);
	//or echo it out
	echo $export;
	//or do whatever you want with it
?>
