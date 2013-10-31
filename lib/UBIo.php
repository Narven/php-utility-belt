<?php


class UBIo
{
	/**
	 * file_exists does not check the include paths. This function does.
	 * It was not written by me, I don't know where it's from exactly.
	 * Let me know if you do.
	 *
	 * @param string $file
	 *
	 * @return boolean
	 */
	public static function fileExistsInPath( $file )
	{
		// Using explode on the include_path is three times faster than using fopen

		// no file requested?
		$file = trim( $file );
		if( !$file )
		{
			return false;
		}

		// using an absolute path for the file?
		// dual check for Unix '/' and Windows '\',
		// or Windows drive letter and a ':'.
		$abs = ( $file[ 0 ] == '/' || $file[ 0 ] == '\\' || $file[ 1 ] == ':' );
		if( $abs && file_exists( $file ) )
		{
			return $file;
		}

		// using a relative path on the file
		$path = explode( PATH_SEPARATOR, ini_get( 'include_path' ) );
		foreach( $path as $base )
		{
			// strip Unix '/' and Windows '\'
			$target = rtrim( $base, '\\/' ) . DIRECTORY_SEPARATOR . $file;
			if( file_exists( $target ) )
			{
				return $target;
			}
		}

		// never found it
		return false;
	}

	/**
	 * Check if file exists, using CURL
	 *
	 * @param $path
	 *
	 * @return bool
	 */
	public static function isFileOk( $path )
	{
		return false;

		$fileHeaders = @get_headers( $path );

		if( $fileHeaders[ 0 ] == 'HTTP/1.1 404 Not Found' )
		{
			return false;
		}
		else
		{
			return true;
		}

		/*
		$ch = curl_init( $path );
		curl_setopt( $ch, CURLOPT_NOBODY, true );
		curl_setopt($ch, CURLOPT_TIMEOUT, 1); // on sec timeout
		curl_exec( $ch );
		$retcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );

		//Yii::log($path, CLogger::LEVEL_ERROR, "AVATAR PATH");

		if($retcode == '200')
		{
			return true;
		}
		*/

		/*
		if(file_exists($path))
		{
			return true;
		}
		*/

		return false;
	}

	/**
	 * Function: recursiveGlob
	 * Recursively goes through a folder and returns all files.
	 *
	 * Parameters:
	 * $pattern - String
	 * $flags - Boolean
	 * $path - String
	 *
	 * Returns:
	 * $files - Array
	 */

	/**
	 * Recursively goes through a folder and returns all files.
	 *
	 * @param string $pattern
	 * @param int    $flags
	 * @param string $path
	 *
	 * @link http://www.adampatterson.ca/blog/2013/01/recursive-glob/
	 *
	 * @return array
	 */
	public static function recursiveGlob( $pattern = '*', $flags = 0, $path = '' )
	{
		$paths = glob( $path . '*', GLOB_MARK | GLOB_ONLYDIR | GLOB_NOSORT );

		$files = glob( $path . $pattern, $flags );

		foreach( $paths as $path )
		{
			$files = array_merge( $files, recursive_glob( $pattern, $flags, $path ) );
		}

		return $files;
	}

	/**
	 * Getting the Latitude and Longitude of an address using the Google Maps API
	 *
	 * @param string $address
	 * @param string $city
	 * @param string $province
	 * @param string $country
	 * @param string $postalcode
	 *
	 * @example UBIo::googleHTTPGeocoding('1 Sir Winston Churchill Square', 'edmonton', 'alberta', 'canada', 'T5J 2R7');
	 *
	 * @return string
	 */
	public static function googleHTTPGeocoding( $address = '', $city = '', $province = '', $country = '', $postalcode = '' )
	{
		// Google Geo Address
		$googleAddress = "http://maps.google.com/maps/geo?q=" . urlencode( $address ) . '+' . urlencode( $city ) . '+' . urlencode( $province ) . '+' . urlencode( $postalcode ) . '+' . urlencode( $country ) . "&output=xml";

		// Retrieve the URL contents
		$googlePage = file_get_contents( $googleAddress );

		// Parse the returned XML file
		$xml = new SimpleXMLElement( $googlePage );

		// Parse the coordinate string
		list( $longitude, $latitude, $altitude ) = explode( ",", $xml->Response->Placemark->Point->coordinates );

		// Output the coordinates
		return "Longitude: $longitude, Latitude: $latitude";

	}

