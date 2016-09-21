<?php
	/*
	** Database Credentials
	*/
	// in production this would go in secure config file
	$server = '127.0.0.1';
	$user = 'root';
	$pass = '';
	$db = 'someDB';
	
	/*
	** Query Database
	*/
	$connectDB = new mysqli($server, $user, $pass, $db);
	if ($connectDB->connect_errno) {
		exit( "Failed to connect to MySQL: (" . $connectDB->connect_errno . ") " . $connectDB->connect_error );
	}
	
	$query = $connectDB->query('Select Distinct `COL 1` From sometable');
	if(!$query) {
		exit( 'Error with query! error: ' . $connectDB->errno . '\n'. $connectDB->error );
	}
	$connectDB->close();
	
	/*
	** Hit urls for HTML
	*/
	foreach ($query as $url) {
		//exit(print_r($url));
		$hit = curl_init($url['COL 1']);
		curl_setopt($hit, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hit, CURLOPT_BINARYTRANSFER, true);
		$html = curl_exec($hit);
		curl_close($hit);
		
		if($html === false) {
			exit( 'Curl Error! error: ' . $hit->errno . '\n'. $hit->error );
		}
		
		/*
		** Parse HTML filtering off domain links
		*/
		$dom = new DOMDocument;
		libxml_use_internal_errors(true); //suppress html5 warnings
		$dom->loadHTML($html);
		$links = [];
		foreach($dom->getElementsByTagName('a') as $a) {
			if(!preg_match("/\.(jpg|jpeg|png|gif|css|js)/i", $a->getAttribute("href"))
				&&preg_match("/^\//", $a->getAttribute("href"))) {
				array_push($links, $a->getAttribute("href"));
			}
		}
		
		/*
		** Build XML
		*/
		$xml = new SimpleXMLElement('<sitemap></sitemap>');
		$xml->addChild('BaseURL', $url['COL 1']);
		$linksTo = $xml->addChild('linksTo');
		foreach ($links as $link) {
			$linksTo->addChild('URL', $link);
		}
	
	}
	
	/*
	** Do something with the xml
	*/
	$xml->asXML('mySiteMap.xml');
	exit('Site Mapped!');
?>
