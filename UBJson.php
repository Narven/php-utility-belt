<?php

/**
 * Class UBJson
 *
 * Helpers for json related stuff
 * @version 0.1
 */
class UBJson
{
	public static function toJsonp( $data )
	{
		$data = json_encode( $data );

		return isset( $_GET[ 'callback' ] ) ? $_GET[ 'callback' ] . '(' . $data . ');' : $data;
	}
}