	/**
	 * Returns new array and preserves until a specific line is found in the old one.
	 * Ideal for rewriting config files with dynamic content, but still allowing
	 * custom rules above that.
	 *
	 * Tip, in combination with file(), consider using the FILE_IGNORE_NEW_LINES flag
	 *
	 * @param array  $original
	 * @param array  $dynamic
	 * @param string $splitLine
	 *
	 * @return array
	 */
	public static function preserveUntil( $original = array(), $dynamic = array(), $splitLine = "# PLEASE DO NOT EDIT BELOW THIS LINE! #" )
	{
		$new = array();

		$splitLineAt = false;
		foreach( $original as $n => $line )
		{
			if( trim( $line ) == trim( $splitLine ) )
			{
				$splitLineAt = $n;
				break;
			}
		}

		if( is_numeric( $splitLineAt ) )
		{
			$new = array_slice( $original, 0, ( $splitLineAt ) );
		}
		else
		{
			// Failsafe. No splitLine found. Preserve entire original.
			$new = $original;
		}

		$new[ ] = $splitLine;
		$new    = array_merge( $new, $dynamic );

		return $new;
	}

	/**
	 * emailTracker
	 *
	 * Find out if your email has been read
	 *
	 * @return image
	 * @todo NOT TESTED
	 * @link http://www.catswhocode.com/blog/useful-snippets-for-php-developers
	 */
	public static function emailTracker()
	{
		Header( "Content-Type: image/jpeg" );

		//Get IP
		if( !empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) )
		{
			$ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
		}
		elseif( !empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) )
		{
			$ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
		}
		else
		{
			$ip = $_SERVER[ 'REMOTE_ADDR' ];
		}

		//Time
		$actual_time      = time();
		$actual_day       = date( 'Y.m.d', $actual_time );
		$actual_day_chart = date( 'd/m/y', $actual_time );
		$actual_hour      = date( 'H:i:s', $actual_time );

		//GET Browser
		$browser = $_SERVER[ 'HTTP_USER_AGENT' ];

		//LOG
		$myFile     = "log.txt";
		$fh         = fopen( $myFile, 'a+' );
		$stringData = $actual_day . ' ' . $actual_hour . ' ' . $ip . ' ' . $browser . ' ' . "\r\n";
		fwrite( $fh, $stringData );
		fclose( $fh );

		//Generate Image (Es. dimesion is 1x1)
		$newimage = ImageCreate( 1, 1 );
		$grigio   = ImageColorAllocate( $newimage, 255, 255, 255 );
		ImageJPEG( $newimage );
		ImageDestroy( $newimage );

		return $grigio;
	}

	/**
	 * Create Data URI’s
	 * Data URI’s can be useful for embedding images into HTML/CSS/JS
	 * to save on HTTP requests. The following function will create a
	 * Data URI based on $file for easier embedding.
	 *
	 * @param $file
	 * @param $mime
	 *
	 * @return string
	 * @link http://www.catswhocode.com/blog/useful-snippets-for-php-developers
	 * @todo not tested
	 */
	public static function createDateUri( $file, $mime )
	{
		$contents = file_get_contents( $file );

		$base64 = base64_encode( $contents );

		return "data:$mime;base64,$base64";
	}

	/**
	 * download
	 * Download from remote on your server
	 *
	 * @param null $url
	 *
	 * @return string
	 */
	public static function download( $url = null )
	{
		return file_get_contents( $url );
	}

	/**
	 * save
	 *
	 * @param null $what
	 * @param null $to
	 *
	 * @return int
	 */
	public static function save( $what = null, $to = null )
	{
		return file_put_contents( $to, $what );
	}

	/**
	 * Display number of Facebook fans in full text
	 *
	 * @param null $pageId
	 *
	 * @return mixed
	 */
	public static function facebookNumberFans( $pageId = null )
	{
		$xml  = @simplexml_load_file( "http://api.facebook.com/restserver.php?method=facebook.fql.query&query=SELECT%20fan_count%20FROM%20page%20WHERE%20page_id=" . $pageId . "" ) or die ( "a lot" );
		$fans = $xml->page->fan_count;

		return $fans;
	}

	/**
	 * @param $file
	 * @param $destination
	 *
	 * @return string
	 */
	public static function unzip( $file, $destination )
	{
		try
		{
			// create object
			$zip = new ZipArchive();
			// open archive
			if( $zip->open( $file ) !== true )
			{
				die ( 'Could not open archive' );
			}
			// extract contents to destination directory
			$zip->extractTo( $destination );
			// close archive
			$zip->close();
		}
		catch( Exception $e )
		{
			return $e->getMessage();
		}
	}

	/**
	 * Detect location by IP
	 *
	 * Here is an useful code snippet to detect the location of a specific IP.
	 * The function below takes one IP as a parameter, and returns the location
	 * of the IP. If no location is found, UNKNOWN is returned.
	 *
	 * @param $ip
	 *
	 * @return string
	 */
	public static function detectCityByIP( $ip )
	{
		$default = 'UNKNOWN';

		if( !is_string( $ip ) || strlen( $ip ) < 1 || $ip == '127.0.0.1' || $ip == 'localhost' )
		{
			$ip = '8.8.8.8';
		}

		$curlopt_useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)';

		$url = 'http://ipinfodb.com/ip_locator.php?ip=' . urlencode( $ip );
		$ch  = curl_init();

		$curl_opt = array(
			CURLOPT_FOLLOWLOCATION => 1,
			CURLOPT_HEADER         => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_USERAGENT      => $curlopt_useragent,
			CURLOPT_URL            => $url,
			CURLOPT_TIMEOUT        => 1,
			CURLOPT_REFERER        => 'http://' . $_SERVER[ 'HTTP_HOST' ],
		);

		curl_setopt_array( $ch, $curl_opt );

		$content = curl_exec( $ch );

		if( !is_null( $curl_info ) )
		{
			$curl_info = curl_getinfo( $ch );
		}

		curl_close( $ch );

		if( preg_match( '{<li>City : ([^<]*)</li>}i', $content, $regs ) )
		{
			$city = $regs[ 1 ];
		}
		if( preg_match( '{<li>State/Province : ([^<]*)</li>}i', $content, $regs ) )
		{
			$state = $regs[ 1 ];
		}

		if( $city != '' && $state != '' )
		{
			$location = $city . ', ' . $state;

			return $location;
		}
		else
		{
			return $default;
		}

	}

	/**
	 * Check if a specific website is available
	 *
	 * Want to know if a specific website is available? cURL is here to help.
	 * This script can be used with a cron job to monitor your websites.
	 *
	 * @param $domain
	 *
	 * @return bool
	 */
	public static function isDomainOnline( $domain )
	{
		//check, if a valid url is provided
		if( !filter_var( $domain, FILTER_VALIDATE_URL ) )
		{
			return false;
		}

		//initialize curl
		$curlInit = curl_init( $domain );
		curl_setopt( $curlInit, CURLOPT_CONNECTTIMEOUT, 10 );
		curl_setopt( $curlInit, CURLOPT_HEADER, true );
		curl_setopt( $curlInit, CURLOPT_NOBODY, true );
		curl_setopt( $curlInit, CURLOPT_RETURNTRANSFER, true );

		//get answer
		$response = curl_exec( $curlInit );

		curl_close( $curlInit );

		if( $response )
		{
			return true;
		}

		return false;
	}

	/**
	 * cURL replacement for file_get_contents()
	 *
	 * The file_get_contents() function is very useful but it is unfortunely
	 * deactivated by default on some webhosts. Using cURL, we can write a
	 * replacement function that works exactly like file_get_contents().
	 *
	 * @param $url
	 *
	 * @return mixed
	 */
	public static function getFileContents( $url )
	{
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); //Set curl to return the data instead of printing it to the browser.
		curl_setopt( $ch, CURLOPT_URL, $url );

		$data = curl_exec( $ch );
		curl_close( $ch );

		return $data;
	}

	/**
	 * Get remote filesize using cURL
	 *
	 * @param        $url
	 * @param string $user
	 * @param string $pw
	 *
	 * @return string
	 */
	public static function getFileSize( $url, $user = "", $pw = "" )
	{
		ob_start();
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_HEADER, 1 );
		curl_setopt( $ch, CURLOPT_NOBODY, 1 );

		if( !empty( $user ) && !empty( $pw ) )
		{
			$headers = array( 'Authorization: Basic ' . base64_encode( "$user:$pw" ) );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		}

		$ok = curl_exec( $ch );
		curl_close( $ch );
		$head = ob_get_contents();
		ob_end_clean();

		$regex = '/Content-Length:\s([0-9].+?)\s/';
		$count = preg_match( $regex, $head, $matches );

		return isset( $matches[ 1 ] ) ? $matches[ 1 ] : "unknown";
	}

	/**
	 * FTP upload with cURL
	 */
	public function upload()
	{
		// open a file pointer
		$file = fopen( "/path/to/file", "r" );

		// the url contains most of the info needed
		$url = "ftp://username:password@mydomain.com:21/path/to/new/file";

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

		// upload related options
		curl_setopt( $ch, CURLOPT_UPLOAD, 1 );
		curl_setopt( $ch, CURLOPT_INFILE, $fp );
		curl_setopt( $ch, CURLOPT_INFILESIZE, filesize( "/path/to/file" ) );

		// set for ASCII mode (e.g. text files)
		curl_setopt( $ch, CURLOPT_FTPASCII, 1 );

		$output = curl_exec( $ch );
		curl_close( $ch );
	}

	/**
	 * Display source code of any webpage
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public static function showSourceCode( $url )
	{
		$lines = file( $url );

		$code = '';

		foreach( $lines as $line_num => $line )
		{
			// loop thru each line and prepend line numbers
			$code .= "Line #<b>{$line_num}</b> : " . htmlspecialchars( $line ) . "<br>\n";
		}

		return $code;
	}

	/**
	 * Whois query using PHP
	 *
	 * If you need to get the whois information for a specific domain,
	 * why not using PHP to do it? The following function take a domain
	 * name as a parameter, and then display the whois info related
	 * to the domain.
	 *
	 * @param $domain
	 *
	 * @return string
	 */
	public static function whoIs( $domain )
	{
		// fix the domain name:
		$domain = strtolower( trim( $domain ) );
		$domain = preg_replace( '/^http:\/\//i', '', $domain );
		$domain = preg_replace( '/^www\./i', '', $domain );
		$domain = explode( '/', $domain );
		$domain = trim( $domain[ 0 ] );

		// split the TLD from domain name
		$_domain = explode( '.', $domain );
		$lst     = count( $_domain ) - 1;
		$ext     = $_domain[ $lst ];

		// You find resources and lists
		// like these on wikipedia:
		//
		// http://de.wikipedia.org/wiki/Whois
		//
		$servers = array(
			"biz"  => "whois.neulevel.biz",
			"com"  => "whois.internic.net",
			"us"   => "whois.nic.us",
			"coop" => "whois.nic.coop",
			"info" => "whois.nic.info",
			"name" => "whois.nic.name",
			"net"  => "whois.internic.net",
			"gov"  => "whois.nic.gov",
			"edu"  => "whois.internic.net",
			"mil"  => "rs.internic.net",
			"int"  => "whois.iana.org",
			"ac"   => "whois.nic.ac",
			"ae"   => "whois.uaenic.ae",
			"at"   => "whois.ripe.net",
			"au"   => "whois.aunic.net",
			"be"   => "whois.dns.be",
			"bg"   => "whois.ripe.net",
			"br"   => "whois.registro.br",
			"bz"   => "whois.belizenic.bz",
			"ca"   => "whois.cira.ca",
			"cc"   => "whois.nic.cc",
			"ch"   => "whois.nic.ch",
			"cl"   => "whois.nic.cl",
			"cn"   => "whois.cnnic.net.cn",
			"cz"   => "whois.nic.cz",
			"de"   => "whois.nic.de",
			"fr"   => "whois.nic.fr",
			"hu"   => "whois.nic.hu",
			"ie"   => "whois.domainregistry.ie",
			"il"   => "whois.isoc.org.il",
			"in"   => "whois.ncst.ernet.in",
			"ir"   => "whois.nic.ir",
			"mc"   => "whois.ripe.net",
			"to"   => "whois.tonic.to",
			"tv"   => "whois.tv",
			"ru"   => "whois.ripn.net",
			"org"  => "whois.pir.org",
			"aero" => "whois.information.aero",
			"nl"   => "whois.domain-registry.nl"
		);

		if( !isset( $servers[ $ext ] ) )
		{
			die( 'Error: No matching nic server found!' );
		}

		$nic_server = $servers[ $ext ];

		$output = '';

		// connect to whois server:
		if( $conn = fsockopen( $nic_server, 43 ) )
		{
			fputs( $conn, $domain . "\r\n" );
			while( !feof( $conn ) )
			{
				$output .= fgets( $conn, 128 );
			}
			fclose( $conn );
		}
		else
		{
			die( 'Error: Could not connect to ' . $nic_server . '!' );
		}

		return $output;
	}

	/**
	 * Generate QR codes with metadata.
	 *
	 * This is a simple function to generate a qrcode with metadata inside
	 * ( TXT for text, EMAIL for email, TEL for phone number and URL for a website link ).
	 *
	 * @param        $data
	 * @param string $type
	 * @param string $size
	 * @param string $ec
	 * @param string $margin
	 *
	 * @example header( "Content-type: image/png" ); echo qr_code("http://emoticode.net", "URL");
	 *
	 * @return mixed
	 */
	public static function generateQrCode( $data, $type = "TXT", $size = '150', $ec = 'L', $margin = '0' )
	{
		$types = array( "URL" => "http://", "TEL" => "TEL:", "TXT" => "", "EMAIL" => "MAILTO:" );
		if( !in_array( $type, array( "URL", "TEL", "TXT", "EMAIL" ) ) )
		{
			$type = "TXT";
		}
		if( !preg_match( '/^' . $types[ $type ] . '/', $data ) )
		{
			$data = str_replace( "\\", "", $types[ $type ] ) . $data;
		}
		$ch   = curl_init();
		$data = urlencode( $data );
		curl_setopt( $ch, CURLOPT_URL, 'http://chart.apis.google.com/chart' );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, 'chs=' . $size . 'x' . $size . '&cht=qr&chld=' . $ec . '|' . $margin . '&chl=' . $data );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );

		$response = curl_exec( $ch );

		curl_close( $ch );

		return $response;
	}

}