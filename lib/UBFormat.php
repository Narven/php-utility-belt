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
}