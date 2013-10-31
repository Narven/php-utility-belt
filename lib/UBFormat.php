<?php

/**
 * Class UBFormat
 *
 * Helpers for json related stuff
 * @version 0.1
 */
class UBFormat
{
	public static function json2Jsonp( $data )
	{
		$data = json_encode( $data );

		return isset( $_GET[ 'callback' ] ) ? $_GET[ 'callback' ] . '(' . $data . ');' : $data;
	}

	/**
	 * Detect browser language
	 *
	 * @param        $availableLanguages
	 * @param string $default
	 *
	 * @return string
	 */
	public static function detectBrowserLang( $availableLanguages, $default = 'en' )
	{
		if( isset( $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ] ) )
		{
			$langs = explode( ',', $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ] );

			foreach( $langs as $value )
			{
				$choice = substr( $value, 0, 2 );

				if( in_array( $choice, $availableLanguages ) )
				{
					return $choice;
				}
			}
		}

		return $default;
	}

	/**
	 * Convert currencies using cURl and Google
	 *
	 * Converting currencies isn’t very hard to do, but as the currencies
	 * fluctuates all the time, we definitely need to use a service like
	 * Google to get the most recent values. The currency() function take
	 * 3 parameters: from, to, and sum.
	 *
	 * @param $from_Currency
	 * @param $to_Currency
	 * @param $amount
	 *
	 * @return float
	 */
	public static function currency( $from_Currency, $to_Currency, $amount )
	{
		$amount        = urlencode( $amount );
		$from_Currency = urlencode( $from_Currency );
		$to_Currency   = urlencode( $to_Currency );
		$url           = "http://www.google.com/ig/calculator?hl=en&q=$amount$from_Currency=?$to_Currency";
		$ch            = curl_init();
		$timeout       = 0;
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)" );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		$rawdata = curl_exec( $ch );
		curl_close( $ch );
		$data = explode( '"', $rawdata );
		$data = explode( ' ', $data[ '3' ] );
		$var  = $data[ '0' ];

		return round( $var, 2 );
	}
}