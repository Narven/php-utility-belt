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
	 * Checks if exists and generates the url for the user avatar
	 *
	 * @param $id sha1(userid)
	 *
	 * @return string
	 */
	public static function getAvatar( $id )
	{
		$avatarDefaultUrl = Yii::app()->getBaseUrl( true ) . MEDIA_URL . 'default/avatar.png';

		//return $avatarDefaultUrl;

		if( !$id )
		{
			return $avatarDefaultUrl;
		}
		else
		{
			$avatar = MEDIA_URI . $id . '.png';

			if( file_exists( $avatar ) )
			{

				return Yii::app()->getBaseUrl( true ) . MEDIA_URL . $id . '.png';
			}
			else
			{
				return $avatarDefaultUrl;
			}
		}

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
	 * @example BCIo::googleHTTPGeocoding('1 Sir Winston Churchill Square', 'edmonton', 'alberta', 'canada', 'T5J 2R7');
	 *
	 * @return string
	 */
	public static function googleHTTPGeocoding($address = '', $city = '', $province = '', $country = '', $postalcode = '')
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
}