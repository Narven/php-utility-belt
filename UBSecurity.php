<?php

/**
 * Class BCSecurity
 *
 * Helpers for security related stuff
 * ex: password hashing, password encryption / decription
 *
 * @version 0.1
 */
class BCSecurity
{
	/**
	 * HMAC
	 *
	 * Calculate HMAC according to RFC 2104, for chosen algorithm.
	 * http://www.ietf.org/rfc/rfc2104.txt
	 *
	 * @access    public
	 *
	 * @param string hash algorithm
	 * @param string key to sign hash with
	 * @param string data to be hashed
	 *
	 * @return    string
	 */
	public static function hmac( $hashfunc, $key, $data )
	{
		$blocksize = 64;

		if( strlen( $key ) > $blocksize )
		{
			$key = pack( 'H*', $hashfunc( $key ) );
		}

		$key  = str_pad( $key, $blocksize, chr( 0x00 ) );
		$ipad = str_repeat( chr( 0x36 ), $blocksize );
		$opad = str_repeat( chr( 0x5c ), $blocksize );
		$hmac = pack( 'H*', $hashfunc( ( $key ^ $opad ) . pack( 'H*', $hashfunc( ( $key ^ $ipad ) . $data ) ) ) );

		return bin2hex( $hmac );
	}

	/**
	 * HMAC-SHA1
	 *
	 * Calculate HMAC-SHA1 according to RFC 2104.
	 * http://www.ietf.org/rfc/rfc2104.txt
	 *
	 * @access    public
	 *
	 * @param    string    key to sign hash with
	 * @param    string    data to be hashed
	 *
	 * @return    string
	 */
	public static function hmacSha1( $key, $data )
	{
		return self::hmac( 'sha1', $key, $data );
	}

	/**
	 * HMAC-MD5
	 *
	 * Calculate HMAC-MD5 according to RFC 2104.
	 * http://www.ietf.org/rfc/rfc2104.txt
	 *
	 * @access    public
	 *
	 * @param    string    key to sign hash with
	 * @param    string    data to be hashed
	 *
	 * @return    string
	 */
	public static function hmacMd5( $key, $data )
	{
		return self::hmac( 'md5', $key, $data );
	}

	/**
	 * Hex to Base64
	 *
	 * Convert hex to base64.
	 *
	 * @access    public
	 *
	 * @param    string
	 *
	 * @return    string
	 */
	public static function hex2b64( $str )
	{
		$raw = '';

		for( $i = 0; $i < strlen( $str ); $i += 2 )
		{
			$raw .= chr( hexdec( substr( $str, $i, 2 ) ) );
		}

		return base64_encode( $raw );
	}

	/**
	 * Encrypt password
	 *
	 */
	public static function encrypt( $plaintext, $salt )
	{
		$td = mcrypt_module_open( 'cast-256', '', 'ecb', '' );

		$iv = mcrypt_create_iv( mcrypt_enc_get_iv_size( $td ), MCRYPT_RAND );

		mcrypt_generic_init( $td, $salt, $iv );

		$encrypted_data = mcrypt_generic( $td, $plaintext );

		mcrypt_generic_deinit( $td );
		mcrypt_module_close( $td );

		$encoded_64 = base64_encode( $encrypted_data );

		return trim( $encoded_64 );
	}

	/**
	 * dEncrypt password
	 *
	 */
	public static function decrypt( $crypttext, $salt )
	{
		$decoded_64 = base64_decode( $crypttext );

		$td = mcrypt_module_open( 'cast-256', '', 'ecb', '' );

		$iv = mcrypt_create_iv( mcrypt_enc_get_iv_size( $td ), MCRYPT_RAND );

		mcrypt_generic_init( $td, $salt, $iv );

		$decrypted_data = mdecrypt_generic( $td, $decoded_64 );

		mcrypt_generic_deinit( $td );
		mcrypt_module_close( $td );

		return utf8_decode( trim( $decrypted_data ) );
	}

	public static function randomPassword( $length = 8 )
	{
		// start with a blank password
		$password = "";

		// define possible characters - any character in this string can be
		// picked for use in the password, so if you want to put vowels back in
		// or add special characters such as exclamation marks, this is where
		// you should do it
		$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

		// we refer to the length of $possible a few times, so let's grab it now
		$maxlength = strlen( $possible );

		// check for length overflow and truncate if necessary
		if( $length > $maxlength )
		{
			$length = $maxlength;
		}

		// set up a counter for how many characters are in the password so far
		$i = 0;

		// add random characters to $password until $length is reached
		while( $i < $length )
		{

			// pick a random character from the possible ones
			$char = substr( $possible, mt_rand( 0, $maxlength - 1 ), 1 );

			// have we already used this character in $password?
			if( !strstr( $password, $char ) )
			{
				// no, so it's OK to add it onto the end of whatever we've already got...
				$password .= $char;
				// ... and increase the counter by one
				$i++;
			}

		}

		// done!
		return $password;
	}
}